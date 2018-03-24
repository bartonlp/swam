<?php 
  // This creates a web page that has a jpeg image and a previous/next button
  // You can click on the thumnails and see the full size picture and when you
  // click on the browsers back button his page shows the next picture.
  // Put this file in the directory where the files reside

  session_start();
?>
<?php
  if(!isset($end)) { 
    $id = opendir("./");

    while(($file = readdir($id)) != false) {
      if(ereg("\.jpg", $file)) {
        $image[] = $file;
      }
    }
   
    closedir($id);

    natsort($image);
    reset($image);

    $index = 0;
    $end = count($image);

    if(session_is_registered(currpath)) {
      if($currpath != dirname($PHP_SELF)) {
        session_unset();
        session_destroy();
      }
      session_start();
    }

    $currpath = dirname($PHP_SELF);

    session_register(currpath);
    session_register(index);
    session_register(end);
    session_register(image);
  } else {
    if(!isset($dir)) {
      if($mode == "Reset") {
        $index = 0;
      }
    } elseif($dir == "back") {
      if($index > 0)
        --$index;
    } else {
      if($index < $end-1)
        ++$index;
    }
  }

  $tmp = $index + 1;
  print("
<html>
<head>
</head>
<body>
      ");
  
  print("There are $end pictures, this is image number $tmp<br>\n");
  print("<a href=$PHP_SELF?dir=back>[back]</a><a href=$PHP_SELF?dir=next>[next]</a>\n");
  print("<form action=\"$PHP_SELF\" method=post>
  <input type=submit name=mode value=Reset>
  <input type=submit name=mode value=Destroy>
  </form>\n");

  $path = dirname($PHP_SELF);

  print("<a href=\"$path/$image[$index]\"><img src=\"mkjpgtnail.php?jpegImg=$image[$index]&dir=$path\"></a><br>\n");

?>

</body>
</html>
