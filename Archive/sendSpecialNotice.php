<?php
  require("secureinfo/id.phpi");

  define("FREDS_ID", 50);
  define("BARTONS_ID", 192);
  define("ZUPONS_ID", 58);
  $id = CheckAndSetId();
  if(!(($id == FREDS_ID) || ($id == BARTONS_ID))) {
    // go away!
    header("Location: http://www.swam.us/index-members.php\n");
    exit();
  }

  // ---------------------------------------------------------------------------

  function GetInfo($id) {
    global $PHP_SELF;

    $result = mysql_query("select specialNotice.id, account.name from specialNotice, account where specialNotice.id = account.id");
    if(!$result) {
      PhpError("sendSpecialNotice.php Error 2: " . mysql_error());
      exit();
    }

    print("<p>These are the people who have signed up for Special Notice:</p>\n");

    while($row = mysql_fetch_row($result)) {
      print("<li>$row[1]</li>\n");
    }

    print("
<hr>
<form action=\"$PHP_SELF\" method=post>
Enter message to be sent:<br>
<TEXTAREA NAME=message ROWS=5 COLS=45> </TEXTAREA>
<br>
<input type=submit>&nbsp;<input type=reset>
</form>\n"
    );
  }

  // ---------------------------------------------------------------------------

  function PostMail($id, $message) {
    // Get all the people we need to send to

    $result = mysql_query("select * from specialNotice");

    if(!$result) {
      PhpError("sendSpecialNotice.php Error 1: " . mysql_error());
      exit();
    }

    // Get each email address

    $sendTo = '';

    while($row = mysql_fetch_row($result)) {
      $result2 = mysql_query("select realEmail from account where id=$row[0]");
  
      if(!$result2) {
        PhpError("specialnotice.php Error 2: " . mysql_error());
        exit();
      }

      // reuse $row it will be reset at top of loop

      $row = mysql_fetch_row($result2);

      $sendto .= "$row[0], ";
    }

    // for debug
    //$sendto = "barton@bartonphillips.com, pcguest@humphrey.applitec.com, admin@applitec.com,";

    mail("admin@applitec.com", "South West Aquatic Masters Special Notice", $message, 
      "From: \"Coach Fred\" <SwamFred@applitec.com>\r\nbcc: $sendto");  

    print("<p>Message sent</p>\n");
  }
?>

<html>
<head>
<title>Send Special Notice</title>
<link rel="stylesheet" href="swam.css" type="text/css">
</head>
<body>
<h1 align=center>Send Special Notices</h1>
<hr>

<?php

  if(!$message) {
    GetInfo($id);
  } else {
    PostMail($id, $message);
  }
?>

<a href="index-members.php">Return to Swam Home Page</a>
</body>
</html>
