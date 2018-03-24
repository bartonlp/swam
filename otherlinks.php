<?php
putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
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
<a href="http://www.clubassistant.com/meets.cfm/">Online Meet Registration</a></li>
<li>
<a href="http://www.usswim.org/">USSwimming</a></li>
<li>
<a href="http://www.unb.ca/web/Masters_swimming/FINAindex.html">Federation
Internationale de Natation Amateur</a></li>
<li>
<a href="http://www.usc.edu/dept/swim/">USC Swimming &amp; Diving</a></li>
<li>
<a href="http://www.pacswim.org/">Welcome to Pacific Swimming!</a></li>
<li>
<a href="http://www.dalandswim.com/">Daland Swim School</a> Masters Program</li>
<li>
<a href="http://lornet.com/swimming/faq/swimfaq.txt">swimming FAQ</a></li>
<li>
<a href="http://csca.org/">Canadian Swimming Coaches Association</a></li>
<li>
<a href="http://www.unb.ca/web/Masters_swimming/">Canadian Masters Swimming
Homepage</a></li>
<li>
<a href="http://www.swiminfo.com/">Swim Info - Online Swimming Encyclopedia</a></li>
<li>
<a href="http://www-rohan.sdsu.edu/dept/coachsci/index.htm">Coaching Science
Abstracts</a></li>
<li>
<a href="http://www.voicenet.com/~nrgswim">NRG Swimming</a></li>
<center>
<a href="http://www.pennyweb.com/shellpenny-cgi/pennyweb.pl?1002A1022">
<img src="images/banner.jpg" width="450" height="80" alt=""></a>
</center>
$footer
EOF;
?>

