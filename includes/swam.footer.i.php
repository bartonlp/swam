<?php
$lastmod = date("M d, Y H:i:s", getlastmod());  

return <<<EOF
<footer>
<hr>
$counterWigget
<h3 class='nomargin'><br>
Webmaster <a href="mailto:john@zupons.net">John Zupon</a></h3>
<p>
<a href="aboutwebsite.php">This site is run with Linux, Apache, MySql and PHP</a><br>
<a href="webstats-new.php">WebStats</a>
</p>
<address>
  South West Aquatic Masters <br>
  Steve Schofield Aquatic Center Pool <br>
  Los Angles Pierce College
  6201 Winnetka <br>
  Woodland Hills, CA 91371 <br>
  Team Phone (818)347-1637 <br>
  Copyright &copy; 2000-2018 South West Aquatic Masters
</address>
<p id='lastmodified'>Last Modified&nbsp;$lastmod</p>
</footer>
</body>
</html>
EOF;

