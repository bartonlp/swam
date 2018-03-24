<?php
require("secureinfo/id.phpi");

// Visitor needs to register before he/she can use services!

// If we had either an ID or an EMAIL we process a FORM
// Otherwise show the FORM

if(($id = $_GET['id']) || $_POST['email']) {
  if($id) {
    // $id is the ID we want to revitalize
    // Get the name associated with this ID and set the
    // COOKIE
    // Get rid of the new ID that was created.
    // MAKE SURE THAT THE OLD AND NEW ID's are not the same!

    $oldid = CheckId("no_inc");
 
    $result = mysql_query("select name, email from account where id=$id", $db);

    if(!$result) {
      PhpError("needtoreg Error 1: " . mysql_error($db));
      exit();
    }

    $row = mysql_fetch_row($result);

    if(!$row) {
      echo "Can't Find Entry for id=$id?<br>";
      exit();
    }
 
    SetIdCookie($id);

    // Make sure we don't delete ourselves!!!

    if($oldid != $id) {
      $result = mysql_query("delete from account where id=$oldid", $db);

      if(!$result) {
        PhpError("needtoreg Error 2: " . mysql_error($db));
        exit();
      }
    }

    $name = $row[0];
    $email = $row[1];

    echo "<html><h1>Your Cookie has been rejuvenated</h1>",
    "<a href=\"index-members.php\">Continue</a></html>\n";

    $message = "Email: $email\nName: $name\n";

    mail("john@zupons.net", "SWAM Guest Book (Revitalized)", $message, "From: $email\n");

    exit();
  }

  if($email) {
    // Check to see if visitor is in database

    // Get the name and email address of this visitor

    $result = mysql_query("select id, name from account where email='$email'", $db);

    if(!$result) {
      PhpError("needtoreg Error 3: " . mysql_error($db));
      exit();
    }

    if(mysql_num_rows($result)) {
      echo "<html><h1>Select Account</h1>\n",
      "<table border=1><tr><th>Account Name</th></tr>";

      while($row = mysql_fetch_row($result)) {
        $id = $row[0];
        $name = $row[1];
        
        echo "<tr><td>$name</td><td><a href=\"$PHP_SELF?id=$id\">This One</a></td></tr>\n";
      }
      
      echo "</table></html>\n";
      exit();
    } else {
      echo "<html><h1>Sorry I could not find your registration</h1>",
      "<p>Using your email address I could not find your registration. You can try ",
      "again (press back), or your can <a href=\"guestreg.php\">reregister</a>.",
      "</html>\n";

      exit();
    }        
  }
} // NOT ID or EMAIL

  // If not id or email set then present the form to fill out.
?>

<html>
<head>
   <base href='http://zupons.net.mylampsite.com/swam/'/>
   <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
   <link rel="stylesheet" href="css/swam.css" type="text/css">
</head>
<body>
<?php include("header.phpi"); ?>
<h1>You must register for special services</h1>
<p style="color: red;">If you have never registered <a href="guestreg.php">Click Here</a>.
<p><b>If you have already registered</b> it is possible your local &quot;COOKIE&quot;
has been corrupted or distroyed. If you think your are already registered please
enter your email address and I will search the database and rejuvenate your
COOKIES.</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
Enter Email Address: <input type="text" name="email"><br>
<input type="submit">
</form>
<br>

<?php include("fullfooter.php");?>
