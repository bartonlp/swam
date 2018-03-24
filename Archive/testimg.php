<?php
  // This file takes uses all the jpegs in the directory where is it and creats
  // a web page with the images that you can click on to see the full size image. 
  // The scale is set to 1/8 normal size

  $dir = dirname($PHP_SELF);

  $id = opendir("./");
  
  print("<html><head></head><body>\n");

  if(file_exists("./clicknext.php")) {
    print("You can walk through the pictures. See a small thumnail and if you want to see the full
picture just click on it. <a href=\"clicknext.php\">Click Through Images</a><hr><br>\n");
  }

  while(($file = readdir($id)) != false) {
    if(ereg("\.jpg", $file))
      print("<a href=\"$file\"><img src=\"mkjpgtnail.php?jpegImg=$file&dir=$dir\" alt=\"$file\"></a><br>\n");
  }
  closedir($id);

  print("</body></html>\n");

?>
  