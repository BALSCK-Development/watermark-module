<?php
// php-gd, jped lib libraries are needed, libgd if ubuntu ? (if u have arch jped-turbo xd)
// enable in php.ini gd extension

function addWatermark($nameFile,$timestamp, $imageDir, $watermarkDir,$imageFileType)
{


    $image = '';

    if($imageFileType == 'png')
    {
        $image = imagecreatefrompng($imageDir);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 100; // 0 = worst / smaller file, 100 = better / bigger file
        imagejpeg($bg, $imageDir.'.jpg', $quality);
        imagedestroy($bg);
        $image = imagecreatefromjpeg($imageDir.'.jpg');
    }else{
        $image = imagecreatefromjpeg($imageDir);
    }

    //$image = imagecreatefrompng($imageDir);
    $watermark = imagecreatefrompng($watermarkDir);


    //TODO work only for jpg
    //$image = imagecreatefromjpeg($imageDir);                                //load image where u want add watermark
    //$watermark = imagecreatefromjpeg($watermarkDir);                        // load watermark

    $image_width = imagesx($image);                                         // get image width
    $image_height = imagesy($image);                                        //get image height

    $watermark_width = imagesx($watermark);                                 //get original watermark width
    $watermark_height = imagesy($watermark);                                //get original watermark height

    $resized_watermark= imagecreatetruecolor($image_width,$image_height);

    $scale = $image_height/$image_width;
    if($scale > 1){
        imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_width, $image_width, $watermark_width, $watermark_height);
        $resized_watermark_width = imagesx($resized_watermark);
        $resized_watermark_height = imagesy($resized_watermark);
        imagecopymerge($image,$resized_watermark,0,0,0,-($image_height-$image_width)/2,$resized_watermark_width,$resized_watermark_height,30);    //copy watermark to image

    }else if($scale < 1){
        imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_height, $image_height, $watermark_width, $watermark_height);
        $resized_watermark_width = imagesx($resized_watermark);
        $resized_watermark_height = imagesy($resized_watermark);
        imagecopymerge($image,$resized_watermark,0,0,-($image_width-$image_height)/2,0,$resized_watermark_width,$resized_watermark_height,30);    //copy watermark to image
    }else{
        imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_width, $image_height, $watermark_width, $watermark_height);
        $resized_watermark_width = imagesx($resized_watermark);
        $resized_watermark_height = imagesy($resized_watermark);
        imagecopymerge($image,$resized_watermark,0,0,0,0,$resized_watermark_width,$resized_watermark_height,30);    //copy watermark to image
    }
    imagejpeg($image,'watermarked/'.$timestamp."_".$nameFile.'.jpg');    //create new image with watermark
    //imagepng($image,'watermarked/'.$timestamp."_".$nameFile);
}

