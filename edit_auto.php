<?php
include "lib.php";
/**********************************Start инициализируем передаваемые переменные****************************************/
$id = 0; $cat_id = 0; $action = ""; $category= ""; $catname  = "";
$imgurl   = ""; $publish  = ""; $engine   = ""; $meta     = "";

if (isset($_GET['action']		)) $action =		$_GET['action'];
if (isset($_GET['id']			)) $id =	    	$_GET['id'];
if (isset($_GET['cat_id']		)) $cat_id =    	$_GET['cat_id'];
if (isset($_GET['category']		)) $category =	    $_GET['category'];
if (isset($_GET['catname']	    )) $catname =		$_GET['catname'];
if (isset($_GET['imgurl']	    )) $imgurl  =		$_GET['imgurl'];
if (isset($_GET['publish']	    )) $publish =		$_GET['publish'];
if (isset($_GET['engine']	    )) $engine  =		$_GET['engine'];
if (isset($_GET['meta']		    )) $meta    =		$_GET['meta'];

@$id= (getRequest('id'));

/************************************End инициализируем передаваемые переменные****************************************/
@$id = $id+1-1;
if ($id<1) exitr("ошибка доступа");

include "header.html";
include "config.php";



$link = opendb();					//открываем базу данных

//$nav_id = PrintNaviBar();

if ($action=="addcatend") {
//echo "engineID=".$Engine_id."!<br>\r\n";
    $query = "INSERT INTO `auto_category` 
              (`auto_id`, `engine`, `meta`, `title`, `img`) 
              VALUES
              ('$id', '$engine', '$meta', '$catname', '$imgurl');";
    echo $query . "<br>\r\n";
    echo "Категория возможноо добавлена<br>\r\n";
mysqli_query($link, $query);		// Выполняем запрос
}

if ($action=="delcat") {
    $query = "DELETE FROM `auto_category` WHERE `auto_category`.`id` = $cat_id;";
    echo $query . "<br>\r\n";
    echo "Категория возможно удалена<br>\r\n";
    mysqli_query($link, $query);		// Выполняем запрос
}

$row		=	assoc_query($link,"auto","brand_id, model_id, engine_id, is_published",$id);						//printArr($row); //показываем массив
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

// printArr($row);
    echo "<table style=\"float: left;\" width=\"200\" border=\"1\">\r\n    <tr>\r\n      <td height=\"200\"><a href=\"edit_cat.php?id=".$row["id"]."\">
<img src='image.php?id=".$id."-".$row["id"]."'></a></td>\r\n    </tr>\r\n    <tr>\r\n      <td align=\"center\"><a href=\"edit_cat.php?id=".$row["id"]."\">".$row["title"]."</a></td>\r\n    </tr>\r\n</table>";



}
echo "<br>\r\n<hr style=\"clear: left;\"><br>\r\n";



if ($action=="addcat") {
    echo "<form name=\"form1\" method=\"get\" action=\"\">\r\n";
    echo '<table border="1">'."\r\n";
    echo '    <tr>'."\r\n";
    echo '      <td>название категории</td>'."\r\n";
    echo '      <td><input type="text" name="catname"></td>'."\r\n";
    echo '    </tr>'."\r\n";
    echo '    <tr>'."\r\n";
    echo '      <td>ссылка на картинку</td>'."\r\n";
    echo '      <td><input type="text" name="imgurl"></td>'."\r\n";
    echo '    </tr>'."\r\n";
    echo '    <tr>'."\r\n";
    echo '      <td>мотор</td>'."\r\n";
    echo '      <td><input type="text" name="engine"></td>'."\r\n";
    echo '    </tr>'."\r\n";
    echo '    <tr>'."\r\n";
    echo '      <td>meta</td>'."\r\n";
    echo '      <td><input type="text" name="meta"></td>'."\r\n";
    echo '    </tr>'."\r\n";
    echo '</table>'."\r\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"addcatend\">";
    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
    echo '<input type="submit" value="Добавить">'."\r\n";
echo "</form>";
}



if ($action!="addcat") echo "<br><a href=\"?id=$id&action=addcat\">Добавить категорию</a>";

?>


<!--
<form name="form1" method="get" action="?action=addcatend">
  Добавить категорию:
	<input type="text" name="category">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="submit" value="Добавить">
</form> -->
