<?php
require("secureinfo/id.phpi");

// Guest registration for SWAM -- Part 2 process FORM info
// This is called from guestreg.php, and by guestreg2.php itself
// When called from guestreg.php "mode" == "post" AND
//    the following arguments are/maybe defined: 
//    id, email, name, address, city, state, zip, country, phone, feedback, team
// When called by itself mode will be "revitalize" or mode will be undefined! and we will ONLY have
// the id.

// In All cases we must have id or there is a problem!

if(!$id) {
  print("<h1>Internal Error -- NO ID</h1>");
  mail("john@zupons.net", "SWAM Guest Book ERROR", "NO ID???");  
  exit();
}

// If no name or email supplied

function NoNameOrEmail() {
  print("
<html>
<head>
<META http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
</head>
<body>
<h1><font color=red>You <u>must</u> provide at least your full name and email address</font></h1>
<br>\n"
  );
}

 // include("fullfooter.php");


// Did we call ourselves? 

if($mode == "revitalize") {
  // $id is the ID we want to revitalize
  // Get the name associated with this ID and set the
  // COOKIE
  // Get rid of the new ID that was created.
  // MAKE SURE THAT THE OLD AND NEW ID's are not the same!

  $oldid = CheckId("no_inc");

  $result = mysql_query("select name, email, realEmail from account where id=$id", $db);

  if(!$result) {
    PhpError("guestreg2 Error 1: " . mysql_error($db));
    exit();
  }

  $row = mysql_fetch_row($result);

  if(!$row) {
    echo "Can't Find Entry for id=$id?<br>";
    exit();
  }

  // Revitalize the COOKIE

  SetIdCookie($id);

  // Make sure we don't delete ourselves!!!

  if($oldid != $id) {
    $result = mysql_query("delete from account where id=$oldid", $db);

    if(!$result) {
      PhpError("guestreg2 Error 2: " . mysql_error($db));
      exit();
    }
  }

  $name = $row[0];
  $email = $row[1];
  $realemail = $row[2];
	
  echo "<html><h1>Your Cookie has been rejuvenated</h1>",
    "<a href=\"index-members.php\">Continue</a></html>\n";


  $message = "Email: $email ($realemail)\nName: $name\n";

  mail("john@zupons.net", "SWAM Guest Book (Revitalized)", $message, "From: $email\n");

  exit();
}

// ---------------------------------------------------------------------------
// Not (mode=="revitalize")
// Check for name and email address form original FORM

if($mode == "post") {
  if(!$name || !$email) {
    // If we don't have a name or email that is an error.

    NoNameOrEmail();
    exit();
  } else {
    // Have name and email so continue with post

    if(!$updateInfo) {
     $email = mysql_escape_string($email);
     $name = mysql_escape_string($name);
 
     $result = mysql_query("select id, name from account where email='$email' or name='$name'", $db);

      if(!$result) {
        PhpError("guestreg2 Error 3: " . mysql_error($db));
        exit();
      }

      $numRows = mysql_num_rows($result);

      if($numRows) {
        echo "<html><h1>I found the following entries in the database</h1>\n";
        echo "<p>If one of these is you please select that entry.";
        echo "Otherwise select NONE at the bottom.</p>";

        echo "<table border=1><tr><th>Account Name</th></tr>";

        while($row = mysql_fetch_row($result)) {
          $dbid = $row[0];
          $dbname = $row[1];
        
          echo "<tr><td>$dbname</td><td><a href=\"$PHP_SELF?id=$dbid&mode=revitalize\">This One</a></td></tr>\n";
        }
      
        echo "</table></html>\n";
  
        // To make a new entry we must send all of the info back to ourselve
        // Use original id and name here!

        print("
<form action=\"$PHP_SELF\" method=post>
<input type=hidden name=id value=$id>
<input type=hidden name=name value=$name>
<input type=hidden name=email value=$email>
<input type=hidden name=realemail value=$realemail>																		
<input type=hidden name=address value=$address>
<input type=hidden name=city value=$city>
<input type=hidden name=state value=$state>
<input type=hidden name=zip value=$zip>
<input type=hidden name=phone value=$phone>
<input type=hidden name=country value=$country>
<input type=hidden name=feedback value=$feedback>
<input type=hidden name=team value=$team>
<input type=hidden name=secret value=$secret>
<input type=hidden name=famid value=$famid>
<input type=hidden name=bday value=$bday>
<input type=hidden name=count value=$count>
<input type=submit value='None Of These. Please make a New Entry'>
</form></body></html>"
        );

        exit();
      } 
    } 
    // If we get here we did not find either the name or email address in the database
    // So update the ID entry with the information supplied
  }
}

// ---------------------------------------------------------------------------
// Could be a valid "post" or if not mode then from ourself but we want to make a new
// entry after being asked if the name/email found was us!
//Test for valid email addressjjz
$email = "$email";

//print("Checking: $email<br>");

if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $email)) {

//    print("Format Test: PASSED<br>");
//    print("Online host verification Test...<br><br>");
//    print("MX Records for: $email<br>");
   
    list($alias, $domain) = split("@", $email);
   
    if (checkdnsrr($domain, "MX")) {
   
        getmxrr($domain, $mxhosts);
       
        foreach($mxhosts as $mxKey => $mxValue){
//            print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mxValue<br>");
        }
       
//        print("Online host verification Test: PASSED<br><br>");
//        print("Email Status: VALID");
   
    } else {
          print("You must enter a valid email address to register<br>");
//        print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No records found.<br>");
//        print("Online host verification Test: FAILED<br><br>");
          print("Email Status: INVALID");
          include("index-nonmembers.php");
         exit();   
    }

} else {
        print("You must enter a valid email address to register<br>");
//    print("Format Test: FAILED<br><br>");
//    print("Invalid email address provided.<br><br>");
        print("Email Status: INVALID");
        include("index-nonmembers.php");
        exit();   
}

CheckMX("fakedomain.org");
CheckMX("hotmail.com");

function CheckMX($domain) {
        exec("dig +short MX " . escapeshellarg($domain),$ips);
        if($ips[0] == "") {
//         print "MX record found for $domain not found!\n";
               
         return FALSE;
        }
//        print "MX Record for $domain found\n";
        return TRUE;
}
// Send Me an email so I know whats happening

if($updateInfo) 
  $updateInfo = "UPDATED INFO\n";

$message = $updateInfo . "Email: $email ($realemail)\nName: $name\nAddress: $address\nCity: $city\n" .
  "State: $state\nZip: $zip\nCountry: $country\nPhone: $phone\nBday: $bday\n" .
  "Count: $count\nTeam: $team\n URL: $SwamUrl\n"  .
  "Feed Back:\n$feedback\n";

//mail("john@zupons.net", "SWAM Guest Book", $message);

$name = addslashes(ucwords($name));
$address = addslashes(strtolower($address));
$city = addslashes(ucwords($city));
$state = addslashes(strtoupper($state));
$country = addslashes(strtoupper($country));
$phone = addslashes($phone);    
$feedback = addslashes($feedback);    
$bday=addslashes($bday);

$name = mysql_escape_string($name);
$email = mysql_escape_string($email);
$realemail = mysql_escape_string($realemail);
$address = mysql_escape_string($address);
$state = mysql_escape_string($state);
$city = mysql_escape_string($city);
$zip = mysql_escape_string($zip);
$country = mysql_escape_string($country);
$phone = mysql_escape_string($phone);
$team = mysql_escape_string($team);
$secret = mysql_escape_string($secret);
$famid = mysql_escape_string($famid);
$bday = mysql_escape_string($bday); 
$count = mysql_escape_string($count);
if ($team == "swam")
   {$secret = "yes";} 
if ($SwamCount = 1)
   {$count = "2"; }
$result = mysql_query("update account set name='$name', email='$email', realEmail='$realemail',
  address='$address', city='$city', state='$state', zip='$zip', 
  country='$country', phone='$phone', count= '$count',
  team='$team', secret='$secret' where id=$id", $db);

if(!$result) {
  PhpError("guestreg2 Error 5: " . mysql_error($db));
  exit();
}
 if($count < '3'){
    } else {
$feedback = trim($feedback);

if($feedback) {
  $result = mysql_query("insert into feedback (id, feedback)
    values($id, '$feedback')", $db);

  if(!$result) {
    PhpError("guestreg2 Error 6: " . mysql_error($db));
    exit();
  }
}
}
mysql_close($db);

?>

<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body>
<h1>Thank you for your feedback</h1>
<br>
<p>
<a href="http://www2.swam.us:8080/index-members.php">Back to SWAM Home Page</a>
</p>
<?php
 // include("fullfooter.php");
?>
