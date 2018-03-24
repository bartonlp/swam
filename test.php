<?php
echo "NEW SITE<br>";
putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
$_site = require_once(getenv("SITELOAD") ."/siteload.php");
ErrorClass::setDevelopment(true);
//$S = new $_site->className($_site);
vardump("SERVER", $_SERVER);
vardump("COOKIE", $_COOKIE);
echo $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "<br>";
date_default_timezone_set('America/Denver');
echo date("Y-m-d H:i:s e");
error_log("this is a test");
  