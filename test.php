<?php
echo "NEW SITE<br>";
putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
ini_set("error_log", "/tmp/PHP_ERROR.log");
$_site = require_once(getenv("SITELOAD") ."/siteload.php");
ErrorClass::setDevelopment(true);
$S = new $_site->className($_site);
vardump("S", $S);
vardump("_site", $_site);
vardump("SERVER", $_SERVER);
vardump("COOKIE", $_COOKIE);
echo "Virtual host: " . $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "<br>";
date_default_timezone_set('America/Denver');
echo date("Y-m-d H:i:s e") . "<br>";
echo "ini_get, error_log: " . ini_get("error_log") . "<br>";
ini_set("error_log", "/tmp/PHP_ERROR.log");
echo "ini-set, error_log: " . ini_get("error_log") . "<br>";
error_log("this is a test");
