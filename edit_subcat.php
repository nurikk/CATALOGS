<?php
// https://html5book.ru/css-position/ CSS-позиционирование
// http://htmlbook.ru/samlayout/verstka-na-html5

include "header.html";
echo "PARTS CATALOG LIST FILE"."</br></br>";
include "lib.php";
include "config.php";

/**********************************Start инициализируем передаваемые переменные****************************************/
$id = 0; $action = ""; $category = ""; $catname = "";
$imgurl = ""; $publish = 0; $engine = ""; $meta = "";
$description = "";

if (isset($_GET['action']		)) $action =		$_GET['action'];
if (isset($_GET['id']			)) $id =			$_GET['id'];
if (isset($_GET['category']		)) $category =		$_GET['category'];
if (isset($_GET['catname']		)) $catname =		$_GET['catname'];
if (isset($_GET['imgurl']		)) $imgurl  =		$_GET['imgurl'];
if (isset($_GET['publish']		)) $publish =		1;
if (isset($_GET['engine']		)) $engine  =		$_GET['engine'];
if (isset($_GET['meta']			)) $meta    =		$_GET['meta'];
if (isset($_GET['description']	)) $description=	$_GET['description'];

$id = ""; $subcat_id = ""; $NO = ""; $part_num = ""; $part_name = ""; $search_url = ""; $meta = ""; $description = ""; $action = ""; $part_id = ""; $add_img_url = ""; $picfilename = "";       // Объявление (задание) пустых перевенных для метода POST или GET . Рома сказал будут ошибки если не задать.
$delbutton = ""; $delete = "";

if (isset($_GET['id']))   {               $id            =  $_GET['id']; }// isset проверяет есть ли такая переменная
if (isset($_POST['id']))  {               $id            =  $_POST['id'];}
if (isset($_POST['subcat_id']))           $subcat_id     =  $_POST['subcat_id'];
if (isset($_POST['NO']))                  $NO            =  $_POST['NO'];
if (isset($_POST['part_num']))            $part_num      =  $_POST['part_num'];
if (isset($_POST['part_name']))           $part_name     =  $_POST['part_name'];
if (isset($_POST['search_url']))          $search_url    =  $_POST['search_url'];
if (isset($_POST['meta']))                $meta          =  $_POST['meta'];
if (isset($_POST['description']))         $description   =  $_POST['description'];
if (isset($_POST['action']))              $action        =  $_POST['action'];
if (isset($_POST['part_id']))             $part_id       =  $_POST['part_id'];
if (isset($_POST['add_img_url']))         $add_img_url   =  $_POST['add_img_url'];
if (isset($_POST['picfilename']))         $picfilename   =  $_POST['picfilename'];
if (isset($_POST['delbutton']))           $delbutton     =  $_POST['delbutton'];
if (isset($_POST['delete']))              $delete     =  $_POST['delete'];





/************************************End инициализируем передаваемые переменные****************************************/
$filename = "";
function getFilename1($filename) { // Получаем имя файла , через разбиение имени файла на массив и берем все что было до последней точки
    $explodename = explode("/",$filename);
    return end($explodename);
}
function getExtension1($filename) { // Получаем расширение файла , через разбиение имени файла на массив и берем все что было до последней точки
    $explodext = explode(".",$filename);
    return end($explodext);
}

@$id = $id+1-1;
if ($id<1) exitr("ошибка доступа");



$link = opendb();				                    	// Открываем базу данных
$nav_id=PrintNaviBar();                                 // Принтим навбар в массив вида: car_id-subcat_id-id
//echo $nav_id;                                         // Данные навбара
$nav_idarray = (explode("-",$nav_id));          // Разбиваем массив на составляющие
//echo "<pre>", print_r ($nav_idarray), "</pre>";       // принтим массив разбивая на машину, категорию субкат .





if ($action=="subcatedit") {
	$query = "UPDATE  `cat_subcat` SET
                `name` = '$catname'
               , `imgurl` = '$imgurl'
               , `meta` = '$meta'
               , `description` = '$description'
               , `published` = '$publish'
              WHERE `id` = $id;";
	echo $query . "<br>\r\n";
	echo "Саб-Категория возможно изменена<br>\r\n";
	mysqli_query($link, $query);		// Выполняем запрос
}






$current_subcat	=	assoc_query($link,"cat_subcat","*",$id);
$current_cat	=	assoc_query($link,"auto_category","*", $current_subcat["category_id"]);
$current_auto	=	assoc_query($link,"auto","*",$current_cat["auto_id"]);
//printArr($current_subcat); //показываем массив
//printArr($current_cat); //показываем массив
//printArr($current_auto); //показываем массив

/************************************START      IMAGES      UPLOAD****************************************/


//echo '<form name="add_img" method="post" action="edit_cat.php" enctype="multipart/form-data>\r\n'.
//	"<input type=\"hidden\" name=\"id\" value=\"".$current_subcat["category_id"]."\">\r\n".
//	"<input type=\"hidden\" name=\"subcat_id\" value=\"$id\">\r\n".
//	'<input type="file" name="picfilename" value="Добавить изображение" accept="image/*,image/jpeg,image/png">'.'<input type="submit" value="Сохранить изображение">'.
//	'<input type="hidden" name="action" value="add_img_url">'."\r\n".
//	"</form>\r\n";
//echo "ДОКУМЕНТ РУУТ : ".$_SERVER['DOCUMENT_ROOT'].$br;
//echo "СКРИПТ ФАЙЛНЕЙМ : ".$_SERVER['SCRIPT_FILENAME'].$br;
//echo "РЕКВЕСТ УРЛ : ".$_SERVER['REQUEST_URI'].$br;
//echo "СКРИПТ НЕЙМ : ".$_SERVER['SCRIPT_NAME'].$br;
//
//$scriptname = substr($_SERVER['SCRIPT_NAME'], (strrpos($_SERVER['SCRIPT_NAME'],"/", -1)+1));
//$strrpos = (strrpos($_SERVER['SCRIPT_NAME'],"/", -0)+1);
//$strlen = strlen($_SERVER['SCRIPT_NAME']);
//$strspn = strspn($_SERVER['SCRIPT_NAME'],$scriptname,"0");
//$path = substr($_SERVER['SCRIPT_NAME'],"0","$strrpos");
//
//echo "PATH : ".$path.$br;
//echo  "STRPOS : ".$strrpos.$br;
//echo "ROMA : ".$scriptname.$br;
//echo $strlen.$br;
//echo $strspn.$br;


if (!$_FILES)
{
	echo '
                  <form action="" method="post" enctype="multipart/form-data">
                  <input type="file" name="picfilename" accept="image/*,image/jpeg,image/png"><input type="submit" value="Загрузить"><br>
                  </form>
                    
         ';

}
else
{
	if(is_uploaded_file($_FILES["picfilename"]["tmp_name"]))
	{
		$dir = $img_directory."$nav_idarray[0]".DIRECTORY_SEPARATOR."$nav_idarray[1]".DIRECTORY_SEPARATOR;
//		echo "Временный файл создан и сохранен в :".$_FILES["picfilename"]["tmp_name"];  //Принтит данные временного файла
//		echo "<pre>", print_r($_FILES), "</pre>"; // Печатаем данные для визуализации массива данных файла.
		$extension = getExtension1($_FILES["picfilename"]["name"]); // Получаем расширение файла через собст функцию
		$filename = getFilename1($_FILES["picfilename"]["name"]); // Получаем имя файла через собст функцию
		$destinationfile = $img_directory.$nav_idarray[0].DIRECTORY_SEPARATOR.$nav_idarray[1].DIRECTORY_SEPARATOR.$id.".".$extension; //Путь и имя сохраняемого файла.

//            $destinationfile =  __DIR__.DIRECTORY_SEPARATOR."images"."$subcat_id".DIRECTORY_SEPARATOR."$nav_idarray[0]".DIRECTORY_SEPARATOR."$nav_idarray[1]".DIRECTORY_SEPARATOR."$id"."."."$extension";
//$destinationfile =  __DIR__.DIRECTORY_SEPARATOR."images"."$subcat_id".DIRECTORY_SEPARATOR."$nav_idarray[0]".DIRECTORY_SEPARATOR."$nav_idarray[1]".DIRECTORY_SEPARATOR."$id"."."."$extension";
//            $dir =  __DIR__.DIRECTORY_SEPARATOR."images"."$subcat_id".DIRECTORY_SEPARATOR."$nav_idarray[0]".DIRECTORY_SEPARATOR."$nav_idarray[1]".DIRECTORY_SEPARATOR;
//$dir =  __DIR__.DIRECTORY_SEPARATOR."images"."$subcat_id".DIRECTORY_SEPARATOR."$nav_idarray[0]".DIRECTORY_SEPARATOR."$nav_idarray[1]".DIRECTORY_SEPARATOR;

        echo "SUBCAT ID $ id =".$id." id полученная из Массива nav_idarray[2]:=".$nav_idarray[2].$br;
		echo "FILENAME : ".$filename.$br;
		echo "FILE EXTENSION : " . $extension . $br;
		echo "VAR ( __FILE__ ) : " . __FILE__ . $br;
		echo __DIR__. $br;
		echo $dir. $br;
		echo $destinationfile.$br;

		if (!file_exists($dir))
		{
			echo "Директоии нет".$br;
			if (!mkdir($dir,0777,true)) echo "Директория не создана".$br; else echo "Директория создана в: ".$dir.$br;
		}
		else echo "Директория существует, создавать не надо".$br;
		if (file_exists($destinationfile))
		{ echo "Файл существует. Заменяем существующий файл...".$br;
			if (move_uploaded_file($_FILES["picfilename"]["tmp_name"], "$destinationfile")) echo "Файл изменен" .$br; else echo "Ошибка замены файла".$br;
		}
		else
		{
			echo "Файл не существует. Создаем файл...".$br;
			if (move_uploaded_file($_FILES["picfilename"]["tmp_name"], "$destinationfile")) echo "Создан файл:".$destinationfile.$br; else echo "Файл не добавлен" .$br;
		}
	}  else echo "ОШИБКА ЗАГРУЗКИ";

}

/************************************  END     IMAGES     UPLOAD****************************************/

$sizeurl= "";
$img    = $current_subcat["imgurl"];
$sizeurl=" width=\"200\" height=\"200\" ";
echo "<img src=\"image.php?id=".$nav_id."\"$sizeurl/>";

$checked="";
if ($current_subcat["published"]=="1"){ $checked="checked"; }

echo "<form name=\"form\" method=\"get\" action=\"\">\r\n".
	'<table border="0">'."\r\n".
	'    <tr>'."\r\n".
	'      <td>название саб-категории</td>'."\r\n".
	'      <td><input type="text" name="catname" size="59"  value="'.$current_subcat["name"].'" ><input type="checkbox" name="publish" '.$checked.'>Опубликовать</td>'."\r\n".
	'    </tr>'."\r\n".
	'    <tr>'."\r\n".
	'      <td>ссылка на картинку</td>'."\r\n".
	'      <td><input type="text" name="imgurl" size="59"  value="'.$current_subcat["imgurl"].'" > <input type="file" name="add_img_url" size="1"  value="Добавить изображение" >
                                                                                                   <input type="hidden" name="add_img_url" value="Добавить изображение" ></td>'."\r\n".
//	'      <td><input type="file" name="add_img_url" size="1"  value="Добавить изображение" ></td>'."\r\n". // Надо доделать эту кнопку
	'    </tr>'."\r\n".
	'    <tr>'."\r\n".
	'      <td>meta</td>'."\r\n".
	'      <td><input type="text" name="meta" size="59"  value="'.$current_subcat["meta"].'" ></td>'."\r\n".
	'    </tr>'."\r\n".
	'    <tr>'."\r\n".
	'      <td>description</td>'."\r\n".
	'      <td><input type="text" name="description" size="59"  value="'.$current_subcat["description"].'" ></td>'."\r\n".
	'    </tr>'."\r\n".
	'    <tr>'."\r\n".
//	'      <td>Опубликовать</td>'."\r\n".
//	'      <td><input type="checkbox" name="publish" '.$checked.'></td>'."\r\n".
	'    </tr>'."\r\n".
	'</table>'."\r\n".
	"<input type=\"hidden\" name=\"action\" value=\"subcatedit\">\r\n".
	"<input type=\"hidden\" name=\"id\" value=\"$id\">\r\n".
	'<input type="submit" value="Сохранить изменения">'."\r\n".
	"</form>\r\n";



/********************************          START УДАЛЕНИЕ САБКАТЕГОРИИ               *********************************/


echo "<form name=\"form1\" method=\"get\" action=\"edit_cat.php\">\r\n".
	"<input type=\"hidden\" name=\"action\" value=\"delsubcat\">\r\n".
	"<input type=\"hidden\" name=\"id\" value=\"".$current_subcat["category_id"]."\">\r\n".
	"<input type=\"hidden\" name=\"subcat_id\" value=\"$id\">\r\n".
	'    <div class="btn-group">'.
	'       <span class="btn btn-switcher">Удалить саб-категорию к хуям</span>'.
	'        <div class="inputs-group hidden">'.
	'            <input type="submit" value="Удалить саб-категорию к хуям">'.
	'        </div>'.
	'    </div>'."\r\n".
	"</form>\r\n";


/********************************          END УДАЛЕНИЕ САБКАТЕГОРИИ               *********************************/

?>


<?php //**********************************START КОД ЛИСТИНГА СПИСКА ЗАПЧАСТЕЙ ****************************************/
/***************************START ПРОЦЕДУРА ДОБАВЛЕНИЯ НОВОЙ СТРОКИ И УДАЛЕНИЯ СУЩЕСТВУЮЩЕЙ **************************/
$query = "select MAX(`NO`) from 1parts_list where subcat_id ='$id';"; // Переменная запроса MySQL на вычисление максимального значения в колонке NO в строке
$result = mysqli_query($link, $query);	// Выполняем запрос и кладем ответ в переменную $result
$nn = mysqli_fetch_row($result);
echo $nn=$nn[0]+1;


$insert = "INSERT INTO `1parts_list` (`subcat_id`, `NO`, `part_name`, `part_num`, `search_url`, `meta`, `description`) VALUES ('$id','$nn','$part_name','$part_num','$search_url','$meta','$description');"; //Переменная содержащая строку запроса MySQL
$del = "DELETE FROM `1parts_list` WHERE `1parts_list`.`id` = $part_id";
//$update_line = "UPDATE `1parts_list` SET (`NO`, `part_name`, `part_num`, `search_url`, `meta`, `description`) VALUES ('$NO','$part_name','$part_num','$search_url','$meta','$description') WHERE `1parts_list`.`id` = $part_id";
$update_line = "UPDATE `1parts_list` SET `NO` = '$NO', `part_name` = '$part_name', `part_num` = '$part_num', `search_url` = '$search_url', `meta` = '$meta', `description` = '$description' WHERE `1parts_list`.`id` = $part_id";


echo $br;

switch ($action) {
	case "addnewline":
        		echo "ADD NEW LINE" . $br;
              echo "Печатаем строку запроса добавления новой строки MySQL: " . $insert.$br;
		mysqli_query($link, $insert); // Запрос к таблице на добавление новой строки
		break;

	case "del_line":
		mysqli_query($link, $del); // Запрос к таблице на удаление строки с нужным Id (part_id)
	    printArr($_POST);

	    echo "Печатаем строку запроса удаления строки в MySQL: " . $del.$br;
        echo "DELETED BY HIDDEN LINE ID" . $part_id.$br;
        echo "ACTION =".$action.", PART ID=".$part_id.$br;
		break;

	case "-":
		mysqli_query($link, $del); // Запрос к таблице на удаление строки с нужным Id (part_id)
	    printArr($_POST);

        echo "Печатаем строку запроса удаления строки в MySQL: " . $del.$br;
        echo "ACTION =".$action.", PART ID=".$part_id.$br;
		break;

	case "delete":
		mysqli_query($link, $del); // Запрос к таблице на удаление строки с нужным Id (part_id)
	    printArr($_POST);

        echo "Печатаем строку запроса удаления строки в MySQL: " . $del.$br;
        echo "ACTION =".$action.", PART ID=".$part_id.$br;
		break;

    case "update":
        mysqli_query($link, $update_line); // Запрос к таблице на удаление строки с нужным Id (part_id)
        printArr($_POST);

        echo "Печатаем строку запроса удаления строки в MySQL: " . $update_line.$br;
        echo "ACTION =".$action.", PART ID=".$part_id.$br;
        break;

    default:
        echo "ДАННЫХ ВРОДЕ НЕТ. Переменная ACTION =".$action;

}

/**************************END ПРОЦЕДУРА ДОБАВЛЕНИЯ НОВОЙ СТРОКИ И УДАЛЕНИЯ СУЩЕСТВУЮЩЕЙ ************************/

/******************************************START ЗАГОЛОВОК ТАБЛИЦЫ **********************************************/
echo "<form name=\"list\" method=\"post\" action=\"\">\r\n".

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


$query = "SELECT * FROM `1parts_list` WHERE subcat_id = $id;";


//echo $query . "<br>\r\n";
$result = mysqli_query($link, $query);	// Выполняем запрос и кладем ответ в переменную $result
$lines_q = mysqli_num_rows($result);  //Выдергиваем количество линий в ответе

for ($x = 1; $x <= $lines_q; $x++)
    {
$line = mysqli_fetch_row ($result);

//  onsubmit=\"return delForm( '$line[3]', '$line[4]')\"
//    onchange="return updForm()"

echo "<form id=\"555\" name=\"list\" onsubmit=\"return updForm('$line[4]','$line[3]','$action')\" method=\"post\" action=\"\">\r\n".

    '<input disabled type="text" name="part_id"             value="'.$line[0].'" size="1">'."\r\n".
    '<input disabled type="text" name="subcat_id"           value="'.$line[1].'" size="1">'."\r\n".
    '<input  type="text" name="NO"                  value="'.$line[2].'" size="1">'."\r\n".
    '<input  type="text" name="part_num"            value="'.$line[4].'" size="">'."\r\n".
    '<input  type="text" name="part_name"           value="'.$line[3].'" size="">'."\r\n".
    '<input  type="text" name="search_url"          value="'.$line[5].'" size="">'."\r\n".
    '<input  type="text" name="meta"                value="'.$line[6].'" size="10">'."\r\n".
    '<input  type="text" name="description"         value="'.$line[7].'" size="10">'."\r\n".

//	'<input          type="button" name="action"    onclick="document.getElementById(\'listform\').submit();"             value="-" >'."\r\n".
    '<input        type="submit" name="action"                 value="update" >'."\r\n".
    '<input        type="submit" name="action"  value="delete" >'."\r\n".
//	'<input          type="hidden" name="action"                 value="del_line">'."\r\n".
    '<input          type="hidden" name="part_id"                value="'.$line[0].'">'."\r\n".

    "</form>\r\n";
    }

//echo "PART ID POST: ".$part_id; // Потом надо удалить
/**********************************************END TABLE PRINTING *************************************************/

?>

<!--*********************** "ANL" is "Add New Line" *****************-->
<!--******************* http://www.softtime.ru/article/?id_article=96-->
<!--<p>Справка: <a href="http://www.softtime.ru/article/?id_article=96">"http://www.softtime.ru/article/?id_article=96"</a></p>-->
<!--<p>Справка по HTML and XHTML Techniques for WCAG 2.0: <a href="https://www.w3.org/TR/WCAG20-TECHS/html.html#H44">"https://www.w3.org/TR/WCAG20-TECHS/html.html#H44"</a></p>-->


<form action="" name="addnewline" method="post">
	<p>
		<input type="text" name="post_id" size="1">
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
