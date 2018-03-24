<?php
  require("secureinfo/id.phpi");

  $id = CheckAndSetId();
  if(!$id) {
    header("Location: http://www.swam.us/noid.php\n");
    exit();
  }

  function ShowEmailAddress($id) {
    global $PHP_SELF;

    $result = mysql_query("select realEmail, name from account where id=$id");
    
    if(!$result) {
      PhpError("specialnotice.php Error 1: " . mysql_error());
      exit();
    }

    $row = mysql_fetch_row($result);

    print("
<p>$row[1], is this you correct email address: <br>
<form action=\"$PHP_SELF\" method=post>
<input type=text name=emailAddress value=\"$row[0]\"><br>
If this is correct just press 'Submit', if it isn't correct FIX IT<br>
and then press 'Submit'. <br>
<input type=submit>
</form><hr><br>"
    );
  }

  // ---------------------------------------------------------------------------

  function GrantAccess($id, $emailAddress) {
    $result = mysql_query("update account set realEmail='$emailAddress' where id=$id");

    if(!$result) {
      PhpError("specialnotice.php Error 2: " . mysql_error());
      exit();
    }

    // Add id to the specialnotice table

    $result = mysql_query("replace into specialNotice (id) values($id)");

    if(!$result) {
      PhpError("specialnotice.php Error 3: " . mysql_error());
      exit();
    }

    print("
<h2>You will now be automatically notified of special news. We will try very hard to restrict
such notification to things like schedule changes and last minute notifications. 
Please check the web page where we will notify you of events several days in advance.<br><br>
If you want to be removed from this list email <a href='webmaster@applitec.com'>me (Webmaster)</a>
and I will remove you from this service.</h2><br><hr>"
    );
  }
?>
<html>
<head>
<title>Grant Access</title>
<link rel="stylesheet" href="swam.css" type="text/css">
</head>
<body>
<h1 align=center>Grant Access to Your Email Address for Special Notices</h1>
<hr>

<?php
  // Grant special notice to use email address!

  if(!$emailAddress) {
    ShowEmailAddress($id);
  } else {
    GrantAccess($id, $emailAddress);
  }
?>

<br><a href="index-members.php">Return to Swam Home Page</a>
</body>
</html>
