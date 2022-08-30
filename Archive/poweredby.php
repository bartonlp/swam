<?php
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

$h->title = "Website Powered By";
$hdr = $S->getPageHead($h);
echo <<<EOF
$hdr
<body>
<center>
<div style="text-align: center; border: groove blue 10px; width: 60%;">
   <div id="runWith">
      <p>This site's designers are John Zupon and Barton L. Phillips<br/>
         <a href="http://www.zupons.net">www.zupons.net</a><br>
         <a href="http://www.bartonphillips.com">www.bartonphillips.com</a><br>
         Copyright &copy; $S->copyright
      </p>
  
      <p>This site is hosted by <a href="http://www.lamphost.net">
         <img width="200" height="40" border="0" align="middle"
                alt="LAMP Host (www.lamphost.net)"
                src="http://www.lamphost.net/sites/all/themes/lamphostnet/images/logo.jpg"/>
         </a>
      </p>
   </div>
   <p>This site is run with Linux, Apache, MySql, and PHP.</p>
   <p><img src="images/linux-powered.gif" border='1' alt="Linux Powered"></p>
   <p><a href="http://www.apache.org/"><img border="0" src="images/apache_logo.gif" alt="Apache" width="400" height="148"></a></p>
   <p><a href="http://www.mysql.com"><img border=0 src="images/powered_by_mysql.gif" alt="Powered by MySql"></a></p>
   <p><a href="http://www.php.net"><img src="images/php-small-white.png" alt="PHP Powered"></a></p>
   <p><a href="http://www.mozilla.org"><img src="images/bestviewedwithmozillabig.gif" alt="Best viewed with Mozilla or any other browser"></a></p>
   <p><a href="http://www.mozilla.org"><img src="images/shirt3-small.gif" alt="Mozillz"></a></p>
   <p><img src="images/msfree.png" alt="100% Microsoft Free"></p>

   <p><a href="http://www.netcraft.com/whats?host=www.swam.us">
      <img src="images/powered.gif" width=90 height=53 border=0 alt="Powered By ...?"></a></p>
</div>
<a href="webstats-new.php">Web Site Statistics</a>
</center>
</body>
</html>
EOF;
?>