<?php
// This file handles readswammail and swammail, that is read and send swammail

require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case 'post':
        // post swam mail
        post($S);
        break;
      case 'composemessage':
        // compose the message to be sent
        composemessage($S);
        break;
      case 'showmessage':
        ShowMessage($S);
        break;
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
    }
    break;
  case "GET":
    switch($_GET['page']) {
      case 'readmail':
        // read swam mail
        readmail($S);
        break;
      case 'sendmail':
        // send swam mail
        sendmail($S);
        break;
      case 'old':
        readmail($S);
        break;
      case 'mine':
        ShowMySends($S);
        break;
      default:
        throw(new Exception("default get"));
        break;
    }
    break;
  default:
    // Main page
    throw(new Exception("Not GET or POST: {$_SERVER['REQUEST_METHOD']}"));
    break;
}

//  ---------------------------------------------------------------------------
// GET send mail

function sendmail($S) {
  // Start the form.

  $h->title = "South West Aquatic Masters Send SwamMail";
  $h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br>Send Swam Mail</h1><hr>";

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  extract($_GET);

  print("<form action='$S->self' method='post'>\n");

  // Get the name and email address of this visitor
  // Check how we sort the list of names.

  if($sortOnFirst) {
    // Sort by first name

    $result = $S->query("select concat(fname, ' ', lname) as name, id, " .
                        "team from swammembers where code is null order by fname");
    } else {
      // sort on LAST NAME is the default

      $result = $S->query("select concat(fname, ' ', lname) as name, id, team " .
                          "from swammembers where code is null order by lname");
    }

  echo <<<EOF
$S->top
<p>View the mail you have <a href="$S->self?page=mine">sent</a></p>
<p>Select your recipient or recipients, then click on <b>Compose Your Message</b> at the bottom of the page.</p>
<form action="$S->self" method="post">
EOF;

  // How should names be sorted? 

  if($sortOnFirst) {
    // just call page again. Last name is default.

    print("<h3>Sort on <a href='$S->self?page=sendmail'>Last Name</a> instead</h3>");
  } else {
    // call page with sortOnFirst set.

    print("<h3>Sort on <a href='$S->self?page=sendmail&sortOnFirst=1'>First Name</a> instead</h3>");
  }

  // Now get each name from database

  while($row = mysql_fetch_assoc($result)) {
    extract($row);

    // Make a checkbox for each name.

    print("<input type=checkbox name='Name[]' value='$id'> $name");

    // And show the team they are frrom

    if($team != "") {
      print(" ($team)<br>\n");
    } else {
      print("<br>\n");
    }
  }
  echo <<<EOF
<input type="submit" value="Compose Your Message"/>
<input type="hidden" name="page" value="composemessage"/>
</form>
$S->footer
EOF;
}

//  ---------------------------------------------------------------------------
// Compose the message via a form.
// This is called from sendmail() and from several read message reply forms.
// If called from sendmail() then there can be several member IDs in the $Name array.

function composemessage($S) {
  $h->title = "South West Aquatic Masters Compose SwamMail";
  $h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br>Compose Swam Mail</h1><hr>";

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  //vardump($_POST, "post");

  extract($_POST);

  $idlist = implode(",", $Name);
  
  echo <<<EOF
$S->top
<form action="$S->self" method="post">
Reply to: $ReplyName<br>
<br>
Enter Subject: <input type='text' name='mail_subject' size='70' value="$ReSubject"><br>
Enter Message:<br>
<textarea name='mail_msg' rows='10' cols='80'></textarea><br>
<input type='hidden' name='page' value='post'>
<input type='hidden' name='idlist' value='$idlist'>
<br>
<input type='submit' value='Submit'><input type='reset' value='Reset'><br>
</form>

<h3>You can read your <a href=\"readswammail.php?page=old\">old</a> SwamMail</h3>\n
$S->footer
EOF;

}

//  ---------------------------------------------------------------------------
// $idlist is the people to sendto

function post($S) {
  extract($_POST);

  $h->title = "South West Aquatic Masters Post SwamMail";
  $h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br>Post Swam Mail</h1><hr>";

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  $Name = explode(",", $idlist);

  // vardump($Name, "Name");
  
  // $Name is an array so check the number of elements
  // If post() is from sendmail() then there could be several member ID's in $Name.
  // If called from one of the reply forms then there is only one ID and that is the ID of the original poster.
  
  if(count($Name) == 0) {
    echo <<<EOF
$S->top
<h1>Error: No Recipients Checked</h1>
$S->footer
EOF;
    exit();
  }

  // There must be a message otherwise why send it?

  if(!$mail_msg || $mail_msg == "") {
    echo <<<EOF
$S->top
<h1>Error: No Message Text</h1>
$S->footer
EOF;
    exit();
  }

  // Put the message into the database

  $mail_msg = $S->escape($mail_msg);
  $mail_subject = $S->escape($subject);

  $result = $S->query("insert into swammailmsg (id, datesent, message, subject) ".
                      "values('$S->id', now(), '$mail_msg', '$mail_subject')");

  // Get the item number we just inserted

  $newItem = mysql_insert_id($S->db);

  // $Name is an array and could have multiple IDs if post() was called via sendmail().
  
  foreach($Name as $name) {
    $result = $S->query("insert into swammailto (item, id) values('$newItem', '$name')");
  }

/*  
  echo "swammailmsg: id=$S->id, datesent=now(), message=$mail_msg, subject=$mail_subject<br>";
  foreach($Name as $name) {
    ++$newItem;
    echo "swammailto: item=$newItem, id=$name<br>";
  }
*/
  echo <<<EOF
$S->top
<h1>Your Mail Was Sent</h1>
$S->footer
EOF;
}

//  ---------------------------------------------------------------------------
// The following are for readswammail

function ShowMessage($S) {
  extract($_POST);

  $h->title = "South West Aquatic Masters Show SwamMail";
  $h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br>Show Swam Mail</h1><hr>";

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  // Get the message from "swammailmsg" for $item

  $result = $S->query("select sm.datesent, sm.message, sm.subject, sm.id as senderid, concat(s.fname, ' ', s.lname) as sender " .
                        "from swammailmsg as sm ".
                        "left join swammembers as s on s.id=sm.id where item='$item' and s.id=sm.id");

  $row = mysql_fetch_assoc($result);
  extract($row);

  //echo "ShowMessage, senderid=$senderid, item=$item, datesent=$datesent, subject=$subject, message=$message, sender=$sender, page=$page<br>";
    
  // Get the message from "swammailmsg" for $item
  // Display the message

  echo <<<EOF
$S->top
<p>From: $sender<br>
Date Sent: $datesent<br><br>
Subject: $subject<br>
Message:<br>
$message</p>
EOF;

  // We can Reply to new messages but not to Old ones!
  // For OLD messages we do not put a Reply button up and don't update the 
  // database!

  if($old != 'old') {
    echo <<<EOF
<form action="$S->self" method="post">
<input type='hidden' name="ReplyName" value="$sender">
<input type='hidden' name="Name[]" value="$senderid">     
<input type='hidden' name="page" value="composemessage">
<input type='hidden' name='item' value='$item'>
<input type='hidden' name='ReSubject' value="RE: $subject">
<input type=submit value="Reply">
</form>
EOF;
  
    // For new messages update the "swammailto" with the current date to show
    // the message has been read!

    list($result, $n) = $S->query("update swammailto set dateread=now() where id='$S->id' and item='$item'", true);
    //echo "senderid=$senderid, myid=$S->id, item=$item, update=$n<br>";
  }
  $b->w3cval = "<br><a href='$S->self?page=readmail'>Return to Mail List</a><hr>";
  $footer = $S->getFooter($b);
  echo $footer;
}

// ---------------------------------------------------------------------------

function ShowMySends($S) {
  $h->title = "South West Aquatic Masters SwamMail I Sent";
  $h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br> Swam Mail I Sent</h1><hr>";
  $b->w3cval = "<br><a href='$S->self?page=readmail'>Return to Mail List</a><hr>";
  list($S->top, $S->footer) = $S->getPageTopBottom($h, $b);

  // Get all of my messages from "swammailmsg"

  $result = $S->query("select item, message, datesent, subject from swammailmsg where id='$S->id'");

  // Loop through all messages

  echo $S->top;

  while($row = mysql_fetch_assoc($result)) {
    extract($row);

    if(!$subject || ($subject == "")) $subject = "NO SUBJECT";

    // Who was this sent to and have they read the message?

    list($result1, $n) = $S->query("select t.id, dateread, concat(fname, ' ', lname) as name from swammailto as t ".
                                     "left join swammembers as s on t.id=s.id where item='$item' and t.id=s.id", true);

    print("Sent: $datesent<br>To: ");
    if($n > 1) echo "<br>\n";

    // Loop through all the people the message was sent to

    while(list($toId, $dateRead, $toName) = mysql_fetch_row($result1)) {
      if($n > 1) echo "&nbsp;&nbsp;&nbsp;";

      // Show either read date or UNREAD

      if($dateRead) {
        echo "$toName: Read ($dateRead)";
      } else {
        echo "<span class=red>$toName: UNREAD</span>";
      }
      echo "<br>\n";
    }
      
    echo <<<EOF
Subject: $subject
<br>Message:<p>$message</p><hr>
EOF;
  }
  echo $S->footer;
}  

//  ---------------------------------------------------------------------------
// Read Swam Mail. This is a GET

function readmail($S) {
  $h->title = "South West Aquatic Masters Read SwamMail";
  $h->banner = "<h1 style='text-align: center'>South West Aquatic Masters<br>Read Swam Mail</h1><hr>";

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  extract($_GET); // id and page

  if($page == 'old') {
    list($result, $n) = $S->query("select item, dateread from swammailto where id='$S->id' and dateread is not null", true);
  } else {
    list($result, $n) = $S->query("select item, dateread from swammailto where id='$S->id' and dateread is null", true);
  }

  echo $S->top;
  //echo "page=$page, n=$n<br>";

  if(!$n) {
    echo <<<EOF
<h1>No New Mail</h1>
EOF;
  } elseif($n == 1) {
    // ONLY one swammail so we can show it and the reply all on this page

    print("<h2 align=center>Mail Count: $mailCnt</h2>");

    list($item, $readDate) = mysql_fetch_row($result);

    //echo "n=1, item=$item, date=$readDate<br>";
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

    $row2 = mysql_fetch_assoc($result2);

    // Get the sendors name from "account"

    $result3 = $S->query("select concat(fname, ' ', lname) as name from swammembers where id='{$row2['id']}'");

    list($fromname) = mysql_fetch_row($result3);

    echo <<<EOF
<p>From: $fromname<br>
Date Sent: {$row2['datesent']}<br>
EOF;
  
    if($page == 'old') {
      print("Date Read: $readDate<br>\n");
    }
    
    echo <<<EOF
<br>Subject: {$row2['subject']}
<br>Message:<br>{$row2['message']}
EOF;

    // If this is a new message let us reply

    if($page != 'old') {
      echo <<<EOF
<form action="$S->self" method="post">
<input type="hidden" name="ReplyName" value="$fromname">
<input type="hidden" name="Name[]" value="{$row2['id']}">                                        
<input type="hidden" name="ReSubject" value='RE: {$row2['subject']}'>
<input type="hidden" name="item" value='$item'>
<input type="hidden" name="page" value="composemessage">
<input type=submit value="Reply">
</form>
EOF;

      // Update "swammailto" to show read date

      list($x, $n) = $S->query("update swammailto set dateread=now() where id='$S->id' and item='$item'", true);
      //echo "$S->id, item=$item, update=$n<br>";
    }

    print("<hr>\n");
  } else {
    // $n is greater than 1
    // Only show From, Date, Subject and a read button

    while($row = mysql_fetch_assoc($result)) {
      extract($row);
      //echo "item=$item, time=$dateread<br>";

      list($result2, $n) = $S->query("select id, datesent, message, subject from swammailmsg where item='$item'", true);

      // If there is NO message then fix swammailto and continue

      if(!$n) {
        $S->query("delete from swammailto where item='$item' and id='$S->id'");
        continue;
      }

      $row2 = mysql_fetch_assoc($result2);

      // Get senders name

      $result3 = $S->query("select concat(fname, ' ', lname) as name from swammembers where id='{$row2['id']}'");

      list($fromname) = mysql_fetch_row($result3);

      echo <<<EOF
<p>From: $fromname<br>
Date Sent: {$row2['datesent']}<br>
EOF;

      if($page == 'old') {
        print("Date Read: $dateread<br>\n");
      }
      
      // Here page is set to Read
      // This will call us and we will show the single message

      echo <<<EOF
<br>Subject: {$row2['subject']}
<form action="$S->self" method="post">
<input type="hidden" name="item" value="$item">
<input type="hidden" name="ReadSender" value="$fromname">
<input type="hidden" name="old" value="$page">
<input type="hidden" name="page" value="showmessage">
<input type="submit" value="Read Message">
</form>
<hr>
EOF;
    }
  } 

  echo <<<EOF
<p>
<b>NOTE:</b> Use the return link below <u>NOT</u> the <i>Back</i> button.
The <i>Back</i> button will not clear your<b>
You Have SwamMail</b> button on the main page.</p>
EOF;

  if($page != 'old') 
    print("<p>Would you like to look at your <a href='$S->self?page=old'>Old Mail?</a></p>");
  
  echo $S->footer;
}

  
?>
