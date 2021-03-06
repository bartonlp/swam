<?php
  // See if client has a cookie. If not send him to get one

  require("secureinfo/id.phpi");

  $id = CheckAndSetId();
  if(!$id) {
    header("Location:  http://www.swam.us/noid.php\n");
    exit();
  }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<head>
<meta name="Author" content="Barton L. Phillips, Applitec Inc., mailto:barton@applitec.com">

<link rel="stylesheet" href="swam.css" type="text/css">

<title>Swam -- Guest Book</title>
</head>

<?php include("header.phpi") ?>

<hr color=blue height=4>

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

<?php
  $result = mysql_query("select * from account where id=$id", $db);

  if(!$result) {
    PhpError("guestreg Error 1: " . mysql_error($db));
    exit();
  }

  $row = mysql_fetch_array($result);
  $name = $row['name'];
  $email = $row['email'];
	$realemail = $row['realEmail'];
  $address = $row['address'];
  $city = $row['city'];
  $state = $row['state'];
  $zip = $row['zip'];
  $country = $row['country'];
  $phone = $row['phone'];
  $team = $row['team'];
?>

<TABLE border=1 align=center cellspacing=0 cellpadding=0>
<FORM ACTION="/guestreg2.php" method="post">
<TR>
<TD>Password.<br>
Use your email address <i>unless you want to remember something else.</i></TD>
<TD><input type="text" name="email" size=30 value="<?php echo "$email"?>"></TD>
</TR>

<tr>
<td>Your real email address if you used something else as your password.<br>
Remember, if you ask a question in the "Feedback" box below<br>
you will only get a relpy if you give us your email address!</td>
<TD><input type="text" name="realemail" size=30 value="<?php echo "$realemail"?>"></TD>
</TR>

<TR>
<TD>Your name (<font color=red>First&amp;Last</font>):</TD>
<TD><input type="text" name="name" size=30 value="<?php echo "$name"?>"></TD>
</TR>
<TR>
<TD>Your address:</TD>
<TD><input type="text" name="address" size=30 value="<?php echo "$address"?>"></TD>
</TR>
<TR>
<TD>Your city:</TD>
<TD><input type="text" name="city" size=20 value="<?php echo "$city"?>"></TD>
</TR>
<TR>
<TD>Your state:</TD>
<TD><input type="text" name="state" size=2 value="<?php echo "$state"?>"></TD>
</TR>
<TR>
<TD>Your zip code:</TD>
<TD><input type="text" name="zip" size=6 value="<?php echo "$zip"?>"></TD>
</TR>
<TR>
<TD>Your country:</TD>
<TD><input type="text" name="country" size=6 value="<?php if($country) echo "$country";else echo "USA"?>"></TD>
</TR>
<tr><td>Phone:</td><td><input type=text name=phone value="<?php echo "$phone"?>"></td>
</tr>

<TR>
<TD COLSPAN=2>Please provide us some feedback?<BR>
<TEXTAREA NAME="feedback" ROWS=5 COLS=45>

</TEXTAREA>
</TD>
</TR>

<tr>
<td>
Team: <select name=team>
<?php
  $teams = array('""','swam','daland','ventura','rosebowl','santabarbera');
  $tnames = array('None Of These', 'Swam', 'Daland', 'Ventura', 'Rose Bowl', 'Santa Barbara');

  for($i=0; $i < count($teams); ++$i) {
    printf("<option value=$teams[$i]%s>$tnames[$i]</option>\n", is_int(strpos($team, $teams[$i])) ? " selected" : "");
  }
?>
</select>
</td>
</tr>
<TR>
<TD height=60 valign=bottom COLSPAN=2 ALIGN=left>

<input type=hidden name=id value=<?php echo "$id"?>>
<input type=hidden name=mode value="post">

<?php
  if($name || $email) {
    print("<input type=submit name=updateInfo value='Update My Information'>\n");
  } else {
    print("<INPUT TYPE=submit VALUE='Register!'>\n");
  } 
?>

<INPUT TYPE="reset" VALUE="Reset">

</TD>
</TR>
</FORM>
</TABLE>
<h2>Privacy Statement:</h2>
<p>With the exception of your "feedback" and your name no other information will be supplied to anyone
without your written permision each and every time. Your feedback and name will appear in the 
"See Who's Signed Already" and you name may appear in the 
"Top users of this site" and "Who's been here today" sections. Only others who have registered 
will be able to see any of these areas.</p>


