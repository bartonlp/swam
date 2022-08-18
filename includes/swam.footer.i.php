<?php

$lastmod = date("M d, Y H:i:s", getlastmod());  
$version = SITE_CLASS_VERSION;
$phpversion = PHP_VERSION;

return <<<EOF
<footer>
<hr>
$counterWigget
<h3 class='nomargin'><br>
Webmaster <a href="mailto:john@zupons.net">John Zupon</a></h3>
<p>
<a href="aboutwebsite.php">This site is run with Linux, Apache, MySql and PHP</a><br>
<a href="webstats.php">WebStats</a>
</p>
<address>
  South West Aquatic Masters <br>
  Steve Schofield Aquatic Center Pool <br>
  Los Angles Pierce College
  6201 Winnetka <br>
  Woodland Hills, CA 91371 <br>
  Team Phone (818)347-1637 <br>
  Copyright &copy; 2022 Southwest Masters
</address>
<p>PHP Version: $phpversion<br>SiteClass Version: $version<br>
Last Modified&nbsp;$lastmod</p>
</footer>
</body>
</html>
EOF;

