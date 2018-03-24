<?php
// This is called from a JavaScript as an IMG

require("secureinfo/id.phpi");

  // When the client exits from our site this gets called
  // we update the lasttime entry with the number of seconds
  // the client was in our site.

  // Open database

  $id = CheckId("no_inc");

  // Only track people that are in lasttime who are people who have registered.

  if(!$SwamName)
    exit();

  $d = date("Y-m-d H:i:s");

  $result = mysql_query("select max(lasttime), DATE_FORMAT(max(lasttime), '%Y-%m-%d %H:%i:%S') from lasttime where id=$id", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "exittime.php2 Error1: $d, $error, $SwamName id=$id\n");
    fclose($fd);

    exit();
  }

  $row = mysql_fetch_row($result);

  $last = $row[0];

  $fd = fopen("exittime.txt", "a");
  fputs($fd, "End: $row[1], $SwamName id=$id\n");
  fclose($fd);

  $result = mysql_query("update lasttime set start=$last where id=$id and lasttime=$last", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "exittime.php2 Error2: $d, $error, $SwamName id=$id\n");
    fclose($fd);

    exit();
  }
?>