<?php
//script is for new registrarion jjz
include("header.phpi");
  $result = mysql_query("select * from account where id=$id", $db);

  if(!$result) {
    PhpError("guestreg Error 1: " . mysql_error($db));
    exit();
  }

  $row = mysql_fetch_array($result);
  $name = $row['name'];
  $realemail = $row['email'];
  $famid = $row['famid'];
  $count = $row['count'];
  $email = $row['email'];
  $address = $row['address'];
  $city = $row['city'];
  $state = $row['state'];
  $zip = $row['zip'];
  $country = $row['country'];
  $phone = $row['phone'];
  $bday = $row['bday'];
  $team = $row['team'];
  $url = $row['url'];
if ($count == '3'){
  }
  else {
$to = "$realemail";
$message = "$name\nHere is your SWAM registration number. It must be entered to complete your registration for access to SWAM members page\nreg#: $famid";
 mail($to, "SWAM registration number", $message);
$result = mysql_query("update account set count='3' where id=$id", $db);

//Send Me an email so I know whats happening
if($updateInfo) 
  $updateInfo = "UPDATED INFO\n";

$message = $updateInfo . "Email: $email ($realemail)\nName: $name\nAddress: $address\nCity: $city\n" .
  "State: $state\nZip: $zip\nCountry: $country\nPhone: $phone\nBday: $bday\n" .
  "Count: $count\nTeam: $team\n URL: $SwamUrl\n"  .
  "Feed Back:\n$feedback\n";

mail("john@zupons.net", "SWAM Guest Book", $message);
  }

?>

<html>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<head>
<meta name="Author" content="John Zupon., mailto:john@zupons.net">

<link rel="stylesheet" href="swam.css" type="text/css">

<title>Swam -- Registration</title>
</head>
<meta name="Author" content="John Zupon., mailto:john@zupons.net">

<link rel="stylesheet" href="swam.css" type="text/css">

<title>Swam -- Registration</title>
</head>
<TABLE border=1 align=center cellspacing=0 cellpadding=0>
<FORM ACTION method="post">
<TR>
<TD>Registration Number.<br>
Enter the registration number emailed to you</TD>
<TD><input type="text" name="regno" size=30 value="<?php echo "$regno"?>"></TD>
</TR>
<tr>
<td>Your Name.<br>
</td>
<TD><input type="text" name="name" size=30 value="<?php echo "$name"?>"></TD>
</TR>
<tr>
<td>Your email address.<br>
</td>
<TD><input type="text" name="realemail" size=30 value="<?php echo "$realemail"?>"></TD>
</TR>
<TR>
<td>You should receive your registration number<br>
  within 15 minutes. If you don't you probably<br>
  entered a incorrect email address. If your<br>
  email address is correct you can email me<br>
  <a href="mailto:john@zupons.net">john@zupons.net</a> and I will get back<br>
   to you within 24 hours.</td>
 
<TD height=60 valign=bottom COLSPAN=2 ALIGN=left>

<input type=hidden name=id value=<?php echo "$id"?>>
<input type=hidden name=mode value="post">
<INPUT TYPE="reset" VALUE="Reset">
<?php if($name || $realemail) {
    print("<INPUT TYPE=submit name=updateInfo value='Register!'>\n");
  }
?>
</TD>
</TR>
</FORM>
</TABLE>

<?php

if ($updateInfo)
  $updateInfo = "UPDATED INFO\n";

$name = mysql_escape_string($name);
$email = mysql_escape_string($email);
$realemail = mysql_escape_string($realemail);
$famid = mysql_escape_string($famid);
$regno = mysql_escape_string($regno);

if($regno == $famid){
$result = mysql_query("update account set famid='0' where id=$id", $db);
echo "<html><h1>Your Registation is complete</h1>",
    "<a href=\"index-members.php\">Continue</a></html>\n";
}
?>
