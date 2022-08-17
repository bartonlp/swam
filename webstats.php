<?php
// All sites do a Symlink to ../bartonphillipsnet for webstats.php. The symlink is needed because we need to
// get the mysitemap.json for the specific website.
// The css is at https://bartonphillips.net/css/webstats.css
// BLP 2021-09-05 -- Update to use this file instead of webstats-new.php. Note the putenv() and
// ini_set() at the start. This is needed because I do not have root access to this server and
// several things just arn't right, not the least that this site uses PHP5 not 7.
// BLP 2021-06-08 -- Moved webstats.ajax.php to bartonphillipsnet/
// BLP 2021-06-05 -- There is no $S->membertable so remove it and tabel7a reference.
// BLP 2021-03-27 -- remove myip table stuff.
// BLP 2021-03-24 -- latest version of tablesorter (not used in some other files!).
// BLP 2021-03-22 -- Removed daycountwhat from daycounts. Added div class scrolling to most tables.
// Special case for Tysonweb.
// BLP 2018-01-07 -- changed tracker order by starttime to lasttime
// BLP 2017-03-23 -- set up to work with https  


putenv("SITELOADNAME=/var/www/zupons.net/vendor/bartonlp/site-class/includes/siteload.php");
ini_set("error_log", "/tmp/PHP_ERROR.log");

// FORM POST and drop into main

if(isset($_POST['submit'])) {
  $siteName = $_POST['site'];

  switch($siteName) {
    case 'Allnatural': 
      header("Location: https://www.allnaturalcleaningcompany.com/webstats.php?blp=8653");
      break;
    case 'BartonlpOrg':
      header("Location: https://www.bartonlp.org/webstats.php?blp=8653");
      break;
    case 'Bartonphillips':
      header("Location: https://www.bartonphillips.com/webstats.php?blp=8653");
      break;
    case 'Tysonweb':
      header("Location: https://www.newbern-nc.info/webstats.php?blp=8653");
      break;
    case 'Newbernzig':
      header("Location: http://www.newbernzig.com/webstats.php?blp=8653");
      break;
    default:
      echo "OPS something went wrong: siteName: $siteName";
      exit();
  }
  exit();
} else {
  $_site = require_once(getenv("SITELOADNAME"));
  ErrorClass::setDevelopment(true);  
  $S = new $_site->className($_site);
}

if(empty($_GET['blp']) || $_GET['blp'] != '8653') { // If blp is empty or set but not '8653' then check $S->myIp
  // myIp can be an array made from the myUri from mysitemap.json
  
  if(is_array($S->myIp)) {
    // Is one of the ips my ip?

    if(!array_intersect([$S->ip], $S->myIp)) {
      echo "<h1>Go Away</h1>";
      exit();
    }
  } else {
    if($S->ip != $S->myIp) {
      echo "<h1>Go Away</h1>";
      exit();
    }
  }
} 

$visitors = [];
$jsEnabled = [];

$S->siteDomain = $S->siteName;

$h->link = <<<EOF
  <link rel="stylesheet" href="https://bartonphillips.net/css/newtblsort.css">
  <link rel="stylesheet" href="https://bartonphillips.net/css/webstats.css"> 
EOF;

$h->css = <<<EOF
<style>
* {
        box-sizing: border-box !important;
}
body { margin: 10px; }
</style>
EOF;

if(is_array($S->myIp)) {
  $myIp = implode(",", $S->myIp);
} else {
  $myIp = $S->myIp;
}

// Set up the javascript variables it needs from PHP

$h->script = <<<EOF
<script>
  var thesite = "$S->siteName";
  var myIp = "$myIp";
</script>
<!-- BLP 2021-03-24 -- this is the latest version of tablesorter -->
<script src="https://bartonphillips.net/tablesorter-master/dist/js/jquery.tablesorter.min.js"></script>
<script src="https://bartonphillips.net/js/webstats.js"></script>
EOF;

$h->title = "Web Statistics";

$h->banner = "<h1 id='maintitle' class='center'>Web Stats For <b>$S->siteName</b></h1>";

list($S->top, $S->footer) = $S->getPageTopBottom($h);

// Render the page

list($top, $footer) = $S->getPageTopBottom();

// Main rendering

$page = getwebstats($S);
echo renderPage($S, $page);
exit();

// =====================
// Functions
// =====================

function getwebstats($S) {
  global $visitors, $jsEnabled;
  
  $T = new dbTables($S);

  // BLP 2021-03-27 -- replacedd myip table with this logic
  
  $tbl =<<<EOF
<table id='blpid' border='1'>
<thead>
<tr><th>myIp</th></tr>
</thead>
<tbody>
EOF;
  foreach($S->myIp as $v) {
    $tbl .= "<tr><td>$v</td></tr>";
  }
  $tbl .= "</table>";
  // end of BLP 2021-03-27
  
  $creationDate = date("Y-m-d H:i:s T");

  $page = <<<EOF
<hr/>
</script>

<h2>From table <i>myip</i></h2>
<p>These are the IP Addresses used by the Webmaster. When these addresses appear in the other tables they are in
<span style="color: red">RED</span>.</p>
$tbl
EOF;

  $sql = "select ip as IP, agent as Agent, count as Count, lasttime as LastTime " .
  "from $S->masterdb.logagent " .
  "where site='$S->siteName' and lasttime >= current_date() order by lasttime desc";

  // BLP 2021-03-27 -- removed callback to blpip along with all ref to blpips.
  
  list($tbl) = $T->maketable($sql, array('attr'=>array('id'=>"logagent", 'border'=>"1")));
  if(!$tbl) {
    $tbl = "<h3 class='noNewData'>No New Data Today</h2>";
  } else {
    $tbl = <<<EOF
<div class="scrolling">
$tbl
</div>
EOF;
  }

  $page .= <<<EOF
<h2 id="table3">From table <i>logagent</i> for today</h2>
<a href="#table4">Next</a>
<h4>Showing $S->siteName for today</h4>
$tbl
EOF;

  // BLP 2021-08-20 -- 
  // Here 'count' is total number of hits (bots and real) so count-realcnt is the number of Bots.
  // 'realcnt' is used in $this->hitCount which is the hit counter at the bottom of some pages.
  // We do not count BOTS in the hitCount.
  // Also we do NOT count me! If isMe() is true we do not count. See myUri.json and mysitemap.json.
  // In myUri.json "/ HOME" is bartonphillips.dyndns.org. I have added the DynDns updater to my
  // home computer's systemd so the IP address should always be the current IP at DynDns.
  
  $sql = "select filename as Page, realcnt as 'Real', (count-realcnt) as 'Bots', lasttime as LastTime ".
  "from $S->masterdb.counter ".
  "where site='$S->siteName' order by lasttime desc";

  $tbl = <<<EOF
<div class="scrolling">
<table id="counter" border="1">
<thead>
<tr><th>Page</th><th>Real</th><th>Bots</th><th>Lasttime</th></tr>
</thead>
<tbody>
EOF;
  
  list($tbl) = $T->maketable($sql, array('attr'=>array('border'=>'1', 'id'=>'counter')));
  
  if(!$tbl) {
    $tbl = "<h3 class='noNewData'>No New Data Today</h2>";
  }

  if($S->reset) {
    $reset = " <span style='font-size: 16px;'>(Reset Date: $S->reset)</span>";
  }
    
  $page .= <<<EOF
<h2 id="table4">From table <i>counter</i> for today</h2>
<a href="#table5">Next</a>
<h4>Showing $S->siteName grand TOTAL hits since last reset $reset for pages viewed today</h4>
<p>'real' is the number of non-bots and 'bots' is the number of robots.</p>
<div class="scrolling">
$tbl
</div>
EOF;

  $today = date("Y-m-d");

  // 'count' is actually the number of 'Real' vs 'Bots'. A true 'count' would be Real + Bots.

  $sql = "select filename as Page, count as 'Real', bots as Bots, lasttime as LastTime ".
         "from $S->masterdb.counter2 ".
         "where site='$S->siteName' and lasttime >= current_date() order by lasttime desc";

  list($tbl) = $T->maketable($sql, array('attr'=>array('border'=>'1', 'id'=>'counter2')));

  if(!$tbl) {
    $tbl = "<h3 class='noNewData'>No New Data Today</h2>";
  }
  
  $page .= <<<EOF
<h2 id="table5">From table <i>counter2</i> for today</h2>
<a href="#table6">Next</a>
<h4>Showing $S->siteName  number of hits TODAY</h4>
$tbl
EOF;

  // Get the footer line
  
  $sql = "select sum(`real`+bots) as Count, sum(`real`) as 'Real', sum(bots) as 'Bots', ".
           "sum(visits) as Visits " .
           "from $S->masterdb.daycounts ".
           "where site='$S->siteName' and lasttime >= current_date() - interval 6 day";

  $S->query($sql);
  list($Count, $Real, $Bots, $Visits) = $S->fetchrow('num');

  // Use 'tracker' to get the number of Visitors ie unique ip accesses.
  // BLP 2018-01-07 -- changed order by from starttime to lasttime.
  
  $S->query("select ip, date(lasttime) ".
            "from $S->masterdb.tracker where lasttime>=current_date() - interval 6 day ".
            "and site='$S->siteName' order by date(lasttime)");

  $Visitors = 0;

  // There should be ONE UNIQUE ip in the rows. So count them into the date.

  $tmp = [];
  
  while(list($ip, $date) = $S->fetchrow('num')) {
    $tmp[$date][$ip] = '';
  }

  foreach($tmp as $d=>$v) { 
    $visitors[$d] = $n = count($v);
    $Visitors += $n;
  }
  
  // Only show items that are not me.
  
  foreach($S->myUri as $v) {
    $me .= "'" . gethostbyname($v) . "',";
  }
  $me = rtrim($me, ",");

  // This screens me out.
  
  $sql = "select count(*), date(starttime) from $S->masterdb.tracker ".
         "where date(starttime)>=current_date() - interval 6 day and site='$S->siteName' and ".
         "isJavaScript & ~(0x201c) and not (isJavaScript & 0x2000) and ip not in($me) ".
         "group by date(starttime) order by date(starttime)";
  
  $S->query($sql);

  $jsenabled = 0;

  while(list($cnt, $date) = $S->fetchrow('num')) {
    $jsEnabled[$date] += $cnt;
    $jsenabled += $cnt;
  }

  $ftr = "<tr><th>Totals</th><th>$Visitors</th><th>$Count</th><th>$Real</th>".
         "<th>$jsenabled</th><th>$Bots</th><th>$Visits</th></tr>";

  // Get the table lines
  
  $sql = "select date as Date, 'visitors' as Visitors, `real`+bots as Count, `real` as 'Real', 'AJAX', ".
           "bots as 'Bots', visits as Visits ".
           "from $S->masterdb.daycounts where site='$S->siteName' and ".
           "lasttime >= current_date() - interval 6 day order by lasttime desc";

  function visit(&$row, &$rowdesc) {
    global $visitors, $jsEnabled;

    $row['Visitors'] = $visitors[$row['Date']];
    $row['AJAX'] = $jsEnabled[$row['Date']];
    return false;
  }
  
  list($tbl) = $T->maketable($sql, array('callback'=>'visit', 'footer'=>$ftr,
                                         'attr'=>array('border'=>"1", 'id'=>"daycount")));

  if(!$tbl) {
    $tbl = "<h3 class='noNewData'>No New Data Today</h2>";
  }
    
  $page .= <<<EOF
<h2 id="table6">From table <i>daycount</i> for seven days</h2>
<a href="#table7">Next</a>

<h4>Showing $S->siteName for seven days</h4>
<p>'Visitors' is the number of distinct IP addresses (via 'tracker' table).<br>
'Count' is the sum of 'Real' and 'Bots', the total number of HITS.<br>
'Real' is the number of non-robots.<br>
'AJAX' is the number of non-robots with AJAX functioning (via 'tracker' table) that are NOT Webmaster.<br>
'Bots' is the number of robots.<br>
'Visits' are hits outside of a 10 minutes interval.<br>
So if you come to the site from two different IP addresses you would be two 'Visitors'.<br>
If you hit our site 10 times the sum of 'Real' and 'Bots' would be 10.<br>
If you hit our site 5 time within 10 minutes you will have only one 'Visits'.<br>
If you hit our site again after 10 minutes you would have two 'Visits'.</p>
$tbl
EOF;
  return $page;
}

// Display the page with the $page.

function renderPage($S, $page) {
  // The analysis files are updated once a day by a cron job.
  $T = new dbTables($S);

  $analysis = file_get_contents("https://bartonphillips.net/analysis/$S->siteName-analysis.i.txt");
  if(!$analysis) $errMsg = "https://bartonphillips.net/analysis/$S->siteName-analysis.i.txt: NOT FOUND";

  // Callback for tracker below

  function trackerCallback(&$row, &$desc) {
    global $S;

    $ip = $S->escape($row['ip']);

    $row['ip'] = "<span class='co-ip'>$ip</span><br>";
    $row['refid'] = preg_replace('/\?.*/', '', $row['refid']);

    if(($row['js'] & 0x2000) === 0x2000) {
      $desc = preg_replace("~<tr>~", "<tr class='bots'>", $desc);
    }
    $row['js'] = dechex($row['js']);
    $t = $row['difftime'];
    if(is_null($t)) {
      //echo "$ip, t=$t<br>";
      return;
    }
    
    $hr = $t/3600;
    $min = ($t%3600)/60;
    $sec = ($t%3600)%60;

    $row['difftime'] = sprintf("%u:%02u:%02u", $hr, $min, $sec);
  }

  // BLP 2018-01-07 -- changed from order by starttime to lasttime
  
  $sql = "select ip, page, agent, starttime, endtime, difftime, isJavaScript as js, refid ".
         "from $S->masterdb.tracker ".
         "where site='$S->siteName' and starttime >= current_date() - interval 24 hour ". 
         "order by lasttime desc";

  list($tracker) = $T->maketable($sql, array('callback'=>'trackerCallback',
                                             'attr'=>array('id'=>'tracker', 'border'=>'1')));

  $tracker = <<<EOF
<div class="scrolling">
$tracker
</div>
EOF;
  
  function botsCallback(&$row, &$desc) {
    global $S;

    $ip = $S->escape($row['ip']);

    $row['ip'] = "<span class='bots-ip'>$ip</span><br>";
  }
  
  $sql = "select ip, agent, count, hex(robots) as bots, site, creation_time as 'created', lasttime ".
         "from $S->masterdb.bots ".
         "where site like('%$S->siteName%') and lasttime >= current_date() - interval 24 hour and count !=0 order by lasttime desc";

  list($bots) = $T->maketable($sql, array('callback'=>'botsCallback',
                                          'attr'=>array('id'=>'robots', 'border'=>'1')));

  $bots = <<<EOF
<div class="scrolling">
$bots
</div>
EOF;
  
  function bots2Callback(&$row, &$desc) {
    global $S;

    $ip = $S->escape($row['ip']);

    $row['ip'] = "<span class='bots2-ip'>$ip</span><br>";
  }
  
  $sql = "select ip, agent, site, which, count from $S->masterdb.bots2 ".
         "where site='$S->siteName' and date >= current_date() - interval 24 hour order by lasttime desc";

  list($bots2) = $T->maketable($sql, array('callback'=>'bots2Callback',
                                           'attr'=>array('id'=>'robots2', 'border'=>'1')));

  $bots2 = <<<EOF
<div class="scrolling">
$bots2
</div>
EOF;
  
  $date = date("Y-m-d H:i:s T");

  // BLP 2021-06-23 -- Only bartonphillips.com has a members table.
  // BLP 2021-09-05 -- Swam also has a members table but it is very different from the
  // bartonphillips table. It does not have ip or agent.
  
  if($S->memberTable) {
    if($S->siteName == "Swam") {
      $sql = "select name, email from $S->memberTable";
    } else {
      $sql = "select name, email, ip, agent, created from $S->memberTable";
    }
    list($tbl) = $T->maketable($sql, array('attr'=>array('id'=>'members', 'border'=>'1')));
    
    $mtbl = <<<EOF
<h2 id="table10">From table <i>$S->memberTable</i></h2>
<a href="#analysis-info">Next</a>
<div id="memberstable">
$tbl
</div>
EOF;
    $mTable = "<li><a href='#table10'>Goto Table: $S->memberTable</a></li>";
    $botsnext = "<a href='#table10'>Next</a>";
  } else {
    $bptsnext = "<a href='#analysis-info'>Next</a>";
  }

  $siteName = $S->siteName;
  
  $ret = <<<EOF
$S->top
<div id="content">
$errMsg
$form
<main>
<p>$date</p>
<ul>
   <li><a href="#table3">Goto Table: logagent</a></li>
   <li><a href="#table4">Goto Table: counter</a></li>
   <li><a href="#table5">Goto Table: counter2</a></li>
   <li><a href="#table6">Goto Table: daycounts</a></li>
   <li><a href="#table7">Goto Table: tracker</a></li>
   <li><a href="#table8">Goto Table: bots</a></li>
   <li><a href="#table9">Goto Table: bots2</a></li>
$mTable
   <li><a href="#analysis-info">Goto Analysis Info</a></li>
</ul>

<div id="hourly-update">
$page
</div>

<h2 id="table7">From table <i>tracker</i> for last 24 hours</h2>
<a href="#table8">Next</a>
<h4>Only Showing $S->siteName</h4>
<p>'js' is hex. 1, 2, 32(x20), 64(x40), 128(x80), 256(x100), 512(x200) and 4096(x1000) are done by 'webstats.js'.<br>
4, 8 and 16(x10) via an &lt;img&gt; tag in the header<br>
16384 (x4000) var an attempt to read 'csstest.css' from the 'head.i.php' file.<br>
1=start, 2=load, 4=script, 8=normal, 16(x10)=noscript,<br>
32(x20)=beacon/pagehide, 64(x40)=beacon/unload, 128(x80)=beacon/beforeunload,<br>
256(x100)=tracker/beforeunload, 512(x200)=tracker/unload, 1024(x400)=tracker/pagehide,<br>
4096(x1000)=tracker/timer: hits once every 5 seconds via ajax.</br>
8192(x2000)=SiteClass (PHP) determined this is a robot via analysis of the 'user agent' or scan of 'bots'.<br>
16384(x4000)=tracker/csstest<br>
The 'starttime' is done by SiteClass (PHP) when the file is loaded.<br>
Rows with 'js' zero (0) are <b>curl</b> or something like <b>curl</b> (wget, lynx, etc) and are probaly really <b>ROBOTS</b>.</p>

$tracker
<h2 id="table8">From table <i>bots</i> for Today</h2>
<a href="#table9">Next</a>
<h4>Showing ALL <i>bots</i> for today</h4>
<p>The 'bots' field is hex.<br>
The 'count' field is the total count since 'created'.<br>
From 'rotots.txt': Initial Insert=1, Update= OR 2.<br>
From 'SiteClass' scan: Initial Insert=4, Update= OR 8.<br>
From 'Sitemap.xml': Initial Insert=16(x10), Update= OR 32(x20).<br>
From 'tracker' cron: Inital Insert=64(x40), Update= OR 128(x80).<br>
From CRON indicates a Zero in the 'tracker' table: 258(x100).<br>
So if you have a 1 you can't have a 2 and visa versa.</p>
$bots
<h2 id="table9">From table <i>bots2</i> for Today</h2>
$botsnext
<h4>Showing ALL <i>bots2</i> for today</h4>
<p>'which' is 0 for zero in tracker, 1 for 'robots.txt', 2 for 'SiteClass', 4 for 'Sitemap.xml'.<br>
The 'count' field is the number of hits today.</p>
$bots2
$mtbl
<div id="analysis">
$analysis
</div>
<hr>
</main>
</div>
$S->footer
EOF;

  return $ret;
}
