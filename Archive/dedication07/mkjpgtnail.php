#!/usr/bin/php -q
<?php
  // Input variables:
  // $jpegImg: name of the jpeg image
  // $dir: directory of the image
  // $scale: division factor. For example 2 means 1/2 the size. If $scale not supplied then 2
  // $force: The width size to use to force to if $scale missing

//  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
//  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
//  header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
//  header("Pragma: no-cache");                                   // HTTP/1.0
  Header("Content-type: image/jpeg");

	$dir = getcwd();
	
  $image = "$dir/$jpegImg";
//  $filename = "$dir/small-$jpegImg";

  $srcImg = imagecreatefromJPEG($image);
  $srcSize = getimagesize($image);

  if(!isset($scale)) {
      $scale = 4;
    // Force image to width of 200 pixles or the value of $force if set

    if(!isset($force)) {
      $force = 200;
    }

    $scale = $srcSize[0] / $force;
  }

	$file = fopen("/home/www/html/swam/imgerror.txt", "a");
  fputs($file, "$dir\n$image\n$scale\n$filename\n");
  fclose($file);

  $dstImg = imagecreate($srcSize[0]/$scale, $srcSize[1]/$scale);
  imagecopyresized($dstImg, $srcImg, 0, 0, 0, 0, $srcSize[0]/$scale, $srcSize[1]/$scale, $srcSize[0], $srcSize[1]);
  imageJPEG($dstImg);
?>
  
