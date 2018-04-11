<?php
include "lib.php";
/**********************************Start инициализируем передаваемые переменные****************************************/
$id = 0; $action = ""; $category= ""; $catname  = "";
$imgurl   = ""; $publish  = 0; $engine   = ""; $meta     = "";
$subcat_id= "";

if (isset($_GET['action']		)) $action =		$_GET['action'];
if (isset($_GET['id']			)) $id =			$_GET['id'];
if (isset($_GET['category']		)) $category =		$_GET['category'];
if (isset($_GET['catname']		)) $catname =		$_GET['catname'];
if (isset($_GET['imgurl']		)) $imgurl  =		$_GET['imgurl'];
if (isset($_GET['publish']		)) $publish =		1;
if (isset($_GET['engine']		)) $engine  =		$_GET['engine'];
if (isset($_GET['meta']			)) $meta    =		$_GET['meta'];
if (isset($_GET['subcat_id']	)) $subcat_id = 	$_GET['subcat_id'];

/************************************End инициализируем передаваемые переменные****************************************/
@$id = $id+1-1;
if ($id<1) exitr("ошибка доступа");

include "header.html";
include "config.php";

$link = opendb();					//открываем базу данных

$nav_id = PrintNaviBar();
//echo "nav_id=".$nav_id.$br;
if ($action=="addcatend") {
	$query = "UPDATE  `auto_category` SET 
                 `engine` = '$engine' 
               , `meta` = '$meta'
               , `title` = '$catname'
               , `img` =  '$imgurl'
               , `published` =  '$publish'
              WHERE `id` = $id;";
//	echo $query . "<br>\r\n";
    echo "Категория возможно изменена<br>\r\n";
    mysqli_query($link, $query);		// Выполняем запрос
}
if ($action=="addsubcatend") {
	$query = "INSERT INTO `cat_subcat` ".
		"(`category_id`, `name`, `imgurl`) ".
		"VALUES ('$id', '$catname', '$imgurl');";
	echo $query."<br>\r\n";
	mysqli_query($link, $query);		// Выполняем запрос
	echo "<a href=\"".$_SERVER['SCRIPT_NAME']."?id=$id\">Саб-категория возможно добавлена</a><br>\r\n";
}
if ($action=="delsubcat") {
	$query = "DELETE FROM `cat_subcat` WHERE `id` = $subcat_id;";
	echo $query .$br;
	echo "Саб-Категория возможно удалена".$br;
	mysqli_query($link, $query);		// Выполняем запрос
}

$current_cat	=	assoc_query($link,"auto_category","auto_id, engine, meta, title, img, published",$id);		//		printArr($current_cat); //показываем массив
$current_auto	=	assoc_query($link,"auto","brand_id, model_id, engine_id",$current_cat["auto_id"]);			//		printArr($current_auto); //показываем массив

$Brand_id		=	basic_query($link,"auto_brand","title",$current_auto["brand_id"]);
$Model_id		=	basic_query($link,"auto_model","title",$current_auto["model_id"]);
$Engine_id		=	basic_query($link,"auto_engine","title",$current_auto["engine_id"]);

//echo $Brand_id." / ".$Model_id." / ".$Engine_id."<br>\r\n";

$checked="";
if ($current_cat["published"]=="1"){ $checked="checked"; }
/**********************************Start Таблица редактирования текущей категории**************************************/
echo '<img src="image.php?id='.$nav_id.'" width="200" height="200">';
echo "<form name=\"form\" method=\"get\" action=\"\">\r\n".
    '<table border="1">'."\r\n".
    '    <tr>'."\r\n".
    '      <td>название категории</td>'."\r\n".
    '      <td><input type="text" name="catname" size="70"  value="'.$current_cat["title"].'" ></td>'."\r\n".
    '    </tr>'."\r\n".
    '    <tr>'."\r\n".
    '      <td>ссылка на картинку</td>'."\r\n".
    '      <td><input type="text" name="imgurl" size="70"  value="'.$current_cat["img"].'" ></td>'."\r\n".
    '    </tr>'."\r\n".
    '    <tr>'."\r\n".
    '      <td>мотор</td>'."\r\n".
    '      <td><input type="text" name="engine" size="70"  value="'.$current_cat["engine"].'" ></td>'."\r\n".
    '    </tr>'."\r\n".
    '    <tr>'."\r\n".
    '      <td>meta</td>'."\r\n".
    '      <td><input type="text" name="meta" size="70"  value="'.$current_cat["meta"].'" ></td>'."\r\n".
    '    </tr>'."\r\n".
    '    <tr>'."\r\n".
    '      <td>Опубликовать</td>'."\r\n".
    '      <td><input type="checkbox" name="publish" '.$checked.'></td>'."\r\n".
    '    </tr>'."\r\n".
    '</table>'."\r\n".
    "<input type=\"hidden\" name=\"action\" value=\"addcatend\">\r\n".
    "<input type=\"hidden\" name=\"id\" value=\"$id\">\r\n".
    '<input type="submit" value="Изменить">'."\r\n".
    "</form>\r\n";

echo "<form name=\"form1\" method=\"get\" style=\"float: left;\" action=\"edit_auto.php\">\r\n".
	"<input type=\"hidden\" name=\"action\" value=\"delcat\">\r\n".
	"<input type=\"hidden\" name=\"id\" value=\"".$current_cat["auto_id"]."\">\r\n".
	"<input type=\"hidden\" name=\"cat_id\" value=\"$id\">\r\n".
	'    <div class="btn-group">'.
	'       <span class="btn btn-switcher">Удалить категорию к хуям</span>'.
	'        <div class="inputs-group hidden">'.
	'            <input type="submit" value="Удалить категорию к хуям">'.
	'        </div>'.
	'    </div>'."\r\n".
    "</form>\r\n";
/************************************End Таблица редактирования текущей категории**************************************/
echo "<br>\r\n<hr style=\"clear: left;\">\r\n";

if ($action!="add_subcat") echo "<a href=\"?id=$id&action=add_subcat\">Добавить субкатегорию</a><br>\r\n";
	else {
	echo"<form name=\"formsubcat\" method=\"get\" action=\"\">\r\n".
		"<input type=\"hidden\" name=\"id\" value=\"$id\">\r\n".
		'Добавляем сабкатегорию:'."\r\n".
		'<table border="1">'."\r\n".
		'    <tr>'."\r\n".
		'      <td>название субкатегории</td>'."\r\n".
		'      <td><input type="text" name="catname" size="70"  value="" ></td>'."\r\n".
		'    </tr>'."\r\n".
		'    <tr>'."\r\n".
		'      <td>ссылка на картинку</td>'."\r\n".
		'      <td><input type="text" name="imgurl" size="70"  value=""  placeholder="если уж нет, то можно не указывать пока"></td>'."\r\n".
		'    </tr>'."\r\n".
		'</table>'."\r\n".
		"<input type=\"hidden\" name=\"action\" value=\"addsubcatend\">\r\n".
		'<input type="submit" value="Добавить суб-категорию">'."\r\n".
		"</form>\r\n";
/*  files/auto/catalogs/NoImage.png */
}







$query = "SELECT * FROM `cat_subcat` WHERE `category_id` = ".$id.";";
//echo $query . "<br>\r\n";
$result = mysqli_query($link, $query);		// Выполняем запрос
$resnum  = mysqli_num_rows($result);		// Узнаём количество возвращённых строк
for ($c=0; $c<$resnum; $c++) {				// Перебираем результаты выборки
	mysqli_data_seek($result, $c);			// Переход к строке №...
	$row = mysqli_fetch_assoc($result);		// Получение строки с индексом в виде имени столбца

//	printArr($row);
	$sizeurl="";
	$sizeurl=" width=\"200\" height=\"200\" ";
	echo "<table style=\"float: left;\" width=\"200\" border=\"1\">\r\n    <tr>\r\n      <td height=\"300\"><a href=\"edit_subcat.php?id=".$row["id"]."\">
<img src='image.php?id=".$nav_id."-".$row["id"]."'$sizeurl></a></td>\r\n    </tr>\r\n    <tr>\r\n      <td align=\"center\"><a href=\"edit_subcat.php?id=".$row["id"]."\">".$row["name"]."</a></td>\r\n    </tr>\r\n</table>";

}



/*
$row		=	assoc_query($link,"auto","brand_id, model_id, engine_id, is_published",$id);						printArr($row); //показываем массив
$Brand_id	=	basic_query($link,"auto_brand","title",$row["brand_id"]);
$Model_id	=	basic_query($link,"auto_model","title",$row["model_id"]);
$Engine_id	=	basic_query($link,"auto_engine","title",$row["engine_id"]);
//if ($row["is_published"] == 1) $text_published = "Показывается на сайте"; else $text_published = "Не показывается на сайте";
echo $Brand_id." / ".$Model_id." / ".$Engine_id."<br>\r\n";


$query = "SELECT * FROM `auto_category` WHERE `auto_id` = ".$id.";";
//echo $query . "<br>\r\n";
$result = mysqli_query($link, $query);		// Выполняем запрос
$resnum  = mysqli_num_rows($result);		// Узнаём количество возвращённых строк
for ($c=0; $c<$resnum; $c++) {				// Перебираем результаты выборки
    mysqli_data_seek($result, $c);			// Переход к строке №...
    $row = mysqli_fetch_assoc($result);		// Получение строки с индексом в виде имени столбца

    printArr($row);
    echo "<table style=\"float: left;\" width=\"200\" border=\"1\">\r\n    <tr>\r\n      <td height=\"200\"><a href=\"edit_cat.php?id=".$row["id"]."\">
<img src='".$row["img"]."'></a></td>\r\n    </tr>\r\n    <tr>\r\n      <td align=\"center\"><a href=\"edit_cat.php?id=".$row["id"]."\">".$row["title"]."</a></td>\r\n    </tr>\r\n</table>";



}
echo "<br>\r\n<hr style=\"clear: left;\"><br>\r\n";




}
if ($action=="addcatend") {
//echo "engineID=".$Engine_id."!<br>\r\n";
    $query = "INSERT INTO `auto_category`
              (`auto_id`, `engine`, `meta`, `title`, `img`)
              VALUES
              ('$id', '$Engine_id', '$meta', '$catname', '$imgurl');";

    echo $query . "<br>\r\n";
    echo "Категория возможно добавлена<br>\r\n";
    mysqli_query($link, $query);		// Выполняем запрос



}

*/
?>