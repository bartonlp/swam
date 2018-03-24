<?php
// This was originally created
// for swam.us the Southwest Aquatic Masters at Pierce College
// Woodland Hills, CA
//
// The BBS application uses two tables:
// The main table is bboard
/*
CREATE TABLE `bboard` (
  `id` int(11) NOT NULL default '0',
  `item` int(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL,
  `bbtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `message` blob,
  `refid` int(11) NOT NULL default '0',
  `secret` char(3) default NULL,
  PRIMARY KEY  (`item`),
  KEY `id` (`id`),
  KEY `refid` (`refid`)
) ENGINE=MyISAM AUTO_INCREMENT=11097 DEFAULT CHARSET=latin1 PACK_KEYS=1
*/
// The items in the bboard table are arranged in threads by the refid field. The first message or NEW message
// has a refid of zero. The next message in the thread will have a refid that is the item number of the first
// message, lather, rise, repeate!
//
// and the secondary table is bbsreadmsg
// This table keeps track of read messages by user id. When a user reads a message an entry is made in
// this table. When we query the bboard table we look at the bbsreadmsg table to see if there is an entry
// for the message item# and the user id. If there is an entry then the message was already read.
/*
CREATE TABLE `bbsreadmsg` (
  `item` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL default '0',
  `msg_date` datetime default NULL,
  PRIMARY KEY  (`item`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1
*/
//
// There is a CRON job that runs each day that deletes old messages. The cron job calls a sql file
// called cullbbs.sql which looks like this:
//-- This is run via barton's crontab
//-- We only want to keep 5 days of bbs messages
//select @deldate:= TO_DAYS(now()) - 5;
//delete from bboard where TO_DAYS(bbtime) < @deldate;
//select date_format(msg_date, '%M %d %Y') from bbsreadmsg;
//
// This will get the date 5 days ago. Then it deletes all items from bboard that are less than that date,
// and from bbsreadmsg. It then displays the remaining item dates.
//
// NOTE: this script does not follow the threads so after the head item is deleted the rest of the read may
// still be in the database (until their date comes). However, the rest of the thread is never displayed
// once the head is removed!

require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$inputtitle = $_GET['title'];

//------------------------------------
// Recursive Function to show postings

function showMessages($refid) {
  global $S, $showAll;

  $result = $S->query("select id, item, title, date_format(bbtime, '%M %d, %Y %H:%i'), message, current_date(), date_format(bbtime, '%Y-%m-%d') from bboard where refid=$refid order by bbtime desc");

  $cnt = mysql_num_rows($result);

  $ulflag = 1;

  while($row = mysql_fetch_row($result)) {
    $msgId = $row[0];
    $msgItem = $row[1];
    $msgTitle = stripslashes($row[2]);
    $msgTime = $row[3];
    $msgMsg = stripslashes($row[4]);
    $curDate = $row[5];
    $msgDate = $row[6];

    $result2 = $S->query("select concat(fname, ' ', lname) from swammembers where id=$msgId");

    list($posterName) = mysql_fetch_row($result2);

    if($msgDate == $curDate)
      $style = 'style="color: red"';
    else
      $style = 'style="color: black"';

    $result3 = $S->query("select * from bbsreadmsg where id=$S->id and item=$msgItem");

    if($showAll == "all") {
      if($ulflag) {
        $ulflag = 0;
        print("<ul>\n");
      }

      print("<li $style>");

      if(mysql_num_rows($result3) == 0) {
        echo "<img src=\"/images/new.gif\" alt='New Post'>";
      }

      print("($msgTime) From: $posterName<br/>Message Title: <a href=\"$S->self?item=$msgItem\">$msgTitle</a></li>\n");
    } else {
    // Not showAll

      if(mysql_num_rows($result3) == 0) {
        if($ulflag) {
          $ulflag = 0;
          print("<ul>\n");
        }

        print("
<li $style>
   <img src='images/new.gif' alt='New Post'>($msgTime) From: $posterName<br/>
   Message Title: <a href='$S->self?item=$msgItem'>$msgTitle</a>
</li>
");
      }
    }
    $cnt += showMessages($msgItem);
  }

  if($ulflag == 0) {
    print("</ul>\n");
  }

  return $cnt;
}
//------------------------------------ End Function showMessages

//------------------------------------
// print out a form for adding a message with
// parent id given

function postForm($refid, $useTitle)  {
  global $email, $name, $S;

  print("
<hr>
<form action='$S->self' method='post'>
   <input type='hidden' name='refid' value='$refid'>
   <input type='hidden' name='action' value='post'>

<center>
<table border='1' bgcolor='#FFFFC0' cellspacing='0' cellpadding='5' width='80%'>
<tr>
   <td width='20%'>
      <b>Title</b>
   </td>

   <td>
      <input style='background-color: #E7FFFF; width: 100%' type='text' name='title' size='80%' value='$useTitle'>
   </td>
</tr>
<tr>
  <td>
    <b>Poster's Name</b>
  </td>
  <td>
    <input style='background-color: #E7FFFF; width: 100%' type='text' name='posterName' disabled='true' size='80%' value='$name'>
  </td>
</tr>
<tr>
  <td colspan='2' align='middle'>
    <textarea style='background-color: #E7FFFF; width: 100%' name='message' rows='7' wrap></textarea>
  </td>
</tr>
<tr>
  <td colspan='2' align='middle'>
    <input type='submit' value='post'>
  </td>
</tr>
</table>
</center>
</form>
"); // end of print
}
//------------------------------------ End of function postForm

//------------------------------------
// Start main code


// Get the POST variables and GET variables

extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

// Do login check and login if needed

if(!$S->id) {
  header("Location: guestreg.php\n\n");
}

$h->title = "Bulletin Board";
$h->extra = '<meta name="robots" content="noindex, nofollow">';
$h->banner = "<h1 style='text-align: center'>Bulletin Board</h1><hr>";
            
$top = $S->getPageTop($h);
                           

// Get the name and email address of this visitor

$result = $S->query("select fname, lname, email from swammembers where id=$S->id");

$row = mysql_fetch_row($result);
$email = $row[2];
$name = "$row[0] $row[1]";

if(!$name) {
  // NO name?

  echo <<<EOF
$top
<h1>DEBUG INFO: 'name' empty?</h1>
<p>This should not heppen as the 'id' was OK?</p>
<p>emai=$email, id=$S->id</p>
<p>Please email <a href='mailto:info@grangyrotary.org'>info@grangyrotary.org</a> and describe this error.</p>
<p><a href='index.php'>RETURN TO HOME PAGE</a></p>
EOF;
      
} else {

  echo $top;
  
  if(isset($mark_as_read)) {
    // Mark all as read

    $result = $S->query("select item, bbtime from bboard");

    while($row = mysql_fetch_row($result)) {
      $msgItem = $row[0];
      $msgPosted = $row[1];

      $S->query("insert ignore into bbsreadmsg (item, id, msg_date) values($msgItem, $S->id, '$msgPosted')");
    }
    $result = $S->query("select max(item) from bboard");
    list($last) = mysql_fetch_row($result);
    //echo "Lastmsg=$last<br>";
    $S->query("update swammembers set lastmsg='$last' where id='$S->id'");

  }

  $showAll = "new"; // Flag to tell showMessages() to show all or just new.

  if(isset($action) && $action == "post") {
    // Process the form we were sent.

    // Must have a title!

    if(!$title) {
      print("<p style='color: red;'>You need a <b>Title</b> for this post</p>");
      exit();
    }

    // And of course a MESSAGE!

    if(!$message) {
      print("<p style='color: red;'>You didn't supply a message</p>");
      exit(); 
    }

    // Get rid of those peskie ' etc by making them \'

    $title = addslashes($title);
    $message = addslashes($message);

    // Create our query to insert the new post

    $S->query("insert into bboard (id, title, message, refid) values($S->id, '$title', '$message', $refid)");

    print("
<h1>Message Posted</h1>
<a href='$S->self'>Return to List</a>
");

    exit();

  } elseif(isset($action) && $action == "show_all") {
    $showAll = "all";
  } elseif(isset($action) && $action == "editmsg") {
    // Edit a message posted by this user

    $editMsg = rawurldecode($editMsg);

    // Display the form and let user edit it

    print("
<form method=post action='$S->self'>
   <textarea style='background-color: #E7FFFF;' name='message' cols='80' rows='7' wrap>$editMsg</textarea>
   <input type='hidden' name='updatemsg' value='$edititem'>
   <input type='hidden' name='action' value='updatemsg'>
   <input type='submit' value='Make Changes'>
");                                   
    exit();
  } elseif(isset($action) && $action == 'updatemsg') {
    $message = addslashes($message);

    $S->query("update bboard set message='$message' where item=$updatemsg");

    echo "<h1>Message Updated</h1>";
    echo "<a href='$S->self'>Return to List</a>";
    exit();
  } // END of if actions

// Show Message or show list of messages
// If item set then show a specific message.

  if(isset($item)) {
    // Show a specific message

    $result = $S->query("select id, date_format(bbtime, '%M %d, %Y %H:%i') as bbtime, title, message, refid, bbtime as posttime " .
                        "from bboard where item='$item'");

    if($row = mysql_fetch_array($result))   {
      $msgId = $row['id']; // id
      $msgTime = $row['bbtime']; // bbtime formated
      $msgTitle = stripslashes($row['title']);  // title
      $msgMsg = stripslashes($row['message']); // message
      $msgRedif = $row['rfid']; // refid
      $msgPosted = $row['posttime']; // bbtime unformated as posttime

      // add to bbsreadmsg and we don't care if it is already therre
      // Ignore dup error

      $S->query("insert ignore into bbsreadmsg (item, id, msg_date) values($item, $S->id, '$msgPosted')");

      $result1 = $S->query("select fname, lname from swammembers where id=$msgId");

      $row = mysql_fetch_row($result1);
      $posterName = "$row[0] $row[1]";

      print("
<a href='$S->self'>Return to the List of Messages</a><br/>
<table border='1' cellspacing='0' cellpadding='10' width='100%' cellpadding='5' width='400' bgcolor='#FFE7F2'>
<tr>
   <td><b>Title</b></td>
<td>
");

      if($msgRedif != 0) {
        print("
<form action='$S->self' method='post'>
   <input type='hidden' name='item' value='$msgRedif'>
   <input type='submit' value='Read Previous Message'>&nbsp;[Use BACK to return to this post]
</form>\n
");
      }

      print("
$msgTitle</td>
</tr>
<tr>
   <td><b>Poster</b></td>
   <td>$posterName</td>
</tr>
<tr>
   <td><b>Posted</b></td>
   <td>$msgTime</td>
</tr>
<tr>
   <td><b>Message</b></td>
   <td>$msgMsg</td>
</tr>
</table><br/>\n
");
      
      // If the ID matches the visitor's ID then he can edit his post

      if($S->id == $msgId) {
        $editMsg = rawurlencode($msgMsg);

        print("
<form action='$S->self' method='post'>
   <input type='hidden' name='edititem' value='$item'>
   <input type='hidden' name='editMsg' value='$editMsg'>
   <input type='hidden' name='action' value='editmsg'>
   <input type='submit' value='Edit Entry'>
</form><br/>
");
      }

      postForm($item, "RE: $msgTitle");
    }

    print("<br/><a href='$S->self'>Return to the List of Messages</a>\n");

    $result = $S->query("select max(item) from bboard");
    list($last) = mysql_fetch_row($result);
    //echo "Lastmsg=$last<br>";
    $S->query("update swammembers set lastmsg='$last' where id='$S->id'");
     
  } else {
    // Show the full list of messages indented etc.

    $showMsg = "List of Messages (newest to oldest) Todays Messages In Red.";

    print("<form action='$S->self' method='post'>\n");


    if($showAll == "new") {
      echo <<<EOF
<h2>$showMsg<br/>Showing New Messages Only</h2>
<input type='hidden' name='action' value='show_all'>
<input type='submit' value='Show All Posts'>
EOF;
    } else {
      echo <<<EOF
<h2>$showMsg<br/>Showing All Messages</h2>
<input type='submit' value='Show Only New Post'>
EOF;
    }
    print("</form>\n");

    // Mark all messages as read

    echo <<<EOF
<form action='$S->self' method='post'>
   <input type='hidden' name='mark_as_read' value='true'>
   <input type='submit' value='Mark Posts as Read'>
</form>
EOF;
    
    // get entire list

    $numPosts = showMessages(0);

    if($numPosts == 0) {
      print("<h3 style='color: red;'>NO MESSAGES AT THIS TIME</h3>\n");
    } else {
      print("<h3>There are $numPosts posts in all.</h3>\n");
    }
    postForm(0, $inputtitle);

  }
   print("<a href=howtowritehtml.php>How to Use HTML in Bulletin Board Messages</a>");
   print("<p>Messages will automatically be deleted about one week from the time they are posted.</p>\n");
}

$footer = $S->getFooter();

echo <<<EOF
<hr>
$footer
EOF;
  
?>
