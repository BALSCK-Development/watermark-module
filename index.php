<?php
// php-gd, jped lib libraries are needed (if u have arch jped-turbo xd)
// enable in php.ini gd extension

function addWatermark($imageUrl)
{
    $image = imagecreatefromjpeg($imageUrl);                                //load image where u want add watermark
    $watermark = imagecreatefromjpeg('watermark.jpg');              // load watermark

    $image_width = imagesx($image);                                         // get image width
    $image_height = imagesy($image);                                        //get image height

    $watermark_width = imagesx($watermark);                                 //get original watermark width
    $watermark_height = imagesy($watermark);                                //get original watermark height

    $resized_watermark= imagecreatetruecolor($image_width,$image_height);   //new width, height

    imagecopyresampled($resized_watermark, $watermark, 0, 0, 0, 0, $image_width, $image_height, $watermark_width, $watermark_height); //resize watermark

    $resized_watermark_width = imagesx($resized_watermark);                 //get resized watermark width ( width is yet like image)
    $resized_watermark_height = imagesy($resized_watermark);                //get resized watermark height ( same with height)

    imagecopymerge($image,$resized_watermark,0,0,0,0,$resized_watermark_width,$resized_watermark_height,10);    //copy watermark to image
    imagejpeg($image,'newImage.jpg');                               //create new image with watermark

}

addWatermark('turtle.jpg');