<?php
// php-gd, jped lib libraries are needed, libgd if ubuntu ? (if u have arch jped-turbo xd)
// enable in php.ini gd extension

function addWatermark($nameFile, $timestamp, $target_file, $watermarkDir, $imageFileType)
{
    $image = '';

    if($imageFileType == 'png')
    {
        $image = imagecreatefrompng($target_file);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 100;
        imagejpeg($bg, $target_file.'.jpg', $quality);
        imagedestroy($bg);
        $image = imagecreatefromjpeg($target_file.'.jpg');
    }
    else
    {
        $image = imagecreatefromjpeg($target_file);
    }

    $watermark = imagecreatefrompng($watermarkDir);

    $image_width = imagesx($image);
    $image_height = imagesy($image);

    $watermark_width = imagesx($watermark);
    $watermark_height = imagesy($watermark);

    $resized_watermark= imagecreatetruecolor($image_width,$image_height);
    $background = imagecolorallocate($resized_watermark , 0, 0, 0);
    // removing the black from the placeholder
    imagecolortransparent($resized_watermark, $background);

    $scale = $image_height/$image_width;
    if($scale > 1){
        // -100 doing padding. If less is needed then must divide /2 in imagecopymerge function
        imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_width-100, $image_width-100, $watermark_width, $watermark_height);
        $resized_watermark_width = imagesx($resized_watermark);
        $resized_watermark_height = imagesy($resized_watermark);
        imagecopymerge($image,$resized_watermark,50,50,0,(-($image_height-$image_width)/2),$resized_watermark_width,$resized_watermark_height,30);

    }else if($scale < 1){
        // -100 doing padding. If less is needed then must divide /2 in imagecopymerge function
        imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_height-100, $image_height-100, $watermark_width, $watermark_height);
        $resized_watermark_width = imagesx($resized_watermark);
        $resized_watermark_height = imagesy($resized_watermark);
        imagecopymerge($image,$resized_watermark,50,50,(-($image_width-$image_height)/2),0,$resized_watermark_width,$resized_watermark_height,30);

    }else{
        // -100 doing padding. If less is needed then must divide /2 in imagecopymerge function
        imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_width-100, $image_height-100, $watermark_width, $watermark_height);
        $resized_watermark_width = imagesx($resized_watermark);
        $resized_watermark_height = imagesy($resized_watermark);
        imagecopymerge($image,$resized_watermark,50,50,0,0,$resized_watermark_width,$resized_watermark_height,30);
    }

    imagejpeg($image,'watermarked/'.$timestamp."_".$nameFile.'.jpg');    //create new image with watermark
}

