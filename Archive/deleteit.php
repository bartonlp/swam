<?php
  // deleteit.php is called from Online.php Exit() function.
  // This deletes the visitor from the online table and sets changed for all
  // other visitors. It also cleans out all old (new=0) messages from the
  // chatmsg table.

  require("secureinfo/id.phpi");

  $id = CheckId("no_inc");

  $d = date("Y-m-d H:i:s", $date/1000);

  $fd = fopen("deleteit.txt", "a");
  fputs($fd, "End: $SwamName, $d, id=$id\n");
  fclose($fd);

  $result = mysql_query("delete from online where id=$id", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "deleteit.php Error1: $d, $error, id=$id\n");
    fclose($fd);
  }

  $result = mysql_query("update online set changed=1", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "deleteit.php Error2: $d, $error, id=$id\n");
    fclose($fd);
  }

  $result = mysql_query("delete from chatmsg where targetId=$id and new=0", $db);

  if(!$result) {
    $error = mysql_error($db);
    $fd = fopen("phpimageerrors.txt", "a");
    fputs($fd, "deleteit.php Error3: $d, $error, id=$id\n");
    fclose($fd);
  }

  // Now make a quick page that says we are logging off
?>

<html>
<head>
<script language=javascript>
<!--
function CloseIt() {
  window.close();
}

setTimeout('CloseIt()', 1000);

//-->
</script>
</head>

<body>
<h1 align=center>Logging Off Swam Chat Watch</h1>
</body>
</html>
