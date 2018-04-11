<?php
/**
 * PARTS CATALOG LIST FILE
 * Created by PhpStorm.
 * User: ALEX
 * Date: 04.04.2018
 * Time: 20:34
 * Выбор загрузки картинки
 *
 */
?>
<?php


echo "PARTS CATALOG LIST FILE"."</br></br>";

include "config.php";
include "lib.php";

$id = ""; $subcat_id = ""; $NO = ""; $part_num = ""; $part_name = ""; $search_url = ""; $meta = ""; $description = ""; $action = ""; $part_id = "";         // Объявление (задание) пустых перевенных для метода POST или GET . Рома сказал будут ошибки если не задать.

if (isset($_GET['id']))   {               $id            =  $_GET['id']; echo "id взята из GET$br";}// isset проверяет есть такая переменная
if (isset($_POST['id']))  {               $id            =  $_POST['id']; echo "id взята из POST$br";}
if (isset($_POST['subcat_id']))           $subcat_id     =  $_POST['subcat_id'];
if (isset($_POST['NO']))                  $NO            =  $_POST['NO'];
if (isset($_POST['part_num']))            $part_num      =  $_POST['part_num'];
if (isset($_POST['part_name']))           $part_name     =  $_POST['part_name'];
if (isset($_POST['search_url']))          $search_url    =  $_POST['search_url'];
if (isset($_POST['meta']))                $meta          =  $_POST['meta'];
if (isset($_POST['description']))         $description   =  $_POST['description'];
if (isset($_POST['action']))              $action        =  $_POST['action'];
if (isset($_POST['part_id']))             $part_id       =  $_POST['part_id'];

@$id = $id+1-1;
if ($id<1) exitr("ошибка доступа"."<br>"."<a><input type=\"button\" onclick=\"history.back();\" value=\"Назад\"/></a>"."<br>"."<img src=https://akphoto1.ask.fm/894/430/122/-329996973-208iid5-8ch6ohrqh9ebopp/original/file.jpg>");

//require "edit_subcat.php";




$link = opendb();					// Открываем базу данных


/**********************************START ПРОЦЕДУРА ДОБАВЛЕНИЯ НОВОЙ СТРОКИ ****************************************/
$query = "select MAX(`NO`) from 1parts_list where subcat_id ='$id';"; // Переменная запроса MySQL на вычисление максимального значения в колонке NO в строке
$result = mysqli_query($link, $query);	// Выполняем запрос и кладем ответ в переменную $result
$nn = mysqli_fetch_row($result);
echo $nn=$nn[0]+1;


$insert = "INSERT INTO `1parts_list` (`subcat_id`, `NO`, `part_name`, `part_num`, `search_url`, `meta`, `description`) VALUES ('$id','$nn','$part_name','$part_num','$search_url','$meta','$description');"; //Переменная содержащая строку запроса MySQL
$del = "DELETE FROM `1parts_list` WHERE `1parts_list`.`id` = $part_id";


echo $br;

switch ($action) {
    case "addnewline":
	    echo "ADD NEW LINE".$br;
	    mysqli_query($link,$insert); // Запрос к таблице на добавление новой строки

	    echo "Печатаем строку запроса MySQL: ".$insert;
	    echo $br;
	    break;

    case "del_line":
        mysqli_query($link,$del); // Запрос к таблице на удаление строки с нужным Id (part_id)
	    echo "Печатаем строку запроса MySQL: ".$del;
	    echo $br;
        echo "DELETED LINE WITH ID".$part_id;
	    echo $br;
        break;


	    //"<img src=http://risovach.ru/upload/2013/10/mem/tipichnyj-nedosypayucshij_32433296_orig_.jpg>";

}




//    if ($action == "addnewline") {
//    echo "ADD NEW LINE".$br;
//    mysqli_query($link,$insert); // Запрос к таблице на добавление новой строки
//
//    echo "Печатаем строку запроса MySQL: ".$insert;
//    echo $br;
//
//}
//    else echo "NOT ADD NEW LINE".$br ;


/**********************************END ПРОЦЕДУРА ДОБАВЛЕНИЯ НОВОЙ СТРОКИ ****************************************/

/*************************************START ПРОЦЕДУРА УДАЛЕНИЕ  СТРОКИ ******************************************/







/******************************************START ЗАГОЛОВОК ТАБЛИЦЫ **********************************************/
echo "<form name=\"list\" method=\"post\" action=\"\">\r\n0".

	'<input disabled type="text" name="id"              value="MySQL id"         size="1">'."\r\n".
	'<input disabled type="text" name="subcat_id"       value="SC id"            size="1">'."\r\n".
	'<input disabled type="text" name="NO"              value="NN"               size="1">'."\r\n".
	'<input disabled type="text" name="part_num"        value="Артикул"          size="">'."\r\n".
	'<input disabled type="text" name="part_name"       value="Наименование"     size="">'."\r\n".
	'<input disabled type="text" name="part_link"       value="Ссылка"           size="">'."\r\n".
	'<input disabled type="text" name="meta"            value="meta"             size="10">'."\r\n".
	'<input disabled type="text" name="descr"           value="description"      size="10">'."\r\n".
	"</form>\r\n";
/*******************************************END ЗАГОЛОВОК ТАБЛИЦЫ ************************************************/

/*******************************************START TABLE PRINTING *************************************************/
//$mysql = assoc_query($link,"1parts_list", "*", 1);




$query = "SELECT * FROM `1parts_list` WHERE subcat_id = $id;";


//echo $query . "<br>\r\n";
$result = mysqli_query($link, $query);	// Выполняем запрос и кладем ответ в переменную $result


$lines_q = mysqli_num_rows($result);  //Выдергиваем количество линий в ответе

echo "Количество строк в таблице равно:  ".$lines_q."$br"; // тут два перевода строки
echo "ID Родительской категории равна:  ".$id."$br"."$br";

for ($x = 1; $x <= $lines_q; $x++) {
	$line = mysqli_fetch_row ($result);



	echo "<form name=\"list\" method=\"post\" action=\"\">\r\n".$x.

		'<input disabled type="text" name="part_id"         value="'.$line[0].'" size="1">'."\r\n".
		'<input disabled type="text" name="subcat_id"       value="'.$line[1].'" size="1">'."\r\n".
		'<input disabled type="text" name="NO"              value="'.$x.'"       size="1">'."\r\n".
		'<input disabled type="text" name="part_name"       value="'.$line[4].'" size="">'."\r\n".
		'<input disabled type="text" name="part_num"        value="'.$line[3].'" size="">'."\r\n".
		'<input disabled type="text" name="part_link"       value="'.$line[5].'" size="">'."\r\n".
		'<input disabled type="text" name="meta"            value="'.$line[6].'" size="10">'."\r\n".
		'<input disabled type="text" name="descr"           value="'.$line[7].'" size="10">'."\r\n".

        '<input          type="submit" name="del_button"           value="-" onclick="confirm(\'Удалить строку?\')">'."\r\n".
        '<input          type="hidden" name="action"               value="del_line">'."\r\n".
        '<input          type="hidden" name="part_id"              value="'.$line[0].'">'."\r\n".



		"</form>\r\n";
}
echo "PART ID POST: ".$part_id;
/**********************************************END TABLE PRINTING *************************************************/

?>

<!--*********************** "ANL" is "Add New Line" *****************-->
<!--******************* http://www.softtime.ru/article/?id_article=96-->
<!--<p>Справка: <a href="http://www.softtime.ru/article/?id_article=96">"http://www.softtime.ru/article/?id_article=96"</a></p>-->
<!--<p>Справка по HTML and XHTML Techniques for WCAG 2.0: <a href="https://www.w3.org/TR/WCAG20-TECHS/html.html#H44">"https://www.w3.org/TR/WCAG20-TECHS/html.html#H44"</a></p>-->


<form action="" name="addnewline" method="post">
    <p>
            0<input type="text" name="post_id" size="1">
            <input readonly type="text" name="id" value="<?=$id?>" size="1">
            <input type="text" name="NO" size="1">
            <input type="text" name="part_num">
            <input type="text" name="part_name">
            <input type="text" name="search_url">
            <input type="text" name="meta" size="10">
            <input type="text" name="description" size="10">
            <input type= "hidden" name="action" value="addnewline">
            <input name="Submit" type=submit value="+">

    </p>

</form>

