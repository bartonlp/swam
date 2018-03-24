<?php
  require("secureinfo/id.phpi");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
<title>What does 'Total Visitors' mean?</title>
</head>
<body>
<h1 align="center">What does "Total Visitors" Mean?</h1>
<hr>
<p>"Total Visitors" is the number of unique IP addresses or Domain
Names that have come to this site. That means that each IP address or
Domain name has only been counted <b><u>once</u></b>. This number is
the total number of unique users. A lot of people never come back, and
some that come back many times never 'Register'. The total number of
'Registered' members is
<?php
  $result = mysql_query("select count(*) from account where name is not null", $db);
  $total = number_format(mysql_result($result, 0, 0));
  
  print("$total.");
?>

</p>

<p>
<?php
  $result = mysql_query("select count(*) from account where name is null", $db);
  $total = number_format(mysql_result($result, 0, 0));

  print("Unregistered visitors = $total<br>\n");

  $result = mysql_query("select count(*) from account where name is null and count > 1", 
$db);
  $total = number_format(mysql_result($result, 0, 0));

  print("Unregistered visitors that came back more than once = $total<br>\n");

  $result = mysql_query("select count(*) from account where name is null and count > 5", 
$db);
  $total = number_format(mysql_result($result, 0, 0));

  print("Unregistered visitors that came back more than five times = $total<br>\n");

  $result = mysql_query("select count(*) from account where name is null and count > 10", 
$db);
  $total = number_format(mysql_result($result, 0, 0));

  print("Unregistered visitors that came back more than 10 times = $total<br>\n");
?>
</body>
</html>
