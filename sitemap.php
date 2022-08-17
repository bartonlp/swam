<?php
// BLP 2021-09-05 -- Note the putenv() and
// ini_set() at the start. This is needed because I do not have root access to this server and
// several things just arn't right, not the least that this site uses PHP5 not 7.
// BLP 2016-02-18 -- This file is a substitute for Sitemap.xml. This file is RewriteRuled in
// .htaccess to read Sitemap.xml and output it. It also writes a record into the bots table
putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
ini_set("error_log", "/tmp/PHP_ERROR.log");
$_site = require_once(getenv("SITELOAD")."/siteload.php");
$S = new Database($_site);

if(!file_exists($S->path . "/Sitemap.xml")) {
  echo "NO SITEMAP<br>";
  exit();
}

$sitemap = file_get_contents($S->path."/Sitemap.xml");
echo $sitemap;

$ip = $_SERVER['REMOTE_ADDR'];
$agent = $S->escape($_SERVER['HTTP_USER_AGENT']);

error_log("sitemap: $S->siteName, $ip, $agent");

$S->query("select count(*) from information_schema.tables ".
           "where (table_schema = '$S->masterdb') and (table_name = 'bots')");

list($ok) = $S->fetchrow('num');
      
if($ok == 1) {
  try {
    $S->query("insert into $S->masterdb.bots (ip, agent, count, robots, who, creation_time, lasttime) ".
               "values('$ip', '$agent', 1, 16, '$S->siteName', now(), now())");
  }  catch(Exception $e) {
    if($e->getCode() == 1062) { // duplicate key
      $S->query("select who from $S->masterdb.bots where ip='$ip'");
      list($who) = $S->fetchrow('num');
      if(!$who) {
        $who = $S->siteName;
      }
      if(strpos($who, $S->siteName) === false) {
        $who .= ", $S->siteName";
      }
      $S->query("update $S->masterdb.bots set robots=robots | 32, who='$who', count=count+1, lasttime=now() where ip='$ip'");
    } else {
      error_log("sitemap: ".print_r($e, true));
    }
  }
} else {
  error_log("sitemap: $S->siteName bots does not exist in $S->masterdb database");
}

$S->query("select count(*) from information_schema.tables ".
           "where (table_schema = '$S->masterdb') and (table_name = 'bots2')");

list($ok) = $S->fetchrow('num');
      
if($ok == 1) {
  $S->query("insert into $S->masterdb.bots2 (ip, agent, date, site, which, count, lasttime) ".
             "values('$ip', '$agent', current_date(), '$S->siteName', 4, 1, now()) ".
             "on duplicate key update count=count+1, lasttime=now()");
} else {
  error_log("sitemap: $S->siteName bots does not exist in $S->masterdb database");
}
