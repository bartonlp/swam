<?php 
  // This creates a web page that has a jpeg image and a previous/next button
  // You can click on the thumnails and see the full size picture and when you
  // click on the browsers back button his page shows the next picture.
  // Put this file in the directory where the files reside
  // This needs the mkthums.php program to be run first! to create the small-.*\.jpg files
  
  session_start();

  if(session_is_registered("currpath")) {
    if($currpath != getcwd()) {
      session_unset();
      session_destroy();
      session_start();

      // Check to see if there is a parameter file

      if(file_exists("./clickparams.txt")) {
        $lines = file("./clickparams.txt");
        while(list($k, $v) = each($lines)) {
          $len = strlen(chop($v));
          if(strlen(chop($v))) {
            if($v[0] != "#") {
              eval("$v");
            }
          }
        }
      }
    }
  }
  


  if(!isset($end)) { 
    $id = opendir("./");

    while(($file = readdir($id)) != false) {
      if(eregi("small-.*\.jpg", $file)) {
        $image[] = $file;
      }
    }
   
    closedir($id);

    natsort($image);
    reset($image);

    $index = 0;
    $end = count($image);

    $currpath = getcwd();

    session_register(currpath);
    session_register(index);
    session_register(end);
    session_register(image);

    if($title) session_register(title);
    if($msg) session_register(msg);

  } else {
    if(!isset($dir)) {
      if($mode == "Reset") {
        $index = 0;
      }
      //else {
      //session_unset();
      //session_destroy();
      //}
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
<body>\n"
  );
  
  if($title)
    print("$title\n");

  if($msg)
    print("$msg\n");

  print("There are $end pictures, this is image number $tmp<br>\n");
  print("<a href=$PHP_SELF?dir=back>[back]</a><a href=$PHP_SELF?dir=next>[next]</a>\n");
  print("<form action=\"$PHP_SELF\" method=post>
  <input type=submit name=mode value=Reset>
  </form>\n");

  $path = dirname($PHP_SELF);

  $img = $image[$index];
  $img = ereg_replace("small-", "", $img);

  print("<p>$img</p>\n");

  print("<a href=\"$path/$img\"><img src=\"$image[$index]\"></a><br>\n");

?>

</body>
</html>
