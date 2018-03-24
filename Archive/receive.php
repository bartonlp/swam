<?php
  // Receive pop up. Part of the Chat system
  // This window is started by Online.php when there is a new message to read.

  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
  header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
  header("Pragma: no-cache");                                   // HTTP/1.0

  require("secureinfo/id.phpi");

  $id = CheckId("no_inc");

  // receive.php can call itself to clear out old messages
  // If $clear is set then remove old messages

  if($clear) {
    // Remove the messages from the database

    $result = mysql_query("delete from chatmsg where targetId=$id and new=0", $db);

    if(!$result) {
      PhpError("receive.php Error 3: " . mysql_error($db));
      exit();
    }
  }

  // Get all messages for this visitor

  $result = mysql_query("select senderName, date_format(timeOfMsg, '%Y-%m-%d %H:%i:%S'), msg from chatmsg where targetId=$id order by timeOfMsg", $db);

  if(!$result) {
    PhpError("receive.php Error 1: " . mysql_error($db));
    exit();
  }

  // Make the page
?>

<html>
<head>
<title>SWAM-CHAT RECEIVER</title>
</head>
<body>
<h1 align=center>South West Aquatic Masters CHAT RECIEVE STARTED</h1>

<form action=<?php echo "$PHP_SELF"; ?> methode=post>
Clear all Messages: <input type=submit name="clear" value="clear">
</form>

<table border=1>
<tr>
<th>Sender</th><th>Sent</th><th>Message</th>
</tr>

<?php
  // loop through the results from chatmsg table above and show all the messages

  while($row = mysql_fetch_row($result)) {
    // print SenderName, TimeOfMsg, and Message

    print("
<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n");
  }

  // After we show everything mark all messages for visitor as read.

  $result = mysql_query("update chatmsg set new=0 where targetId=$id", $db);

  if(!$result) {
    PhpError("receive.php Error 2: " . mysql_error($db));
    exit();
  }
?>

</table>

</body>
</html>
