
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "header.html";
include "config.php";
include "lib.php";
function printAutoList($Arr) {
    echo "\r\n<ul>\r\n";
    while (list ($key1, $val1) = each ($Arr)) {
        echo "  <li>".$key1."</li>\r\n";                                        //печатаем брэнд
        echo "    <ul>\r\n";
        while (list ($key2, $val2) = each ($Arr[$key1])) {
            echo "      <li>".$key2."</li>\r\n";                                //печатаем модель
            echo "        <ul>\r\n";
            while (list ($key3, $val3) = each ($Arr[$key1][$key2])) {
                echo "          <li><a href='edit_auto.php?id=".$key3."'>".$val3."</a></li>\r\n";                        //печатаем мотор
            }
            echo "      </ul>\r\n";
        }
        echo "    </ul>\r\n";
    }
}
/********************************************************************************************/
/********************************************************************************************/
/********************************************************************************************/

$link = opendb();					//открываем базу данных



$query="SELECT * FROM `auto`";		//делаем полный запрос данных из таблицы ауто
$result=mysqli_query($link, $query);			//выполняем запрос
$resnum = mysqli_num_rows($result);			// узнаём сколько строк в ответе
for ($c=0; $c<$resnum; $c++) {                                          //начинаем разбирать ответ из мускуля в массив
	mysqli_data_seek($result, $c);                                      /* Переход к строке №... */
	$row = mysqli_fetch_row($result);                                   /* Получение строки */

	$Brand_id		=	basic_query($link,"auto_brand", "title",$row[1]);
	$Model_id		=	basic_query($link,"auto_model", "title",$row[2]);
	$Engine_id		=	basic_query($link,"auto_engine","title",$row[3]);

	$auto[$Brand_id][$Model_id][$row[0]] = $Engine_id;
}


ksort($auto, SORT_STRING);
while (list ($key, $val) = each ($auto) ) {
	ksort($auto[$key], SORT_STRING);
	while (list ($key1, $val1) = each ($auto[$key]) ) {
		asort($auto[$key][$key1], SORT_STRING);
	}}

function printMenu ($arr) {
	reset($arr);
	while (list ($key, $val) = each ($arr)) {
		$firstLetterBrand = substr($key, 0, 1);
		$menuArr[$firstLetterBrand][$key]=$val;
	}
	echo '<nav class="dws-menu">'."\r\n";
	echo '<ul class="nav">'."\r\n";
	while (list ($key, $val) = each ($menuArr)) {						// самый верх, просто буквы
		if ($key=="_") break;
		echo "  <li><a href=\"#\">$key....</a>"."\r\n";
		echo "    <ul>"."\r\n";
		while (list($key1, $val1) = each($val)) {					// тут печатаем бренды
			echo "      <li><a href=\"#\"><span>$key1</span></a>"."\r\n";
			echo "        <ul>"."\r\n";
			while (list($key2, $val2) = each($val1)) {				// тут печатаем модели
				echo "          <li><a href=\"#\">$key2</a>"."\r\n";
				echo "            <ul>"."\r\n";
				while (list($key3, $val3) = each($val2)) {			// тут печатаем моторы
					echo "              <li><a href=\"edit_auto.php?id=$key3\">$val3</a></li>"."\r\n";
				}
				echo "            </ul>"."\r\n";
			}
			echo "        </ul>"."\r\n";
			echo "      </li>"."\r\n";
		}
		echo "    </ul>"."\r\n";
		echo "  </li>"."\r\n";
	}
	echo '</nav>'."\r\n";
}


printMenu($auto);

printAutoList($auto);

?>

