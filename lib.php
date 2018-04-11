<?php

function getRequest($key, $default = NULL) {
    return array_key_exists($key, $_REQUEST) ? $_REQUEST[$key] : $default;
}

    function PrintNaviBar() {
        global $id, $link;
        $scriptname = substr($_SERVER['SCRIPT_NAME'], (strrpos($_SERVER['SCRIPT_NAME'],"/", -1)+1));
        if ($scriptname=="edit_auto.php") $vlogennost = 1;	elseif ($scriptname=="edit_cat.php") $vlogennost = 2;	elseif ($scriptname=="edit_subcat.php") $vlogennost = 3;	else $vlogennost = 0;
    /*
        echo "vlogennost=".$vlogennost." <br>\r\n ";
        echo "scriptname=".$scriptname." <br>\r\n ";
    */
        if ($vlogennost>2) {
            $subcategory_name = basic_query($link,"cat_subcat","name",$id);
	        $category_id = basic_query($link,"cat_subcat","category_id",$id);
	        $ret = $category_id."-".$id;
        } else {
            $category_id = $id;
            $ret = $category_id;
        }

        $auto_id = basic_query($link,"auto_category","auto_id",$category_id);
        $category_name = basic_query($link,"auto_category","title",$category_id);

        $ret = $auto_id."-".$ret;


//	    echo "subcategory_name=".$subcategory_name."<br>\r\n" ;
//	    echo "category_id=".$category_id."<br>\r\n" ;
//	    echo "auto_id=".$auto_id."<br>\r\n" ;
//	    echo "category_name=".$category_name ."<br>\r\n" ;


        $current_auto	=	assoc_query($link,"auto","brand_id, model_id, engine_id",$auto_id);			//		printArr($current_auto); //показываем массив
        $Brand_id		=	basic_query($link,"auto_brand","title",$current_auto["brand_id"]);	$Model_id		=	basic_query($link,"auto_model","title",$current_auto["model_id"]);	$Engine_id		=	basic_query($link,"auto_engine","title",$current_auto["engine_id"]);

        echo "<a href=\"edit_auto.php?id=$auto_id\">".$Brand_id." / ".$Model_id." / ".$Engine_id."</a>";
        if ($vlogennost>2){
            echo " &#8658; <a href=\"edit_cat.php?id=$category_id\">".$category_name."</a>";
            echo " &#8658; ".$subcategory_name."<br>\r\n";
        } else	echo " &#8658; ".$category_name;
        echo "<br>\r\n";

        return $ret;
    }


	// возвращает заданный $row в формате строки
	function basic_query($link, $table, $row, $id) 	{
			$query1 = "SELECT `".$row."` FROM `".$table."` WHERE `id` = ".$id.";";
//          echo $query1 . "<br>\r\n";
			$result1 = mysqli_query($link, $query1);    // Выполняем запрос
			$row1 = mysqli_fetch_row($result1);         // Получение строки
		return $row1[0];
	}

	// возвращает заданный $row в формате assoc массива
	function assoc_query($link, $table, $row, $id)	{
			$query1 = "SELECT ".$row." FROM `".$table."` WHERE `id` = ".$id.";";
//          echo $query1 . "<br>\r\n";
			$result1 = mysqli_query($link, $query1);	// Выполняем запрос
			$row1 = mysqli_fetch_assoc($result1);		// Получение строки
		return $row1;
	}



	// возвращает список баз в аррее
	function fill_bases($link, $database = "test") {	
		if(!$link) exitr("не задан линк соединения с базой"); 
		$query="SHOW TABLE STATUS";// FROM `$database`";
		//echo "query = ".$query."<br>\r\n";
		$a = mysqli_query($link, $query);
		while ($b = mysqli_fetch_array($a, MYSQLI_ASSOC)) $DataBD[]=$b;
	return $DataBD;
	}


	function print_filledbases($DataBD){ $NumBases = count($DataBD);	// COUNT_RECURSIVE
		for ($a=0; $a<$NumBases; $a++) {
			if ($DataBD[$a]["Comment"]=="") $DataBD[$a]["Comment"] = $DataBD[$a]["Name"];
			print "	  <option value='".$DataBD[$a]["Name"]."' ".$bases[($a)].">".$DataBD[$a]["Comment"]." (".$DataBD[$a]["Rows"].")</option>\r\n";
		}	//	$DataBD[$a]["Name"]	$DataBD[$a]["Rows"]	$DataBD[$a]["Update_time"]	$DataBD[$a]["Comment"]
	}
	
	
	function num_format($num){
		return number_format($num,0,',','&nbsp;');
	}
	
	function query_parse($query){			//Предварительная обработка запроса
		$regexp=preg_quote($query);			//Преобразование к регулярному выражению
		$regexp=str_replace("\\\\\"","",$regexp);
		$regexp=str_replace("/","",$regexp);
		$regexp=str_replace("\\*",".*",$regexp);
		$regexp=str_replace(" ",".*",$regexp);
		while (substr($regexp,0,2)==".*")	//Удаление незначащих * и пробелов в начале и конце запроса
			$regexp=substr($regexp,2);
		while (substr($regexp,strlen($regexp)-2)==".*")
			$regexp=substr($regexp,0,strlen($regexp)-2);
	return "/".$regexp."/ui";
	}
	
	function query_parse_for_search($query){			//Предварительная обработка запроса
		$regexp=$query;			//Преобразование к регулярному выражению
		$regexp=str_replace("\\\\\"","",$regexp);
		$regexp=str_replace("/","",$regexp);
		$regexp=str_replace("\\*","%",$regexp);
		$regexp=str_replace("*","%",$regexp);
		$regexp=str_replace(" ","%",$regexp);
		while (substr($regexp,0,2)=="%")	//Удаление незначащих * и пробелов в начале и конце запроса
			$regexp=substr($regexp,2);
		while (substr($regexp,strlen($regexp)-2)=="%")
			$regexp=substr($regexp,0,strlen($regexp)-2);
		return $regexp;
	}

	function name_highlight($str,$matches){	//Выделение цветом совпавшей части
		$pos=$matches[0][1];
		$matchlen=strlen($matches[0][0]);
		return substr($str,0,$pos)."<font color='green'>".$matches[0][0]."</font>".substr($str,$pos+$matchlen);
	}



	// выход и написание причины выхода
	function exitr($message = "") {
		echo "<a><font color=red>".$message."</a><br>";
		//include "footer.htm"; 
		exit;
		}

        function printArr($Arr) {
                echo '<table border=1>';
                while (list ($key, $val) = each ($Arr)) {
                        echo "<tr><td>".$key."</td>\r\n<td>";
                        if (is_array($val)) {
                                printArr($val);
                        } else {
                                echo $val;
                        }
                        echo "</td></tr>\r\n";
                }
                echo "</table>\r\n";
        }
        function format($Num, $Prec) {
                $prec = 2;
                $denum = 1048576;
                if ($Prec == "b") {
                        $prec = 0;
                        $denum = 1;
                }
                if ($Prec == "kb") {
                        $prec = 1;
                        $denum = 1024;
                }
                return number_format($Num/$denum, $prec, '.', '');
        }
        function timestamp($a=-1) {          //создание штампа из даты типа ДД.ММ.ГГГГ ЧЧ:ММ:СС
                if ($a==-1) { $a=time(); return $a; }
                  $day=substr($a,0,2);
                  $month=substr($a,3,2);
                  $year=substr($a,6,4);
                  $hour=substr($a,11,2);
                  $minutes=substr($a,14,2);
                  $sec=substr($a,17,2);      // Четверг, 1 января 1970 03:00:00
                  $stamp=mktime ($hour, $minutes, $sec, $month, $day, $year); //[, int is_dst]
                return $stamp;
        }
        //создание даты из таймштампа
        function ftime($a=-1) {
                if ($a==-1) $a=time();
                        $weekday = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб");
//                        $weekday = array("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
                        $months = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

                list($wday,$mday,$month,$year,$hour,$minutes,$second) = preg_split("( )",date("w j n Y H i s",$a));
                $out = "$weekday[$wday], $mday ".$months[$month-1]." $year $hour:$minutes:$second";
                return $out;
        }
        //создание маленькой даты из таймштампа
        function smalltime($a=-1) {
                if ($a==-1) $a=time();
                        $months = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

                list($wday,$mday,$month,$year,$hour,$minutes,$second) = preg_split("( )",date("w j n Y H i s",$a));
                $out = "$mday ".$months[$month-1]." $year";
                return $out;
        }
        // Получение картинки по идентифекатору юзера
        function getimguser($id, $sex) {
           $filetarget=md5($id).".jpg";
//           echo $filetarget;
           $directory="img/"; 
           $files=$directory.$filetarget;
           if (file_exists($files)) { $out=$directory.$filetarget; }
                               else { $out=$directory.$sex."_no_img.jpg"; }
           return ($out);
        }
        // Получение ФИО по идентифекатору юзера
	function getfiouser($user_id) {
           $query="SELECT * FROM `users` WHERE id = '$user_id'";
           $resultk=mysql_query ($query);
           $num_komp=mysql_num_rows($resultk);

           if ($num_komp==0) { $out="нет имени<br>"; } else {
            $row=mysql_fetch_array($resultk);
              $name1=($row["name1"]);
              $name2=($row["name2"]);
              $name3=($row["name3"]);
              $out=$name1." ".$name2." ".$name3;
             }
           return ($out);
        }
        // Получение ФИО по идентифекатору юзера
	function getsmallfiouser($user_id) {
           $query="SELECT * FROM `users` WHERE id = '$user_id'";
           $resultk=mysql_query ($query);
           $num_komp=mysql_num_rows($resultk);

           if ($num_komp==0) { $out="нет имени<br>"; } else {
            $row=mysql_fetch_array($resultk);
              $name1=($row["name1"]);
              $name2=($row["name2"]);
              $name3=($row["name3"]);
               $name2=substr($name2,0,1).".";
               $name3=substr($name3,0,1).".";
              $out=$name1." ".$name2." ".$name3;
             }
           return ($out);
        }
        // **********
        function normDate($dd) {
                list($dat, $tim) = split(" ", $dd);
                list($m,$d,$y) = split("/", $dat);
                list($h,$min,$s) = split(":", $tim);
                switch ($m) {
                        case 01: $mm = "января";   break;
                        case 02: $mm = "февраля";  break;
                        case 03: $mm = "марта";    break;
                        case 04: $mm = "апреля";   break;
                        case 05: $mm = "мая";      break;
                        case 06: $mm = "июня";     break;
                        case 07: $mm = "июля";     break;
                        case "08": $mm = "августа";  break;
                        case "09": $mm = "сентября"; break;
                        case "10": $mm = "октября";  break;
                        case "11": $mm = "ноября";   break;
                        case "12": $mm = "декабря";  break;
                        default: $mm = "мартобря";
                }
                $d = $d*1;
                $tim = $h.":".$min;
                $dat = $d." ".$mm." 20".$y." г., ".$tim;
                return $dat;
        }
        // Открытие мускуля и выбор базы из конфига
	function opendb($db="default") {
		include "config.php";								// $host $user $password $database
        	if ($db=="default") $dbase=$database; else $dbase=$db;				// проверка установлена ли база?
		$db = @mysqli_connect($host, $user, $password, $dbase);				// подключаемся к базе с параметрами из конфига
		if (!$db) {									// смотрим ошибку
        	    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL."<br>\r\n";
		    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL."<br>\r\n";
        	    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL."<br>\r\n";
		    exit;
        	}
        	if (!mysqli_set_charset($db, "utf8")) {
		    printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($db));
		    exit(); }
		return $db;
        }
        // распечатка всех входных данных
	function printall($db="default") {
		$a = $_REQUEST;
		foreach ($a as $key => $v) {
		   print "$key =  $v<br>\r\n";
		}
        }
	// Вычисление возраста по дате рождения
	function get_age($dob_stamp) {
		$dob = getdate($dob_stamp);
		$now = getdate(time());
		$age = $now['year'] - $dob['year'];
		$age-= (int)($now['mon'] < $dob['mon']);
		$age-= (int)(($now['mon'] == $dob['mon']) && ($now['mday'] < $dob['mday']));
		return $age;
	}
	// проверка даты на правильность.
	function isvaliddate($day, $month, $year) {
		$day = intval($day);
		$month = intval($month);
		$year = intval($year);
		$time = mktime(0,0,0,$month,$day,$year);
		if ($day != date("j",$time)) return false;
		if ($month != date("n",$time)) return false;
		if (($year != date("Y",$time)) AND ($year != date("y",$time))) return false;
		return true;
	}





    function date_difference($date_from,$date_to,$unit='y') {
/* 
Calculates difference from date_from to date_to, taking into account leap years 
if date_from > date_to, the number of days is returned negative 
date_from and date_to format is: "dd-mm-yyyy" 
It can calculate ANY date difference, for example between 21-04-345 and 11-11-3412 
This is possible by mapping any date to the "range 0" dates, as this table shows: 

   INI            END            RANGE    LEAP YEARS 
   ...            ...            ...        ... 
   01/01/1920    01/01/1939    -3        5 
   01/01/1940    01/01/1959    -2        5 
   01/01/1960    01/01/1979    -1        5 
   01/01/1980    01/01/1999    0        5    * this is the range used for calculations with mktime 
   01/01/2000    01/01/2019    1        5 
   01/01/2020    01/01/2039    2        5 
   01/01/2040    01/01/2059    3        5 
   01/01/2060    01/01/2079    4        5 
   ...            ...            ...        ... 

The difference is calculated in the unit specified by $unit (default is "days") 
$unit: 
   'd' or 'D' = days 
   'y' or 'Y' = years 
*/ 


   //get parts of the dates 
   $date_from_parts = explode('-', $date_from); 
   $date_to_parts = explode('-', $date_to); 
   $day_from = $date_from_parts[0]; 
   $mon_from = $date_from_parts[1]; 
   $year_from = $date_from_parts[2]; 
   $day_to = $date_to_parts[0]; 
   $mon_to = $date_to_parts[1]; 
   $year_to = $date_to_parts[2]; 

   //if date_from is newer than date to, invert dates 
   $sign=1; 
   if ($year_from>$year_to) $sign=-1; 
   else if ($year_from==$year_to) 
       { 
       if ($mon_from>$mon_to) $sign=-1; 
       else if ($mon_from==$mon_to) 
           if ($day_from>$day_to) $sign=-1; 
       } 

   if ($sign==-1) {//invert dates 
       $day_from = $date_to_parts[0]; 
       $mon_from = $date_to_parts[1]; 
       $year_from = $date_to_parts[2]; 
       $day_to = $date_from_parts[0]; 
       $mon_to = $date_from_parts[1]; 
       $year_to = $date_from_parts[2]; 
       } 

   switch ($unit) 
       { 
       case 'd': case 'D': //calculates difference in days            
       $yearfrom1=$year_from;  //actual years 
       $yearto1=$year_to;      //(yearfrom2 and yearto2 are used to calculate inside the range "0")    
       //checks ini date 
       if ($yearfrom1<1980) 
           {//year is under range 0 
           $deltafrom=-floor((1999-$yearfrom1)/20)*20; //delta t1 
           $yearfrom2=$yearfrom1-$deltafrom;          //year used for calculations 
           } 
       else if($yearfrom1>1999) 
           {//year is over range 0 
           $deltafrom=floor(($yearfrom1-1980)/20)*20; //delta t1 
           $yearfrom2=$yearfrom1-$deltafrom;          //year used for calculations            
           } 
       else {//year is in range 0 
           $deltafrom=0; 
           $yearfrom2=$yearfrom1; 
           } 
           
       //checks end date 
       if ($yearto1<1980) {//year is under range 0 
           $deltato=-floor((1999-$yearto1)/20)*20; //delta t2 
           $yearto2=$yearto1-$deltato;            //year used for calculations 
           } 
       else if($yearto1>1999) {//year is over range 0 
           $deltato=floor(($yearto1-1980)/20)*20; //delta t2 
           $yearto2=$yearto1-$deltato;            //year used for calculations            
           } 
       else {//year is in range 0 
           $deltato=0; 
           $yearto2=$yearto1; 
           } 
   
       //Calculates the UNIX Timestamp for both dates (inside range 0) 
       $ts_from = mktime(0, 0, 0, $mon_from, $day_from, $yearfrom2); 
       $ts_to = mktime(0, 0, 0, $mon_to, $day_to, $yearto2); 
       $diff = ($ts_to-$ts_from)/86400; 
       //adjust ranges 
       $diff += 7305 * (($deltato-$deltafrom) / 20); 
       return $sign*$diff; 
       break; 
       
       case 'y': case 'Y': //calculates difference in years 
       $diff=$year_to-$year_from;        
       $adjust=0; 
       if ($mon_from>$mon_to) $adjust=-1; 
       else if ($mon_from==$mon_to) 
           if ($day_from>$day_to) $adjust=-1; 
       
       return $sign*($diff+$adjust); 
       break;        
       } 
}

function array_sort($array, $on, $order=SORT_ASC)               //сортировка массива (массив, сортируемый столбец, порядок сортировки)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}



?>