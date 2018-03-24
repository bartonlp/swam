<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

// Visitor needs to register before he/she can use services!

// If we had either an ID or an EMAIL we process a FORM
// Otherwise show the FORM

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case 'post':
        checkpassword($S);
        break;
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
    }
    break;
  case "GET":
    switch($_GET['page']) {
      case 'thisone':
        thisone($S);
        break;
      default:
        start($S);
        break;
    }
    break;
  default:
    // Main page
    throw(new Exception("Not GET or POST: {$_SERVER['REQUEST_METHOD']}"));
    break;
}

// ---------------------------------------------------------------------------
// We selected one of the several members shown
// ---------------------------------------------------------------------------

function thisone($S) {
  $id = $_GET['id'];
 
  list($result, $n) = $S->query("select concat(fname, ' ', lname) as name, email, realEmail from swammembers where id='$id'", true);

  if(!$n) {
    echo "$top\nCan't Find Entry for id=$id?\n$footer";
    exit();
  }

  $row = mysql_fetch_assoc($result);
  extract($row);
    
  $S->setIdCookie($id);

  $message = "Agent: $S->agent\nIp: $S->ip\nEmail: $email\nrealEmail: $realEmail\nName: $name\n";

  // Look to see if $realEmail looks like an email address. In which case $email is probably a password so use $realEmail instead
  
  if(preg_match("/^.+?@.+\..+$/", $realEmail)) {
    $email = $realEmail;
  }

  //mail("john.zupon@gmail.com", "SWAM Guest Book (Revitalized)", $message, "From: $email\n");
  mail("bartonphillips@gmail.com", "SWAM Guest Book (Revitalized)", $message, "From: $email\r\nCC:john.zupon@gmail.com",
       "-fbartonphillips@gmail.com");

  header("Location: index-members.php");
}

// ---------------------------------------------------------------------------
// This is the page=post from start()

function checkpassword($S) {
  // Check to see if visitor is in database
  // Get the name and email address of this visitor

  $postEmail = $_POST['email'];

  list($result, $n) = $S->query("select id, concat(fname, ' ', lname) as name, email, realEmail " .
                                "from swammembers where email='$postEmail'", true);

  if($n > 1) {
    list($top, $footer) = $S->getPageTopBottom("<h1>Select Account</h1>");

    echo <<<EOF
$top
<hr>
<table border=1><tr><th>Account Name</th></tr>
EOF;

    while($row = mysql_fetch_assoc($result)) {
      extract($row);
      echo "<tr><td>$name</td><td><a href='$S->self?id=$id&page=thisone'>This One</a></td></tr>\n";
    }
      
    echo "</table>\n$footer";
  } elseif($n == 1) {
    $row = mysql_fetch_assoc($result);
    extract($row);

    $S->setIdCookie($id);
    
    $message = "Id: $id\nAgent: $S->agent\nIp: $S->ip\nEmail: $email\nrealEmail: $realEmail\nName: $name\n";

    // Look to see if $realEmail looks like an email address.
    // In which case $email is probably a password so use $realEmail instead
  
    if(preg_match("/^.+?@.+\..+$/", $realEmail)) {
      $email = $realEmail;
    }

    mail("bartonphillips@gmail.com", "SWAM Guest Book (Revitalized)", $message, "From: $email\r\nCC: john.zupon@gmail.com",
         "-fbartonphillips@gmail.com");
    
    header("Location: index-members.php");
  } else {
    list($top, $footer) = $S->getPageTopBottom("<h1>Sorry I could not find your registration</h1>");

    echo <<<EOF
$top
<hr>
<p>Using your email address I could not find your registration. Did you create a password instead?<br>You can try
again (press back), or your can <a href="guestreg.php">reregister</a>.</p>
$footer
EOF;
  }        
}

// First Page

function start($S) {
  $h->title = "Need to Register";
  $h->banner = "<h1>You must register for special services</h1>";

  list($top, $footer) = $S->getPageTopBottom($h);

  echo <<<EOF
$top
<p style="color: red;">If you have never registered <a href="guestreg.php">Click Here</a>.
<p><b>If you have already registered</b> it is possible your local &quot;COOKIE&quot;
has been corrupted or distroyed. If you think your are already registered please
enter your email address (or password) and I will search the database and rejuvenate your
COOKIES.</p>

<form action="$S->self" method="post">
Enter Email Address: <input type="text" name="email"><br>
<input type="submit">
<input type="hidden" name="page" value="post">
</form>
<br>
$footer
EOF;
}
?>
