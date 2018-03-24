<?php
  // CheckChat.php
  // This is how the Chat system communicates with the server.
  // This looks like an Image to the JavaScript in Online.php

  // Make this look like a gif image, and make sure we don't cache this page.

  Header("Content-type: image/gif");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
  header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
  header("Pragma: no-cache");                                   // HTTP/1.0

  require("secureinfo/id.phpi");

  $id = CheckId("no_inc");

  // $w is the returned image width which we use to transfer information back to Online.php
  // The width is a bitmap value.
  // 1 is always set. If it is the only bit then no change.
  // 2 someone new or someone left the web site so update Online.php page
  // 4 there is a new chat message ready to view. Online.php will start receive.php

  $w = 1;

  // Check in the chatmsg table to see if there is anything for this visitor that is new.

  $result = mysql_query("select * from chatmsg where targetId='$id' and new=1", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "checkchat.php Error5: $error, id=$id\n");
    fclose($fd);
  } else {
    // If there are any rows then there is a new message. Bit 3 (mask 4) tells Online.php
    // to start (or restart) the receive.php page.

    if(mysql_num_rows($result) != 0) {
      $w |= 4;
    }
  }

  // Now check to see if someone has come or gone from the web site.
  // Changed is set for everyone when someone comes or goes.

  $result = mysql_query("select changed from online where id='$id'", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "checkchat.php Error1: $error, id=$id\n");
    fclose($fd);
  } else {
    if(mysql_num_rows($result) == 0) {
      // Some how were are here and there is no entry for us?
      // Add it

      $result = mysql_query("insert into online (id,name,changed) values($id, '$SwamName', 0)", $db);
      
      $w |= 2;  // bit 2 set means change
    } else {
      // We found an entry for us so look to see if changed is true.

      $row = mysql_fetch_row($result);

      if($row[0] == 1)
        $w |= 2;  // bit 2 set means change
    }
  }

  // update keepalive. Keepalive is used to catch logoffs that somehow did not cause the
  // JavaScript Exit() function from being run in Online.php.

  $result = mysql_query("update online set keepalive=now() where id='$id'", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "checkchat.php Error2: $error, id=$id\n");
    fclose($fd);
  }

  // clean up any timestamps that are older than two minutes

  $result = mysql_query("delete from online where keepalive < date_sub(now(), INTERVAL 2 MINUTE)", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "checkchat.php Error3: $error, id=$id\n");
    fclose($fd);
  }  

  // If we deleted any rows we need to set changed

  if(mysql_affected_rows($db)) {
    $result = mysql_query("update online set changed=1", $db);

    if(!$result) {
      $error = mysql_error($db);
      $fd = fopen("phpimageerrors.txt", "a");
      fputs($fd, "checkchat.php Error4: $error, id=$id\n");
      fclose($fd);
    }
    $w |= 2;    // force update
  }

  $im = imagecreate($w, 1);
  ImageGif($im);
  ImageDestroy($im);
?>
