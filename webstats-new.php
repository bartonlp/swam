<?php
// BLP 2016-11-28 -- NOTE: THIS IS NOT THE SAME AS THE ONE IN bartonlp.com! It does use
// http://bartonphillips.net/js/webstats-new.js.
// This is for www.swam.us (www.zupons.net/swam).
putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
$_site = require_once(getenv("SITELOAD")."/siteload.php");
$S = new $_site->className($_site);
$T = new dbTables($S);

// AJAX
// via ajax proxy for curl http://ipinfo.io/<ip>

if($_POST['page'] == 'curl') {
  $ip = $_POST['ip'];

  $cmd = "http://ipinfo.io/$ip";
  $ch = curl_init($cmd);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $loc = json_decode(curl_exec($ch));
  $locstr = "Hostname: $loc->hostname<br>$loc->city, $loc->region $loc->postal<br>Location: $loc->loc<br>ISP: $loc->org<br>";

  echo $locstr;
  exit();
}

// AJAX
// via ajax findbot. Search the bots table looking for all the records with ip

if($_POST['page'] == 'findbot') {
  $ip = $_POST['ip'];

  $human = array(3=>"Robots", 0xc=>"SiteClass", 0x30=>"Sitemap", 0xc0=>"cron");

  $S->query("select agent, who, robots from $S->masterdb.bots where ip='$ip'");

  $ret = '';

  while(list($agent, $who, $robots) = $S->fetchrow('num')) {
    $h = '';
    
    foreach($human as $k=>$v) {
      $h .= $robots & $k ? "$v " : '';
    }

    $bot = sprintf("%X", $robots);
    $ret .= "<tr><td>$who</td><td>$agent</td><td>$bot</td><td>$h</td></tr>";
  }

  if(empty($ret)) {
    $ret = "<div style='background-color: pink; padding: 10px'>$ip Not In Bots</div>";
  } else {
    $ret = <<<EOF
<style>
#FindBot table {
  width: 100%;
}
#FindBot table td:first-child {
  width: 20%;
}
#FindBot table td:nth-child(2) {
  word-break: break-all;
  width: 70%;
}
#FindBot table td:nth-child(3) {
  width: 10%;
}
#FindBot table * {
  border: 1px solid black;
}
</style>
<table>
<thead>
  <tr><th>$ip</th><th>Agent</th><th>Bots</th><th>Human</th></tr>
</thead>
<tbody>
$ret
</tbody>
</table>
EOF;
  }
  
  echo $ret; 
  exit();
}

// AJAX
// Get the info form the tracker table again.
// NOTE this is called from js/webstats-new.js which always uses this file for its AJAX calls!!

if($_POST['page'] == 'gettracker') {
  // Callback function for maketable()

  function callback1(&$row, &$desc) {
    global $S, $ipcountry;

    $ip = $S->escape($row['ip']);

    $co = $ipcountry[$ip];

    $row['ip'] = "<span class='co-ip'>$ip</span><br><div class='country'>$co</div>";

    //echo "js: " . $row['js'] . "<br>";
    
    if(($row['js'] & 0x2000) === 0x2000) {
      $desc = preg_replace("~<tr>~", "<tr class='bots'>", $desc);
    }
    $row['js'] = dechex($row['js']);
  } // End callback

  $site = $_POST['site'];
  
  //$ipcountry = json_decode($_POST['ipcountry'], true);

  $sql = "select ip, page, agent, starttime, endtime, difftime, isJavaScript as js ".
         "from $S->masterdb.tracker " .
         "where site='$site' and starttime >= current_date() - interval 24 hour ". 
         "order by starttime desc";

  list($tracker) = $T->maketable($sql, array('callback'=>callback1,
                                             'attr'=>array('id'=>'tracker', 'border'=>'1')));
  echo $tracker;
  exit();
}
// End of Ajax

// Get info for main page.

$sql = "select filename, count, realcnt, lasttime from counter where site='Swam' and lasttime>current_date() order by lasttime";
list($counter) = $T->maketable($sql, array('attr'=>array('id'=>'counter', 'border'=>'1')));

$sql = "select filename, date, count, bots, lasttime from counter2 where site='Swam' and lasttime>current_date() order by lasttime";
list($counter2) = $T->maketable($sql, array('attr'=>array('id'=>'counter2', 'border'=>'1')));

// This is the callback for maketable() for 'tracker'.

function callback(&$row, &$desc) {
  global $S, $ipcountry;

  $ip = $S->escape($row['ip']);

  $co = $ipcountry[$ip];

  $row['ip'] = "<span class='co-ip'>$ip</span><br><div class='country'>$co</div>";

  if(($row['js'] & 0x2000) === 0x2000) {
    $desc = preg_replace("~<tr>~", "<tr class='bots'>", $desc);
  }
  $row['js'] = dechex($row['js']);
}

// Get all 'tracker' entries for our site and starttime for the last 24 hours.

$sql = "select ip, page, agent, starttime, endtime, difftime, isJavaScript as js ".
       "from $S->masterdb.tracker where site='$S->siteName' and starttime >= current_date() - interval 24 hour ". 
       "order by starttime desc";

list($tracker) = $T->maketable($sql, array('callback'=>callback,
                                            'attr'=>array('id'=>'tracker',
                                            'border'=>'1')));

// Get all 'bots' entries for this day.

$sql = "select ip, agent, count, hex(robots) as bots, who, creation_time as 'created', lasttime ".
       "from $S->masterdb.bots ".
       "where lasttime >= current_date() and count !=0 order by lasttime desc";

list($bots) = $T->maketable($sql, array('attr'=>array('id'=>'robots', 'border'=>'1')));

// Get all 'bots2' entries for this day

$sql = "select ip, agent, site, which, count from $S->masterdb.bots2 ".
       "where date >= current_date() order by lasttime desc";

list($bots2) = $T->maketable($sql, array('attr'=>array('id'=>'robots2', 'border'=>'1')));

// figure out the timezone of the server by doing 'date' which returns
// something like: Sun Dec 28 12:14:44 PST 2014
// Get the first letter of the time zone, like M for MST etc.

$date = date("Y-m-d H:i:s T");

// Now set up the link and scripts for the '$top'

$h->link = <<<EOF
  <link rel="stylesheet" href="http://bartonphillips.net/js/tablesorter/themes/blue/style.css">
EOF;

// BLP 2016-11-28 -- $S->myIp can be a string or an array.

if(is_array($S->myIp)) {
  $myIp = implode(",", $S->myIp);
} else {
  $myIp = $S->myIp;
}

$h->extra = <<<EOF
  <script src="http://bartonphillips.net/js/tablesorter/jquery.tablesorter.js"></script>
  <script src="http://bartonphillips.net/js/webstats-new.js"></script>
  <script>
var ipcountry = null; // We do not have ipcountry set up yet BLP 2016-11-28 -- 
var thesite = "$S->siteName"; // This siteName
var myIp = "$myIp"; // My Ip addresses
jQuery(document).ready(function($) {
  $("#counter, #counter2").tablesorter().addClass('tablesorter'); // attach class tablesorter to all except our counter
});
  </script>
EOF;

$h->title = "Web Statistics";

$h->css = <<<EOF
  <style>
table {
  background-color: white;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th,
table.tablesorter tbody tr td {
  padding-right: .8rem;
  font-size: .8rem;
}
* {
  box-sizing: border-box !important;
}
/* #swam is the Member's table. set the size and make it scrollable and inline-block */
#swam {
  height: 10rem;
  display: inline-block;
  overflow: auto;
}
  </style>
EOF;

$sitename = strtolower($S->siteDomain);
$h->banner = "<h1 id='maintitle'>Web Stats For <b>$sitename</b></h1>";

// Now get the top and footer

list($top, $footer) = $S->getPageTopBottom($h);

// Render the page

echo <<<EOF
$top
<main>
<p>$date</p>
<h2>From table <i>counter</i> one day</h2>
$counter
<h2>From table <i>counter2</i> one day</h2>
$counter2
<h2>From table <i>tracker</i> (real time) for last 24 hours</h2>
<p>'js' is hex. 1, 2, 32(x20), 64(x40), 128(x80, 256(x100), 512(x200) and 4096(x1000) are done via 'ajax'.<br>
4, 8 and 16(x10) via an &lt;img&gt;<br>
1=start, 2=load, 4=script, 8=normal, 16(x10)=noscript,<br>
32(x20)=beacon/pagehide, 64(x40)=beacon/unload, 128(x80)=beacon/beforeunload,<br>
256(x100)=tracker/beforeunload, 512(x200)=tracker/unload, 1024(x400)=tracker/pagehide,<br>
4096(x1000)=tracker/timer: hits once every 5 seconds via ajax.</br>
8192(x2000)=SiteClass (PHP) determined this is a robot via analysis of user agent or scan of 'bots'.<br>
The 'starttime' is done by SiteClass (PHP) when the file is loaded.</p>
$tracker
<h2>Table Eight from table <i>bots</i> (real time) for Today</h2>
<p>The 'bots' field is hex.<br>
The 'count' field is the total count since 'created'.<br>
From 'rotots.txt': Initial Insert=1, Update= OR 2.<br>
From app scan: Initial Insert=4, Update= OR 8.<br>
From 'Sitemap.xml': Initial Insert=16(x10), Update= OR 32(x20).<br>
From 'tracker' cron: Inital Insert=64(x40), Update= OR 128(x80).<br>
So if you have a 1 you can't have a 4 and visa versa.</p>
$bots
<h2>Table Nine from table <i>bots2</i> (real time) for Today</h2>
<p>'which' is 1 for 'robots.txt', 2 for the application, 4 for 'Sitemap.xml'.<br>
The 'count' field is the number of hits today.</p>
$bots2
</main>
$footer
EOF;
