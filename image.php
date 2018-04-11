<?php
include "lib.php";
include "config.php";

@$id= (getRequest('id')."");

$img_path_a = explode("-",$id);
$img_path = $img_directory.implode("\\",$img_path_a);
$filename = $img_path;
if (file_exists($filename.".jpg")) {
	$image_type = "jpg";
	$sOrigImg =  $filename.".jpg";
	$rImage = imagecreatefromjpeg($sOrigImg);
} elseif (file_exists($filename.".png")){
	$image_type = "png";
	$sOrigImg =  $filename.".png";
	$rImage = imagecreatefrompng($sOrigImg);
} else {
	$image_type = "png";
	$sOrigImg =  $img_directory."NoImage.png";
	$rImage = imagecreatefrompng($img_directory."NoImage.png");
}
//header ("Content-type: image/png");
//imagepng($rImage); // Выводим изображение








//include  '../../lib.php';
//include  '../../config.php';
    function resizeImg ($img, $w, $h, $newWidth, $newHeight) {
        $res = $img;

    // Узнаём информацию об изображении
        $prop[0] = $w;
        $prop[1] = $h;

    // Задаём в переменных новую ширину и высоту
    //	$newWidth = 200;
    //	$newHeight = 200;

        /**
         *  Создаём новый ресурс с нужной шириной и высотой,
         *   в который запишем исходный ресурс,
         *   заметим, что изображение полноцветное - imageCreateTrueColor
         */
        $tmp = imageCreateTrueColor($newWidth, $newHeight);

        /**
        Перед тем как произодить опрерации с новым ресурсом,
        установим некоторые опции
        imageAlphaBlending - устанавливает режим смешивания(режим
        смешивания недоступен для изображений с палитрой)
        по умолчанию для truecolor изображений - true, для изображений
        с палитрой - false
        true/false - включен/выключен

        true - при накладывании одного изображения на другое
        цвета пикселей нижележащего и накладываемого изображения смешиваются,
        параметры смешивания определяются прозрачностью пикселя.

        false - накладываемый пиксель заменяет исходный
         */
        imageAlphaBlending($tmp, false);

        /**
        ImageSaveAlpha
        Сохранять или не сохранять информацию о прозрачности
        по умолчанию - false, а надо true
        */

        imageSaveAlpha($tmp, true);

        /**
        Всё, теперь прозрачность должна сохранятся
        */

        /**
        копируем исходное изображение с новое, в новый ресурс
        */
        imageCopyResampled($tmp, $res, 0, 0, 0, 0, $newWidth,
            $newHeight, $prop[0], $prop[1]);
        return $tmp;
    }




//$sOrigImg = $img_directory."pic1.jpg";
$sWmImg = $img_directory."wm2.png";
//echo "sWmIm=g".$sWmImg.$br ;
//echo "sOrigImg=".$sOrigImg;

$aImgInfo = getimagesize($sOrigImg);
$WaterMarkInfo = getimagesize($sWmImg);
$WaterMarkInfo = getimagesize($sWmImg);
$aWmImgInfo = $aImgInfo;

//    printArr($aWmImgInfo); exit;

//exit;

if (is_array($aImgInfo) && count($aImgInfo)) {
    header ("Content-type: image/png");

    $iSrcWidth = $aImgInfo[0];
    $iSrcHeight = $aImgInfo[1];

    $iFrameSize = 15;

//    $rImage = imagecreatetruecolor($iSrcWidth+$iFrameSize*2, $iSrcHeight+$iFrameSize*2); // Создаем новое изображение
    $rImage = imagecreatetruecolor($iSrcWidth, $iSrcHeight); // Создаем новое изображение
    if ($image_type=="jpg"){
	    $rSrcImage = imagecreatefromjpeg($sOrigImg); //  Создаем исходное изображение из JPG
    } elseif ($image_type=="png"){
	    $rSrcImage = imagecreatefrompng($sOrigImg); //  Создаем исходное изображение из JPG
    }

//    $aGrid[1] = imagecolorallocate($rImage, 130, 130, 130); // Определяем цвета для прямоугольной области
//    $aGrid[2] = imagecolorallocate($rImage, 150, 150, 150);
//    $aGrid[3] = imagecolorallocate($rImage, 170, 170, 170);
//    $aGrid[4] = imagecolorallocate($rImage, 190, 190, 190);
//    $aGrid[5] = imagecolorallocate($rImage, 210, 210, 210);
//    for ($i=1; $i<=5; $i++) { // Наша маленькая рамка будет содержать 5 прямоугольников для эмуляции градиента
//        imagefilledrectangle($rImage, $i*3, $i*3, ($iSrcWidth+$iFrameSize*2)-$i*3, ($iSrcHeight+$iFrameSize*2)-$i*3, $aGrid[$i]); // Рисуем заполненный прямоугольник
//    }


//    imagecopy($rImage, $rSrcImage, $iFrameSize, $iFrameSize, 0, 0, $iSrcWidth, $iSrcHeight); // Копируем полученное изображение на изображение-источник
    imagecopy($rImage, $rSrcImage, 0, 0, 0, 0, $iSrcWidth, $iSrcHeight); // Копируем полученное изображение на изображение-источник

    if (is_array($aWmImgInfo) && count($aWmImgInfo)) {
        $watermark = imagecreatefrompng($sWmImg);
        $rWmImage = resizeImg($watermark, $WaterMarkInfo[0], $WaterMarkInfo[1], $iSrcWidth, $iSrcHeight); //  Создаем изображение водяного знака
//			$rWmImage = imagecreatefrompng($sWmImg);
//			imagepng($rWmImage); exit;
        imagecopy($rImage, $rWmImage, $iSrcWidth-$aWmImgInfo[0], $iFrameSize, 0, 0, $aWmImgInfo[0], $aWmImgInfo[1]); // Копируем изображение водяного знака на изображение источник
    }


    $iTextColor = imagecolorallocate($rImage, 200, 200, 200); // Определяем цвет текста
    $sIP = $_SERVER['REMOTE_ADDR']; // Определяем IP посетителя
//    imagestring($rImage, 5, $iFrameSize*2, $iFrameSize*2, "IP: {$sIP}, {$sOrigImg} - ({$iSrcWidth} x {$iSrcHeight})", $iTextColor); // Рисуем текст
    imagestring($rImage, 5, $iFrameSize*2, $iFrameSize*2, "IP: $sIP", $iTextColor); // Рисуем текст


    imagepng($rImage); // Выводим изображение
} else {
    echo 'Image error!';
    exit;
}
?>