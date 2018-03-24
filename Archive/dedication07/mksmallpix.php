#!/usr/bin/php -q
<?php
  // This file takes the contents of a directory and makes thumnail jpegs with the 
  // name "small-<filename>"
  // We sym link this file into the directory where the pictures are. 
  //
  
  $dir = getcwd();

  $id = opendir($dir);

  while(($file = readdir($id)) != false) {
    if(eregi("^small-.*\.jpg", $file))
        continue;

    if(eregi("\.jpg", $file)) {
      $image = "$dir/$file";
      $filename = "$dir/small-$file";

      if(file_exists($filename))
        continue;

      $srcImg = imagecreatefromJPEG($image);
      $srcSize = getimagesize($image);

      $force = 150;

      $scale = $srcSize[0] / $force;

      $file = fopen("imgerror.txt", "w");
      fputs($file, "$image\n$scale\n$filename\n");
      fclose($file);

      $dstImg = imagecreate($srcSize[0]/$scale, $srcSize[1]/$scale);
      imagecopyresized($dstImg, $srcImg, 0, 0, 0, 0, $srcSize[0]/$scale, $srcSize[1]/$scale, $srcSize[0], $srcSize[1]);
      imageJPEG($dstImg, $filename);
    }
  }
  closedir($id);
?>
  
