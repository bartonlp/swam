<?php
  // Send pop up. Part of the Chat system
  // Page is started from Online.php by a JavaScript:
  // window.open("/swam/send.php?targetId=" + target, "send", "width=550,height=500,scrollbars");
  // targetId is passed in. 
  // Or it is called by itself to post messges it gathered.

  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
  header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
  header("Pragma: no-cache");                                   // HTTP/1.0

  require("secureinfo/id.phpi");
  $id = CheckId("no_inc");

  // Get the name of the person to send to. This is $targetId which is passed in.

  $result = mysql_query("select name from account where id=$targetId", $db);
  if(!$result) {
    PhpError("send.php Error 1: " . mysql_error($db));
    exit();
  }

  $row =  mysql_fetch_row($result);
  $targetName = $row[0];
?>

<html>
<head>
<title>SWAM-CHAT SENDER</title>
</head>
<body>
<h1 align=center>South West Aquatic Masters Chat Send</h1>
<p align=center>Message to <?php echo "$targetName"; ?></p>

<?php
  // Did we call ourself? If so $sendmsg is the data to send.

  if($sendmsg) {
    // Process posted info.

    $result = mysql_query("insert into chatmsg (targetId, senderId, senderName, msg, new) values ($targetId, $id, '$SwamName', '$sendmsg', 1)", $db);

    if(!$result) {
      PhpError("send.php Error 2: " . mysql_error($db));
      exit();
    }

    print("
<h1>Message Send to $targetName:</h1>
<p>Message: $sendmsg</p>
<hr>\n");

  }
?>

<FORM ACTION=<?php echo "$PHP_SELF"; ?> method="post">
Send Message<br>
<TEXTAREA NAME="sendmsg" ROWS=5 COLS=45>

</TEXTAREA>
<input type="hidden" name="targetId" value=<?php echo "$targetId"; ?>>
<br>
<INPUT TYPE="submit" VALUE="Send"> 
<INPUT TYPE="reset" VALUE="Reset">
</form>

</body>
</html>

