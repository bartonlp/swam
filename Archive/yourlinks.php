<!--
This file is INCLUDED in the index-members.php page.

This allows users to customize their site and add links to other
sites.
Add a table to database "mylinks"
The table has:
account
description
link

On the web page there is a button "Customize Links" (or something like
that.) The page shows existing custom links with a "Delete" button.
There is also a text area with "Description, Link" and "Add Link"
button. The "Add Link" button adds an entry to the mylinks table. If
there are entries in the mylinks tables they are displayed on the web
page.
-->

<?php
  // Check to see if there are any entries in the database.

  $result = mysql_query("select * from mylinks where account = $SwamId", $db);

  if(!$result) {
    PhpError("yourlinks.phpi Error 1: " . mysql_error($db));
    exit();
  }

  if(mysql_num_rows($result) != 0) {
    // Create table

    print("
<table id='mylinks'>
<tr><th colspan='2'>My Links</th></tr>
");


    while($row = mysql_fetch_row($result)) {
      $mylinksAcc = $row[0];
      $mylinksDesc = $row[1];
      $mylinksLink = $row[2];

      // Create link line

      print("
<tr>
<td class='liteYellow'><span>$mylinksDesc</span></td>
<td class='liteYellow'><a href='http://$mylinksLink'>$mylinksLink</a></td>
</tr>
");
    }

    print("
</table>
<br>
");

  }
?>

