<?php 
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->title = "No Id";
$h->banner = "<h1>You don't have an Id yet.</h1>";

$top = $S->getPageTop($h);
$footer = $S->getFooter();

echo <<<EOF
$top
<hr>
<p>You must go to our Home page which will automatically assign you one. This page
should automatically redirect to the home page in 20 seconds. If not click the link below.</p>
<p><span style="color: red">Note: If your browser does <b>not</b> support <i>cookies</i> or
if you have use of <i>cookies</i> disabled you will not be able to take advantage
of many of the features on our web site -- including the page you just came from! Sorry.</span></p>
<a href="index-members.php">Home Page of Southwest Aquatic Masters</a>
$footer
EOF;
?>
