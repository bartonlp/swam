<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->title = "Guest Registration";
$h->banner = "<h1>Guest Registration</h1>";

list($S->top, $S->footer) = $S->getPageTopBottom($h);

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case 'post':
        post($S);
        break;
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
    }
    break;
  case "GET":
    switch($_GET['page']) {
      case 'respond':
        respond($S);
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

// ********************************************************************************

function start($S) {
  if($id = $S->id) {
    list($result, $n) = $S->query("select * from swammembers where id='$id'", true);
    if($n) {
      $row = mysql_fetch_assoc($result);
      extract($row);
    }
  }
  
  echo <<<EOF
$S->top
<br>
<p>Please let us know what you think about our site. Was there a problem? Did you find what you
were looking for or at least something interesting? Will you come back? Fill in what you want there
are no required fields, of course, the more you tell us the more we can do to improve our site.</p>
<p align=center style="background-color: yellow; border: groove black 3px">
If you have <b>already registered</b> you want to post information for all swimmers you may want to use the new
<a href="bboard.php">BBS <i>Bulletin Board Service</i></a> instead.</a>
<p>
<a name="findme"></a>
<p align=center style="background-color: red; color: white; border: groove black 3px">
If you have already registered but your information seems to be misssing you may
have lost your computer COOKIE. I may be able to 
<a href="needtoreg.php" style="background-color:white">Find You</a> if you are in our database. I can then
rejuvenate your Swam COOKIE.
</p><br>
EOF;

  if($id) {
    $result = $S->query("select * from swammembers where id=$id");

    $row = mysql_fetch_assoc($result);
    extract($row);
    if(!preg_match("/.*?@.*?\..*/", $email)) {
      $password = $email;
      $email = $realEmail;
    }
    $repeatemail = $email;
  }

  echo <<<EOF
<table border=1 align=center cellspacing=0 cellpadding=0>
<form action="$S->self" method="post">
<tr>
<td>Enter email address.<br>
Your email address is required to register.
</td>
<td><input type="text" name="email" size=30 value="$email"></td>
</tr>
<tr>
<td>Reenter email address.</td>
<td><input type="text" name="repeatemail" size=30 value="$repeatemail"></td>
</tr>
<tr>
<td>You can enter a password if you want extra security.<br>If you enter a password then you will need to use it to
revitalize your COOKIE<br>when logging in instead of your email address.<br> The password is OPTIONAL.</td>
<td><input type="text" name="password" value="$password"></td>
</tr>
<tr>
<td>Your First Name:</td>
<td><input type="text" name="fname" size=30 value="$fname"></td>
</tr>
<tr>
<td>Your Last Name:</td>
<td><input type="text" name="lname" size=30 value="$lname"></td>
</tr>
<tr>
<td>Your address:</td>
<td><input type="text" name="address" size=30 value="$address"></td>
</tr>
<tr>
<td>Your city:</td>
<td><input type="text" name="city" size=20 value="$city"></td>
</tr>
<tr>
<td>Your state:</td>
<td><input type="text" name="state" size=2 value="$state"></td>
</tr>
<tr>
<td>Your zip code:</td>
<td><input type="text" name="zip" size=6 value="$zip"></td>
</tr>
<tr>
<td>Your country:</td>
<td><input type="text" name="country" size=6 value="$country"></td>
</tr>
<tr><td>Phone:</td><td><input type=text name=phone value="$phone"></td>
</tr>
<tr>
<td>Your birthday(<font color=red>MO/Day/Year (xx/xx/xxxx) optional</font>):</td>
<td><input type="text" name="bday" size=12 value="$bday"></td>
</tr>
<tr>
<td colspan="2">Please provide us some feedback?<br>
<textarea name="feedback" rows="5" cols="45">
</textarea>
</td>
</tr>
<tr>
<td>
Team: <select name=team>
EOF;

  $teams = array('""','swam','daland','ventura','rosebowl','santabarbera');
  $tnames = array('None Of These', 'Swam', 'Daland', 'Ventura', 'Rose Bowl', 'Santa Barbara');

  for($i=0; $i < count($teams); ++$i) {
    printf("<option value=$teams[$i]%s>$tnames[$i]</option>\n", is_int(strpos($team, $teams[$i])) ? " selected" : "");
  }

echo <<<EOF
</select>
</td>
</tr>
<tr>
<td height="60" valign="bottom" colspan="2" align="left">
<input type="hidden" name="id" value="$id">
<input type="hidden" name="page" value="post">
EOF;

  if($name || $email) {
    echo "<input type=submit name=updateInfo value='Update My Information'>\n";
  } else {
    echo "<input type='submit' value='Register!'>\n"; 
  }

echo <<<EOF
<input type="reset" value="Reset">
</td>
</tr>
</form>
</table>
<h2>Privacy Statement:</h2>
<p>With the exception of your "feedback" and your name no other information will be supplied to anyone
without your written permision each and every time. Your feedback and name will appear in the 
"See Who's Signed Already" and you name may appear in the 
"Top users of this site" and "Who's been here today" sections. Only others who have registered 
will be able to see any of these areas.</p>
$S->footer
EOF;
}

// ********************************************************************************

function post($S) {
  //vardump($_POST, "POST");
  
  $ar = array('fname'=>'First Name', 'lname'=>'Last Name', 'email'=>'Email Address');

  $err = false;

  foreach($ar as $k=>$v) {
    if(!$_POST[$k]) {
      echo "You must supply $v<br>";
      $err = true;
    }
  }

  if($err) {
    echo "$S->top\nReturn and correct your information\n$S->footer";
    exit();
  }

  extract($_POST);

  // $email is the real email address and $repeatemail must match it
  
  if($email != $repeatemail) {
    echo "$S->top\nEmail ($email) does not match repeat email ($repeatemail)\n$S->footer";
    exit();
  }

  // Now make sure that $email looks like an email address
  
  if(!preg_match("/^.+?@.+?\..+$/", $email)) {
    echo "$S->top\nYour email address ($email) does not look like an email address. Please return and fix it.<br>\n$S->footer";
    exit();
  }
  
  $name = "$fname $lname";

  // Put $email into $realEmail and then check to see if $password has been set
  
  $realEmail = $email;
  
  if($password) {
    $email = $password; // Put the password in $email for insert into swammembers
  }

  //echo "email=$email, password=$password, realEmail=$realEmail<br>";
  //exit();
  
  if($id) {
    $S->query("update swammembers set fname='$fname', lname='$lname', name='$name', email='$email', address='$address', " .
              "city='$city', state='$state', gender='$gender', zip='$zip', country='$country', " .
              "phone='$phone', bday='$bday', team='$team', realEmail='$realEmail', visits=visits+1, visittime=now() where id='$id'");

    $h->title = "Guest Registration. Data Updated";
    $h->banner = "<h1>Data Updated</h1>";
    $top = $S->getPageTop($h);

    $S->setIdCookie($id);
    
    echo <<<EOF
$top
$S->footer
EOF;

  } else {
    // Check the database for fname, lname
    // If already there tell member and ask if he wants to update his profile

    list($result, $n) = $S->query("select id from swammembers where fname='$fname' and lname='$lname'", true);

    if($n) {
      list($id) = mysql_fetch_row($result);
      
      // We found this member
      $h->title = "Already a member";
      $h->banner = "<h1>$fname $lname you are already a member</h1>";
      $top = $S->getPageTop($h);

      echo <<<EOF
$top
<form action="$S->self" method="post">
<input type="submit" value="Update Your Profile"/>
<input type="hidden" name="email" value="$email"/>
<input type="hidden" name="repeatemail" value="$repeatemail"/>
<input type="hidden" name="password" value="$password"/>
<input type="hidden" name="fname" value="$fname"/>
<input type="hidden" name="lname" value="$lname"/>
<input type="hidden" name="address" value="$address"/>
<input type="hidden" name="city" value="$city"/>
<input type="hidden" name="state" value="$state"/>
<input type="hidden" name="zip" value="$zip"/>
<input type="hidden" name="country" value="$country"/>
<input type="hidden" name="phone" value="$phone"/>
<input type="hidden" name="bday" value="$bday"/>
<input type="hidden" name="feedback" value="$feedback"/>
<input type="hidden" name="team" value="$team"/>
<input type="hidden" name="id" value="$id">
<input type="hidden" name="page" value="post">
</form>
$footer
EOF;
    } else {
      $code = rand();
      $query = "insert into swammembers (fname, lname, name, email, firsttime, address, city, state, gender, zip, country, " .
               "phone, bday, team, realEmail, code) values('$fname', '$lname', '$name', '$email', now(), '$address', '$city', " .
               "'$state', '$gender', '$zip', '$country', '$phone', '$bday', '$team', '$realEmail', '$code')";
      //echo "$query";
      $S->query($query);

      $msg = <<<EOF
You registered at Swam.us. To complete your registration '$name' follow this link:
http://zupons.net/swam/guestreg.php?page=respond&code=$code
Thank You.
EOF;
      // At this point we must use $realEmail not $email as $email my really be the password!
    
      mail($realEmail, "Registration for Swam.us", $msg, "From: Swam.us Registration <info@zupons.net>", "-fbartonphillips@gmail.com");

      $msg = <<<EOF
Name: $fname $lname
Email: $realEmail
Password: $email
Code: $code
IP: $S->ip
Agent: $S->agent
EOF;
    
      mail("bartonphillips@gmail.com", "Someone has registered at Swam.us", $msg,
           "From: Swam.us Registration <info@zupons.net>\r\nCC: john.zupon@gmail.com",
           "-fbartonphillips@gmail.com");

      $h->title = "Registration, Check Email";
      $h->banner = "<h1>Registration: Check Email</h1>";
      $top = $S->getPageTop($h);

      echo <<<EOF
$top
<h2>You will receive an Email at $realEmail very soon. Follow the link provided in the Email to complete your registration.</h2>
$S->footer
EOF;
    }
  }
}

// ********************************************************************************

function respond($S) {
  $code = $_GET['code'];

  list($result, $n) = $S->query("select * from swammembers where code = '$code'", true);
  if(!$n) {
    echo "$S->top\n<h2>Not Found</h2>\n$S->footer";
    exit();
  }
  $row = mysql_fetch_assoc($result);
  extract($row);

  $S->query("update swammembers set visits=1, visittime=now(), code=null where code='$code'");
  $h->title = "You are now registered";
  $h->banner = "<h1>Thank you. Your now registered</h1>";
  $top = $S->getPageTop($h);

  //echo "respond: id=$id<br>";
  
  $S->setIdCookie($id);

  echo <<<EOF
$top
<p>Thank you $name for registering.</p>
<p>You can now continue to the <a href="index-members.php">members page</a></p>
$S->footer
EOF;

}

?>

