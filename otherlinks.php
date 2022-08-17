<?php
// BLP 2021-09-05 -- Note the putenv() and
// ini_set() at the start. This is needed because I do not have root access to this server and
// several things just arn't right, not the least that this site uses PHP5 not 7.
putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
ini_set("error_log", "/tmp/PHP_ERROR.log");
$_site = require_once(getenv("SITELOAD") ."/siteload.php");

$S = new $_site->className($_site);

$h->title = "Other Master's Swimming Links";
$h->banner = "<h1 style='text-align: center'>Other Master's Swimming Links</h1><hr>";

list($top, $footer) = $S->getPageTopBottom($h);

echo <<<EOF
$top
<h2 align=center>
Swimming Links</h2>
<hr>
<li>
<a href="http://www.usms.org/"><img src="images/usms320.gif" height="50" width="150" alt=""></a>
<a href="http://www.usms.org/">United
States Masters Swimming</a></li>
<li>
<a href="http://www.pacificmasters.org"><img src="images/pmsd.gif" height="50" width="150" alt=""></a>
<a href="http://www.pacificmasters.org">Pacific
Masters Swimming</a></li>
<li>
<a href="http://www.piercecollege.edu">Pierce College Home Page</a></li>
<li>
<a href="http://www.spma.net/">SPMA</a></li>
<li>
<a href="http://www.pacswim.org/">Welcome to Pacific Swimming!</a></li>
<li>
<a href="http://www.dalandswim.com/">Daland Swim School</a> Masters Program</li>
<li>
<center>
<a href="http://www.pennyweb.com/shellpenny-cgi/pennyweb.pl?1002A1022">
<img src="images/banner.jpg" width="450" height="80" alt=""></a>
</center>
$footer
EOF;
?>

