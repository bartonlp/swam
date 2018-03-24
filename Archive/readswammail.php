<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

extract($_GET);
extract($_POST);
$id = $sid;

// ---------------------------------------------------------------------------
// Functions *****

function ShowMessage($item, $sender, $mode) {
  global $S;

  // Get the message from "swammailmsg" for $item

  $result = mysql_query("select datesent, message, subject, id as senderid from swammailmsg where item='$item'");

  $row = mysql_fetch_assoc($result);
  extract($row);

  //echo "ShowMessage, id=$senderid, item=$item, sender=$sender, mode=$mode<br>";
    
  // Get the message from "swammailmsg" for $item
  // Display the message

  print("
<p>From: $sender<br>
Date Sent: $datesent<br><br>
Subject: $subject<br>
Message:<br>
$message</p>\n"
    );

    // We can Reply to new messages but not to Old ones!
    // For OLD messages we do not put a Reply button up and don't update the 
    // database!

  if($mode != 'old') {
    echo <<<EOF
<form action="swammail.php" method="post">
<input type=hidden name="ReplyName" value="$sender">
<input type=hidden name="ReplyId" value="$senderid">     
<input type=hidden name="mode" value="2">
<input type=submit value="Reply">
</form>
EOF;

    // For new messages update the "swammailto" with the current date to show
    // the message has been read!

    $result = $S->query("update swammailto set dateread=now() where id=$senderid and item=$item");
  }
} // end function ShowMessage
  
// ---------------------------------------------------------------------------

function ShowMySends($myId) {
  global $S;

  // Get all of my messages from "swammailmsg"

  $result = $S->query("select item, message, datesent, subject from swammailmsg where id=$myId");

  // Loop through all messages

  while($row = mysql_fetch_row($result)) {
    // Each message get all the people I sent it to
    $msg = $row[1];
    $item = $row[0];
    $dateSent = $row[2];
    $subject = $row[3];

    if(!$subject || ($subject == "")) $subject = "NO SUBJECT";

    // Who was this sent to and have they read the message?

    $result1 = mysql_query("select id, dateread from swammailto where item='$item'");

    print("Sent: $dateSent<br>To:<br>\n");

    // Loop through all the people the message was sent to

    while($row1 = mysql_fetch_row($result1)) {
      $toId = $row1[0];
      $dateRead = $row1[1];

      // Get the name of the recipient from "account"

      $result2 = mysql_query("select concat(fname, ' ', lname) as name from swammembers where id='$toId'", $db);

      $toName = mysql_result($result2, 0);

      print("&nbsp;&nbsp;&nbsp");

      // Show either read date or UNREAD

      if($dateRead) {
        print("$toName: Read ($dateRead)");
      } else {
        print("<span class=red>$toName: UNREAD</span>");
      }
      print("<br>\n");
    }
      
    print("
<br>Subject: $subject
<br>Message:<p>$msg</p><hr>\n"
         );
  }
}  // End Function ShowMySends

  // End Functions ***
  // ---------------------------------------------------------------------------

$h->title = "South West Aquatic Masters Read SwamMail";
$h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br>SwamMail</h1><hr>";

$top = $S->getPageTop($h);
$footer = $S->getFooter();
echo $top;

// $mode can be:
// not set: Read new posts
// old: Read old posts
// mine: read my old posts
// Read: If there were multiple messages then we call ourself to display each one

switch($mode) {
  case '':
    // Read new posts. We are coming from the home page
    // Get all items for me that I have not read

    $result = $S->query("select item from swammailto where id='$id' and dateread is null");
    break;
  case 'old':
    // read old posts
    // Get all items for me that I have already read

    $result = $S->query("select item, dateread from swammailto where id=$id and dateread is not null");
    break;
  case 'mine':
    // read my posts

    ShowMySends($id);

    exit;     // EXIT
  case 'Read':
    // Show one message and then let the user return to read the rest
    // of his messages.

    ShowMessage($ReadItem, $ReadSender, $ReadOld);

    // We will call ourself again with $mode blank and start all over again

    print("<p><a href='$S->self?sid=$S->id'>Return To Read SwamMail</p>\n$footer");
    exit;     // EXIT
}

// cases "", old, and mine get here!

$mailCnt = mysql_num_rows($result);

if($mailCnt == 0) {
  print("<h1>No New Mail</h1>\n");
} else if($mailCnt == 1) {
  // ONLY one swammail so we can show it and the reply all on this page

  print("<h2 align=center>Mail Count: $mailCnt</h2>");

  $row = mysql_fetch_row($result);
  $item = $row[0];

  // If mode is old then $row[1] is pressent and is read date

  if($mode == 'old') {
    $readDate = $row[1];
  }
  // Get the message for this item

  list($result2, $n) = $S->query("select id, datesent, message, subject from swammailmsg where item='$item'", true);

  // If there is NO message for this item then clean up "swammailto".
  // Delete the item from "swammailto"

  if(!$n) {
    $result3 = $S->query("delete from swammailto where item='$item' and id='$id'");

    print("<h3>No message found. There was an internal error -- Sorry!</h3>\n$footer");
    exit;
  }

  // Get the message

  $row2 = mysql_fetch_row($result2);

  // Get the sendors name from "account"

  $result3 = $S->query("select concat(fname, ' ', lname) as name from swammembers where id='$row2[0]'");

  $row3 = mysql_fetch_row($result3);

//vardump($row, "row");
//vardump($row2, "row2");
//vardump($row3, "row3");

  print("
<p>From: $row3[0]<br>
Date Sent: $row2[1]<br>"
       );
  
  if($mode == 'old') {
    print("Date Read: $readDate<br>\n");
  }
    
  print("
<br>Subject: $row2[3]
<br>Message:<br>$row2[2]</p>\n"
       );

  // If this is a new message let us reply
  // Set mode to 2 and goto "swammail.php"

  if($mode != 'old') {
    echo <<<EOF
<form action="swammail.php" method="post">
<input type="hidden" name="ReplyName" value="$row3[0]">
<input type="hidden" name="ReplyId" value="$row2[0]">                                        
<input type="hidden" name="ReSubject" value='RE: $row2[3]'>
<input type="hidden" name="mode" value="2">
<input type=submit value="Reply">
</form>
EOF;

    // Update "swammailto" to show read date

    $result = $S->query("update swammailto set dateread=now() where id='$id' and item='$item'");
  }

  print("<hr>\n");
} else {
  // ---------------------------------------------------------------------------
  // mailCnt is greater than 1
  // Only show From, Date, Subject and a read button

  while($row = mysql_fetch_row($result)) {
    $item = $row[0];

    if($mode == 'old')
      $readDate = $row[1];

    list($result2, $n) = $S->query("select id, datesent, message, subject from swammailmsg where item='$item'", true);

    // If there is NO message then fix swammailto and continue

    if(!$n) {
      $result3 = $S->query("delete from swammailto where item='$item' and id='$id'");
      continue;
    }

    $row2 = mysql_fetch_row($result2);

    // Get senders name

    $result3 = $S->query("select name from swammembers where id='$row2[0]'");

    $row3 = mysql_fetch_row($result3);

    print("
<p>From: $row3[0]<br>
Date Sent: $row2[1]<br>\n"
         );
  
    if($mode == 'old') {
      print("Date Read: $readDate<br>\n");
    }
      
    // Here mode is set to Read
    // This will call us and we will show the single message

    echo <<<EOF
<br>Subject: $row2[3]
<form action="$S->self" method="post">
<input type="hidden" name="ReadItem" value="$item">
<input type="hidden" name="ReadSender" value="$row3[0]">
<input type="hidden" name="ReadOld" value="$mode">
<input type="hidden" name="mode" value="Read">
<input type="submit" value="Read Message">
</form>
<hr>
EOF;
  }
}  // end of if mailCnt

print("
<p>
<b>NOTE:</b> Use the return link below <u>NOT</u> the <i>Back</i> button.
The <i>Back</i> button will not clear your<b>
You Have SwamMail</b> button on the main page.</p>\n"
     );

if($mode != 'old') 
  print("<p>Would you like to look at your <a href=\"$S->self?mode=old\">Old Mail?</a></p>");
  
echo $footer;
?>
