<?php
  // This program takes the contents of the directory and makes an index.html file
  // that has the jpeg images as thumnails

  $dir = getcwd();

  $id = opendir($dir);

  if($pass == 2) {
    $fd = fopen("index.html", "w");
    fputs($fd, "<html>\n<head>\n<title>$title</title>\n</head>\n<body>\n<h1 align=center>$title</h1>\n<hr>\n<table border=2>\n");
    
    while(($file = readdir($id)) != false) {
      if(eregi("\.jpg", $file)) {
        fputs($fd, "<tr><td><a href=\"$file\"><img src=\"/mkjpgtnail.php?jpegImg=$file&dir=$dir&scale=4\" alt=\"$ar[$file]\"></a></td><td>");
        if($ar[$file] != "")
          fputs($fd, "$ar[$file]</td></tr>\n");
        else
          fputs($fd, "$file</td></tr>\n");
      }
    }

    closedir($id);
    fputs($fd, "</table>\n</body>\n</html>\n");

    fclose($fd);
    
    header("Location:index.html");
    print("All Done");
    exit();
  }

  print("<html><head></head><body>\n");

  print("<form action=$PHP_SELF method=post>\n");
  print("<table>\n");
  print("<tr><td>Page Title</td><td><input type=text name=title size=50></td></tr>\n");

  while(($file = readdir($id)) != false) {
    if(eregi("\.jpg", $file))
      print(
"<tr><td><img src=\"/mkjpgtnail.php?jpegImg=$file&dir=$dir&scale=4\" alt=\"$file\"></td>
<td><input type=text name=ar[$file] size=50></td></tr>\n"
           );

  }
  closedir($id);
  
  print("</table><br>\n");
  print("<input type=hidden name=pass value=2><input type=submit value=Submit></form>\n");
  print("</body></html>\n");

?>
  