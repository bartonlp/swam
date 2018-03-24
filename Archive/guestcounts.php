<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->title = "Guest Counts";
$h->banner = "<h1 style='text-align: center'>Guest Counts</h1><hr>";
list($top, $footer) = $S->getPageTopBottom($h);

$toptotal = 0;
	
$result = $S->query("select name, sum(visits) as sumcnt " .
                    "from swammembers where visits > 10 and name is not null group by name order by sumcnt");

$tbl = "";

while($row = mysql_fetch_row($result)) {
  $tbl .= "<tr><td>" . stripslashes($row[0]) . "</td><td align='right'>$row[1]</td></tr>\n";
  $toptotal += $row[1];
}

$result = $S->query("select sum(visits) from swammembers");

$total = mysql_result($result, 0, 0);

echo <<<EOF
$top
<p>These counts show the number of times you came to the <b>swam.us</b> domain
from somewhere other than the <b>swam.us</b> domain. That is the counter does not
tally each time you go from the main page to the <i>BBS</i> and back or to and from any
other page in the <b>swam.us</b> domain. It does count you if you come from another
domain or if you got here by entering the address in the <i>locator window</i> or used
the <i>bookmarks or fovorites</i>. Therefore, this is a pretty conservitave count.</p>
<table border=1 bgcolor=cyan cellpadding=0 cellspacing=0>
<tr>
<th colspan=2>Top users of this site (more than ten times)</th>
</tr>
<tr>
<th>Name</th><th>Count</th>
</tr>
$tbl
<tr><th align='left'>Total of Top Users</th><td align='right'>$toptotal</td></tr>
<tr><th align='left'>Total of all registered members</th><td align='right'>$total</td></tr>
</table>
$footer
EOF;
?>
