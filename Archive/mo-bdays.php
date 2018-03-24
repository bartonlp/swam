<?php
// Display the birthdays for this month
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;
$h->title = "Member Birthdays";
$h->banner = "<h1 style='text-align: center'>Birthdays for this month:</h1><hr>";
list($top, $footer) = $S->getPageTopBottom($h);


$result = $S->query("select monthname(curdate()), month(curdate()), year(curdate())");

$row = mysql_fetch_row($result);
$moName = $row[0];
$mo = $row[1];
$year = $row[2];

$result = $S->query("select name, bday from account where $mo=substring_index(bday, '/',1) order by substring_index(bday, '/',2) ");

$bdays = "";

while($row = mysql_fetch_row($result)) {
	$name = $row[0];
	$bday = $row[1];
	
	$bdayAr = split("/", $bday);

	$day = intval($bdayAr[1]);
	$old = $year - $bdayAr[2];
	$bdays .= "<li>On $moName, $day $name will be $old years old.</li>\n";
}
echo <<<EOF
$top
<ul>
$bdays
</ul>
$footer
EOF;
?>
