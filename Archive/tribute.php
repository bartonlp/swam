<?php
// The main table is bboard
//+---------+---------------+------+-----+---------+----------------+
//| Field   | Type          | Null | Key | Default | Extra          | Description
//+---------+---------------+------+-----+---------+----------------+------------
//| id      | int(11)       |      | MUL | 0       |                | id from account
//| item    | int(11)       |      | PRI | NULL    | auto_increment | unique message id
//| title   | varchar(200)  | YES  |     | NULL    |                | message title
//| bbtime  | timestamp(14) | YES  |     | NULL    |                | time the item was posted
//| message | blob          | YES  |     | NULL    |                | the message
//| refid   | int(11)       |      | MUL | 0       |                | the item# of the refering msg or 0 if head
//| secret  | char(3)       | YES  |     | NULL    |                | if set then this is SWAM only message
//+---------+---------------+------+-----+---------+----------------+
//
// The items in the bboard table are arranged in threads by the refid field. The first message or NEW message
// has a refid of zero. The next message in the thread will have a refid that is the item number of the first
// message, lather, rise, repeate!
//

require("secureinfo/id.phpi");

$id = CheckId();

  // Get the name and email address of this visitor

  $result = mysql_query("select name, email from account where id=$id", $db);

  if(!$result) {
    echo "<h1>Error 1: ", mysql_error($db) , "</h1>";
    exit();
  }

  $row = mysql_fetch_row($result);
  $email = $row[1];
  $name = $row[0];

  if(!$name) {
    // NO name so not registered. Go to registration

    header("Location: http://www.swam.us/needtoreg.php\n");
    exit();
  }

  $result = mysql_query("select max(item) from bboard", $db);

  if(!$result) {
    echo "<h1>Error 2: ", mysql_error($db) , "</h1>";
    exit();
  }
  $row = mysql_fetch_row($result);

  $result = mysql_query("update account set lastmsg=$row[0] where id=$id", $db);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Author" content="John Zupon, mailto:john@zupons.net">
<meta name="Description" content="South West Aquatic Master Swimming at Pierce College. A fun coached Masters swimming workout plus Masters swimming competition.">
<meta name="KeyWords" content="Swimming, Masters swimming, Swim Meets, Sports, Athletics, Swimming Coaching, Swimming Competition">

<link rel="stylesheet" href="swam.css" type="text/css">

<title>A Tribute to Steve Schofield</title>
</head>
<body>

<!-- DON'T USE STANDARD HEADER SO WE SEE MORE MESSAGES ON THE SCREEN -->
<h1 class="center">You Can Add Your Tribute to <br>Steve Schofield Here</h1>


<?php
  //------------------------------------
  // Recursive Function to show postings

  function showMessages($refid) {
    global $db, $PHP_SELF, $id, $showAll, $SwamSecret;

    $query = "select id, item, title, date_format(bbtime, '%M %d, %Y %H:%i'), message, current_date(), date_format(bbtime, '%Y-%m-%d'), secret from bboard where refid=$refid order by bbtime desc";

    $result = mysql_query($query, $db);

    if(!$result) {
      echo "<h1>Error 3: ", mysql_error($db) , "</h1>";
      exit();
    }

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
      $bbsSecret = $row[7];

      if($bbsSecret == "on" && $SwamSecret != 'yes')
        continue;

      $result2 = mysql_query("select name from account where id=$msgId", $db);

      if(!$result) {
        echo "<h1>Error 4: ", mysql_error($db) , "</h1>";
        exit();
      }

      $row1 = mysql_fetch_row($result2);

      $posterName = $row1[0];

      if($msgDate == $curDate)
        $style = 'style="color: red"';
      else
        $style = 'style="color: black"';

      $result3 = mysql_query("select * from bbsreadmsg where id=$id and item=$msgItem");

      if(!$result3) {
        echo "<h1>Error 4a: ", mysql_error($db), "</h1>";
        exit();
      }

      //echo "DEBUG::$posterName, $msgItem, $msgTitle<br>";

      if($showAll == "all") {
        if($ulflag) {
          $ulflag = 0;
          print("<ul>\n");
        }

        echo "<li $style>";

//        if($bbsSecret == "on") {
//          echo "[swam only] ";
//        }

        if(mysql_num_rows($result3) == 0) {
          echo "<img src=\"/images/new.gif\" alt=\"\">";
        }

        echo "($msgTime) From: $posterName<br>Message Title: <a href=\"$PHP_SELF?item=$msgItem\">$msgTitle</a></li>\n";
      } else {
        // Not showAll

        //$blp = mysql_num_rows($result3);
        //echo "DEBUG::mysql_num_rows=$blp<br>";

        if(mysql_num_rows($result3) == 0) {
          if($ulflag) {
            $ulflag = 0;
            print("<ul>\n");
          }

          echo "<li $style>";

//          if($bbsSecret == "on") {
//            echo "[swam only] ";
//          }

          echo "<img src=\"images/new.gif\" alt=\"\">($msgTime) From: $posterName<br>Message Title: <a href=\"$PHP_SELF?item=$msgItem\">$msgTitle</a></li>\n";
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
    global $email, $name, $id, $PHP_SELF, $SwamSecret;

    print("
<hr>
<form action=\"$PHP_SELF\" method=post>
<input type=hidden name=refid value=$refid>
<input type=hidden name=action value=post>

<center>
<table border=1 bgcolor=#FFFFC0 cellspacing=0 cellpadding=5 width=80%>
<!--<tr>
  <td width=20%>
    <b>Title</b>
  </td>

  <td>
    <input style=\"background-color: #E7FFFF;\" type=text name=title size=80% value=\"$useTitle\">
  </td>
</tr>-->
<tr>
  <td>
    <b>Poster's Name</b>
  </td>
  <td>
    <input style=\"background-color: #E7FFFF;\" type=text name=posterName size=80% value=\"$name\">
  </td>
</tr>

<!--<tr>
  <td>
    <b>Poster's E-mail</b>
  </td>
  <td>
    <input style=\"background-color: #E7FFFF;\" type=text name=email size=80% value=\"$email\">
  </td>
<tr> -->

  <td colspan=2 align=middle>
    <textarea style=\"background-color: #E7FFFF;\" name=message cols=80 rows=7 wrap></textarea>
  </td>
</tr>
<tr>
  <td colspan=2 align=middle>\n
   "); // end of print

//    if($SwamSecret == "yes")
//        print("Swam Team Only<input type=checkbox name=secret>&nbsp;&nbsp;&nbsp;");

    print("
    <input type=submit value=post>
  </td>
</tr>
</table>
</center>
</form>

<p><a href=\"spellcheck.html\"><b>Spelling Checker</b></a><br>
         </p>\n
       "); // end of print
  }
  //------------------------------------ End of function postForm

//------------------------------------
// Start main code

//if(isset($mark_as_read)) {
        // Mark all as read

//        $result = mysql_query("select item, bbtime from bboard");
//        if(!$result) {
//                echo "<h1>Error: mark as read: ", mysql_error($db), "</h1>";
//                exit();
//        }

//        while($row = mysql_fetch_row($result)) {
//                $msgItem = $row[0];
//                $msgPosted = $row[1];

//                mysql_query("insert into bbsreadmsg (item, id, msg_date) values($msgItem, $id, '$msgPosted')");
//        }
//}

//$showAll = "new"; // Flag to tell showMessages() to show all or just new.

// If we were passed change
// Then update the account with the new name

if(isset($change)) {
  // Update account
  // change has the ID and okay say to either use new name or NOT

  if($okay == 'yes') $name = $newname;

  $result = mysql_query("update account set name='$name', email ='$email' where id=$change", $db);

  if(!$result) {
    echo "<h1>Error 5: ", mysql_error($db) , "</h1>";
    exit();
  }
} elseif(isset($action) && $action == "post") {
  // Process the form we were sent.

  // First check to make sure we have name and email. If not ERROR

  if(!$posterName || !$email) {
    echo "<font size=+2 color=red>You <u>MUST</u> supply both your name and email</font>";
    exit();
  }

  // Must have a title!

//  if(!$title) {
//    echo "<font size=+2 color=red>You need a <b>Title</b> for this post</font>";
//    exit();
//  }

  // And of course a MESSAGE!

  if(!$message) {
    echo "<font size=+2 color=red>You didn't supply a message</font>";
    exit();
  }

  // Get rid of those peskie ' etc by making them \'
   $title = Tribute_to_Steve;
//  $title = addslashes($title);
  $message = addslashes($message);

  // Create our query to insert the new post

  $result = mysql_query("insert into bboard (id, title, message, refid, secret) values($id, '$title', '$message', $refid, '$secret')", $db);
  if(!$result) {
    echo "<h1>Error 6: ", mysql_error($db) , "</h1>";
    exit();
  }

  // Check to see if the name in the database is the same as the name used.
  // That is did the visitor CHANGE his NAME?

  if($name && (strtolower($posterName) != strtolower($name))) {
    // NAME CHANGED, so let visitor select if he wants to change his
    // name in the database or NOT?

    echo "<font color=red>The name you just entered <i>", ucwords($posterName),
      "</i> is not the same as the name in the database ",
      "(<i>$name</i>) do you want to change the database?</font>";

    // Create a form and set value "change"

    echo "<form method=post action=\"$PHP_SELF\">";
    echo "<input type=hidden name=change value=$id>";
    echo "<input type=hidden name=name value=\"$name\">";
    echo "<input type=hidden name=newname value=\"", ucwords($posterName), "\">";
    echo "<input type=hidden name=email value=$email>";
    echo "YES <input type=radio name=okay value='yes'> NO <input type=radio name=okay value='no' checked>";
    echo "<br><input type=submit>";
    exit();
  }

  // Update account if no change of name.

  $result = mysql_query("update account set name='$name', email='$email' where id=$id", $db);

  if(!$result) {
    echo "<h1>Error 7: ", mysql_error($db) , "</h1>";
    exit();
  }
} elseif(isset($action) && $action == "show_all") {
  $showAll = "all";
} elseif(isset($edititem)) {
  $editMsg = rawurldecode($editMsg);
  echo "<form method=post action=\"$PHP_SELF\">";
  echo "<textarea style=\"background-color: #E7FFFF;\" name=message cols=80 rows=7 wrap>$editMsg</textarea>";
  echo "<input type=hidden name=updatemsg value=$edititem>";
  echo "<input type=submit value=\"Make Changes\">";
  exit();
} elseif(isset($updatemsg)) {
  $message = addslashes($message);

  $result = mysql_query("update bboard set message='$message' where item=$updatemsg", $db);

  if(!$result) {
    echo "<h1>Error 6: ", mysql_error($db) , "</h1>";
    exit();
  }

  echo "<h1>Message Updated</h1>";
  echo "<a href=\"$PHP_SELF\">Return to List</a>";
  exit();
}

// Show Message or show list of messages
// If item set then show a specific message.

if(isset($item)) {
  // Show a specific message

  $query = "select id, date_format(bbtime, '%M %d, %Y %H:%i') as bbtime, title, message, refid, bbtime as posttime from bboard where item=$item ";

  $result = mysql_query($query, $db);

  if(!$result) {
    echo "<h1>Error 8: ", mysql_error($db) , "</h1>";
    exit();
  }

  if($row = mysql_fetch_row($result))   {
    $msgId = $row[0];
    $msgTime = $row[1];
    $msgTitle = stripslashes($row[2]);
    $msgMsg = stripslashes($row[3]);
    $msgRedif = $row[4];
    $msgPosted = $row[5];

    // add to bbsreadmsg and we don't care if it is already therre

    // echo "DEBUG::item=$item, id=$id, msgPosted=$msgPosted<br>";

    $result1 = mysql_query("insert into bbsreadmsg (item, id, msg_date) values($item, $id, '$msgPosted')");

    // Don't report duplicate key errors (1062)

    if(!$result1 && (mysql_errno($db) != 1062)) {
      echo "<h1>Error 8a: ", mysql_error($db), "</h1>";
    }

    $result1 = mysql_query("select name from account where id=$msgId", $db);

    if(!$result1) {
      echo "<h1>Error 9: ", mysql_error($db) , "</h1>";
      exit();
    }

    $row = mysql_fetch_row($result1);
    $posterName = $row[0];


    print("
<a href=\"$PHP_SELF\">Return to the List of Messages</a><br>
<table border=1 cellspacing=0 cellpadding=10 width=100% cellpadding=5 width=400 bgcolor=#FFE7F2>

<tr>
<td><b>Title</b></td>
<td>
    ");

    if($msgRedif != 0) {
      print("
<form action=\"$PHP_SELF\" method=post>
<input type=hidden name=item value=\"$msgRedif\">
<input type=submit value=\"Read Previous Message\"> &nbsp; [Use BACK to return to this post]
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
</table><br>\n
   ");

    // If the ID matches the visitor's ID then he can edit his post

    if($id == $msgId) {
      print("
<form action=\"$PHP_SELF\" method=post>
<input type=hidden name=edititem value=$item>\n
     ");

      $editMsg = rawurlencode($msgMsg);
      print("
<input type=hidden name=editMsg value='$editMsg'>
<input type=submit value=\"Edit Entry\">
</form><br>\n
     ");
    }

    postForm($item, "RE: $msgTitle");
  }

  print("<br><a href=\"$PHP_SELF\">Return to the List of Messages</a>\n");

} else {
//      Show the full list of messages indented etc.

  $showMsg = "List of Messages (newest to oldest) Todays Messages In Red";

  print("<form action=\"$PHP_SELF\" method=post>\n");


//  if($showAll == "new") {
//    print("<h2>$showMsg<br>Showing New Messages Only</h2>\n");
//    print("<input type=hidden name=action value=show_all>\n<input type=submit value=\"Show All Posts\">\n");
//  } else {
//    print("<h2>$showMsg<br>Showing All Messages</h2>\n");
//    print("<input type=submit value=\"Show Only New Posts\">\n");
//  }
//  print("</form>\n");

  // Mark all messages as read

//  print("<form action='$PHP_SELF' method='post'>\n".
//        "<input type='hidden' name='mark_as_read' value='true'>\n".
//        "<input type='submit' value='Mark Posts as Read'>\n".
//        "</form>\n");

  // get entire list

//  $numPosts = showMessages(0);

//  if($numPosts == 0) {
//    print("<h3><font color=red>NO MESSAGES AT THIS TIME</font></h3>\n");
//  } else {
//    print("<h3>There are $numPosts posts in all.</h3>\n");
//  }
  postForm(0, "");
}
?>
<hr>
<p>I am using the Bulletin Board as a way for you to post your tribute to Steve. Your tribute will be posted on the Bulletion Board with a title Tribute to Steve. I will monitor your posts
and copy them to this file within 24 hours.</p>

<p>You <u>must</u> provide your email address and your name;
however, your email address will only be used for administrative purposes.</p>



