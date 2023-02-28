<?php
// BLP 2021-09-05 -- Note the putenv() and
// ini_set() at the start. This is needed because I do not have root access to this server and
// several things just arn't right, not the least that this site uses PHP5 not 7.
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

$S->title = "Other Master's Swimming Links";
$S->banner = "<h1 style='text-align: center'>Other Master's Swimming Links</h1><hr>";

[$top, $footer] = $S->getPageTopBottom();

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
$footer
EOF;
?>

