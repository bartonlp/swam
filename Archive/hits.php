<?php
// Display Web Statistics for Granby Rotary
$_site = require_once("/var/www/zupons.net/vendor/bartonlp/site-class/includes/siteload.php");
$S = new $_site->className($_site);
$T = new dbTables($S);

$WEBMASTER = "192,58";

// This section is the Ajax back end for this page

switch($_GET["table"]) {
  case "pageHits":
    // Ajax
    $query = "select filename as Filename, count as Count from counter where lasttime>current_date() order by count desc"; //order by last desc";
    list($table) = $T->maketable($query, array('attr'=>array('id'=>"pageHits", 'border'=>'1')));
    echo $table;
    exit();

  case "ipHits":
    // Ajax
     $query = "select ip as IP, id as ID, count as Count from logip where lasttime>current_date() order by count desc"; //order by lasttime desc";
     list($table) = $T->maketable($query, array('attr'=>array('id'=>"ipHits", 'border'=>'1')));
     echo $table;
     exit();

  case "ipAgentHits":
    function ipagentcallback(&$row, &$desc) {
      global $WEBMASTER;
      
      $ar = explode(',', $WEBMASTER);
      
      $tr = "<tr";
      if($row[id]) {
        if(in_array($row['id'], $ar)) {
          $tr .= " class='blp'";
        } 
      } else {
        $tr .= " class='noId'";
      }

      $tr .= ">";

      $desc = preg_replace("/<tr>/", $tr, $desc);
      return false;
    }

    $query = "select l.ip as IP, l.agent as Agent, l.id, concat(r.fname, ' ', r.lname) as Name, " .
             "l.lasttime as LastTime from logagent as l left join swammembers as r on l.id=r.id " .
             "where l.lasttime>current_date() order by l.lasttime desc";
    
    list($table) = $T->maketable($query, array('callback'=>ipagentcallback, 'attr'=>array('id'=>"ipAgentHits", 'border'=>'1')));
    echo $table;
    exit();

/*    
  case "osoursite":
    $ar = array('OS'=>array('types'=>array(), 'table'=>""), 'Browser'=>array('types'=>array(), 'table'=>""));
    $types = array('Windows', 'Linux', 'Macintosh');
    $ar['OS']['types'] = $types;
    $types = array('Opera', 'Chrome', 'Safari', 'SeaMonkey', 'Firefox', 'MSIE');
    $ar['Browser']['types'] = $types;

    $table = <<<EOF
<table id="osoursite1" border='1' style="width: 100%">
<thead>
<tr><th>OS</th><th>Visits</th><th>%</th><th>Records</th><th>%</th><th>Visitors</th><th>%</th></tr>
</thead>
<tbody>

EOF;

    $ar['OS']['table'] = $table;
    
    $table = <<<EOF
<table id="osoursite2" border='1' style="width: 100%">
<thead>
<tr><th>Browser</th><th>Visits</th><th>%</th><th>Records</th><th>%</th><th>Visitors</th><th>%</th></tr>
</thead><tbody>

EOF;

    $ar['Browser']['table'] = $table;

    // logagent is only updated in login.php
    // $result = $S->query("select sum(count) as count from logagent");
    // I have changed logagent to be updated on every access to a page.

    // Don't show webmaster

    $S->query("select sum(count) as count from memberpagecnt where id not in($WEBMASTER)");
    list($total) = $S->fetchrow('num');
    $S->query("select count(*) as count from memberpagecnt where id not in($WEBMASTER)");
    list($records) = $S->fetchrow('num');
    $S->query("select id from memberpagecnt where id not in($WEBMASTER) group by id");
    list($totalmembers) = $S->fetchrow('num');

    foreach($ar as $k=>$t) {
      $cnt = 0;
      foreach($t['types'] as $type) {
        switch($type) {
          case "Chrome":
            $stype = "Chrome%Safari";
            break;
          case "Safari":
            $stype = "Safari%') and agent not like('%Chrome";
            break;
          default:
            $stype = $type;
            break;
        }
        // dont count webmaster
        $n = $S->query("select sum(count) as count from memberpagecnt where id not in($WEBMASTER) and agent like('%$stype%')");
        $count = 0;
        if($n) {
          $row = $S->fetchrow('assoc');
          $count = $row['count'];
          $cnt += $count; 
          $percent = number_format($count / $total * 100, 2, ".", ",");
          $count = number_format($count, 0, "", ",");
          $S->query("select count(*) from memberpagecnt where id not in($WEBMASTER) and agent like('%$stype%')");
          list($un) = $S->fetchrow('num');
          $perun = number_format($un / $records * 100, 2, ".", ",");
          $un = number_format($un, 0, "", ",");
          $S->query("select id from memberpagecnt where id not in($WEBMASTER) and agent like('%$stype%') group by id");
          list($mem) = $S->fetchrow('num');
          $permem = number_format($mem / $totalmembers * 100, 2, ".", ",");
          $mem = number_format($mem, 0, "", ",");
        }
        $ar[$k]['table'] .= <<<EOF
<tr><th style='text-align: left'>$type</th><td style='text-align: right'>$count</td>
<td style='text-align: right'>$percent</td><td style='text-align: right'>$un</td>
<td style='text-align: right'>$perun</td>
<td style='text-align: right'>$mem</td>
<td style='text-align: right'>$permem</td></tr>

EOF;
      }
      $cnt = $total - $cnt;
      $percent = number_format($cnt / $total * 100, 2, ".", ",");
      $cnt = number_format($cnt, 0, "", ",");
      $ar[$k]['table'] .= <<<EOF
</tbody>
</table>
EOF;
}

      $ftotal = number_format($total, 0, "", ",");
      $records = number_format($records, 0, "", ",");

      $tbl = <<<EOF
<table border='1'>
<tbody>
<tr><td>Total Records</td><td style="text-align: right; padding: 5px">$records</td></tr>
<tr><td>Total Visits</td><td style="text-align: right; padding: 5px">$ftotal</td></tr>
<tr><td>Total Visitors</td><td style="text-align: right; padding: 5px">$totalmembers</td</tr>
</tbody>
</table>

<div id='osBrowserCntDiv' class='wrapper' style='width: 80%'>
<br>
<div id='OScnt' style="margin-bottom: 20px">
{$ar['OS']['table']}
</div>
<div id='browserCnt'>
{$ar['Browser']['table']}
</div>
</div>
<p>Note that in some cases a &quot;Visitor&quot; (that is an IP Address) has used two or more different browsers which makes the
total visitor and percent visitors more than the number of IP Addresses. For example a couple of our members use both Firefox (good)
and Microsoft Internet Explorer (bad) on the same computer (IP Adddress).</p>
<p>Also note that some of our visitors use several different computers (work, home, laptop etc). And finally most visitors have
dynamic rather than static IP Addresses supplied by there Internet Service Provider (ISP). That means that from time to time
their ISP changes their IP Address. All very complicated.</p>
<p>It is interesting to note that visitors using Firefox visite the site a lot more than those using MSIE (55% to 38%),
but more visitors use MSIE than do Firefox (53% to 50%). Also, while 25% of our visitors use a Mac they don't visit our
site very often, only 8% of the visits are made by Mac users. Does this tell us something?</p>
<ul style="clear: both; padding-top: 20px">
<li>The <i>Visits</i> columns show the total number of times a member with the <b>OS</b> or <b>Browser</b> visited our site.</li>
<li>The <i>Records</i> columns shows the total number of times the <b>IP/AGENT</b> was used to access our site (the
&quot;User Agent String&quot; has information about the OS and Browser.)</li>
<li>The <i>Visitors</i> columns show the number of members using the <b>OS</b> or <b>Browser</b>.</li>
</ul>
<p>This table does <b>not</b> include the accesses by the Webmaster as that would skew the results toward Linux and Firefox.</p>

EOF;
      echo $tbl;
      exit();
*/      
}

// ********************************************************************************
// End of Ajax
// Start of Main Page logic

$h->extra = <<<EOF
  <link rel="stylesheet" href="http://bartonphillips.net/css/tablesorter.css">
  <link rel="stylesheet" href="http://bartonphillips.net/css/hits.css">
  <script src="http://bartonphillips.net/js/tablesorter/jquery.metadata.js"></script>
  <script src="http://bartonphillips.net/js/tablesorter/jquery.tablesorter.js"></script>
  <script>
jQuery(document).ready(function($) {
  var tablename="{$_GET['table']}";

  // create a div for name popup
  $("body").append("<div id='popup' "+
                   "style='position: absolute; display: none; border: 2px solid black; "+
                   "background-color: #8dbdd8; padding: 5px;'></div>");

  $("h2.table").each(function() {
    $(this).append(" <span class='showhide' style='color: red'>Show Table</span>");
  });

  $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter
  $("#Swammembers, #otherMemberHits, #memberNot").tablesorter();
  //$("#OScnt table").tablesorter({ sortList:[[1,1]] , headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}}});
  //$("#browserCnt table").tablesorter({ sortList:[[1,1]], headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}} });

  $("div.table").hide();

  if(tablename != "") {
    $("div[name='"+tablename+"']").show();
    $("div[name='"+tablename+"']").prev().children().first().text("Hide Table");
  }
  $(".showhide").css("cursor", "pointer");

  $(".showhide").toggle(function() {
    tablename = $(this).parent().next().attr("name");
    var tbl = $("#"+tablename); // The <table
    var t = $(this); // The span
    var s = t.parent().next(); // <div class="table"
    if(tbl.length) {
      s.show(); // show the <div class="table"
      t.text("Hide Table");
    } else {
      $("body").css("cursor", "wait");
      t.css("cursor", "wait");

      $.get("$S->self", { table: tablename }, function(data) {
        $("div[name='"+tablename+"']").append(data);
        $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter
        s.show();
        t.text("Hide Table");

        // Switch for the specific table

        switch(tablename) {
          case "osoursite":
            $("#osoursite1, #osoursite2").tablesorter({ sortList: [[1,1]],
               headers: { 1: {sorter: "currency"}, 3: {sorter: "currency"}, 5: {sorter: "currency"} } });
            break;
          case "pageHits":
            $("#pageHits").tablesorter(); //{ sortList:[[1,1]] }
            break;

          case "ipAgentHits":
            $("#ipAgentHits").tablesorter(); // { sortList:[[4,1]] }

            // Set up the Ip Agent Hits table
            $("#ipAgentHits").before("<p>You can toggle the display of only members or all visitors \
<input type='submit' value='Show/Hide NonMembers' id='hideShowNonMembers' /><br/> \
You can toggle the display of the ID \
<input type='submit' value='Show/Hide ID' id='hideShowIdField' /><br/> \
Your can taggle the display of the Webmaster \
<input type='submit' value='Show/Hide Webmaster' id='hideShowWebmaster' /></p>");

            // Ip Agent Hits.
            // Hide all ids of zero
            $(".noId").hide();

            // Button to toggle between all and members only in Ip Agent Hits
            // table
            $("#hideShowNonMembers").toggle(function() {
              $(".noId").not("[name='blp']").show();
              $(".botmsg").css("visibility", "visible");
            }, function() {
              $(".noId").hide();
              $(".botmsg").css("visibility", "hidden");
            });

            // Hide the ID field in Ip Agent Hits table
            $("#ipAgentHits td:nth-child(3)").hide();
            $("#ipAgentHits thead th:nth-child(3)").hide();

            // Button to toggle between no ID and showing ID in Ip Agent Hits
            // table
            $("#hideShowIdField").toggle(function() {
              $("#ipAgentHits td:nth-child(3)").show();
              $("#ipAgentHits thead th:nth-child(3)").show();
            }, function() {
              $("#ipAgentHits td:nth-child(3)").hide();
              $("#ipAgentHits thead th:nth-child(3)").hide();
            });

            // Hide the webmaster (me) in the Ip Agent Hits table
            $(".blp").hide();

            // Button to toggle between hide and show of Webmaster in Ip Agents
            // Hits Table
            $("#hideShowWebmaster").toggle(function() {
              $(".blp").show();
            }, function() {
              $(".blp").hide();
            });
            break;

         case "ipHits":
           $("#ipHits").tablesorter(); // {sortList:[[2,1]] }
           // Set up Ip Hits
           $("#ipHits").before("<p>Move the mouse over ID to see the member&apos;s name.<br/> \
Click to toggle <i>all</i> or <i>members-only</i> \
<input id='membersonly' type='submit' value='toggle all/members-only' /></p>");

           $("#ipHits tbody tr td:nth-child(2)").hover(function(e) {
             var name = "Non Member";
             if($(this).text() != '0') {
               name = $("#Id_"+$(this).text()).text();
             }
             $("#popup").text(name).css({ top: e.pageY+20, left: e.pageX }).show();
           }, function() {
             $("#popup").hide();
           });

           // Hide and mark all IDs that are zero in the Ip Hits table
           $("#ipHits tbody tr td:nth-child(2)").each(function() {
             var \$this = $(this);
             if(\$this.text() == '0') \$this.parent().addClass('ipHitsNoId').hide();
           });

           // Members only button toggles members and all in Ip Hits table
           $("#membersonly").toggle(function() {
             $(".ipHitsNoId").show();
           }, function() {
             $(".ipHitsNoId").hide();
           });
           break;
        } // end of switch
        $("body").css("cursor", "default");
        t.css("cursor", "pointer");
      });
    }
  }, function() {
    $(this).parent().next().hide();
    $(this).text("Show Table");
  });

});

  </script>

  <style type="text/css">
#daycount tbody tr td { /*visitors*/
  text-align: right;
}
#daycount tfoot tr th {
  text-align: right;
}
#daycount tfoot tr th:first-child {
  text-align: center;
}
  </style>
EOF;

$h->title = "Web Site Statistics";
$h->banner = "<h2 class='center'>Web Site Statistics</h2>";
list($top, $footer) = $S->getPageTopBottom($h);

// Member Hits

// NOTE the last field is San Diego time so in all cases add on hour
// via addtime().

// Get the last person to access the webpage

$S->query("select concat(fname, ' ', lname) as name, addtime(visittime, '1:0') as lastvisit from swammembers ".
          "where visittime = (select max(visittime) from swammembers where visits != 0 ".
          "and id not in ($WEBMASTER, '$S->id'))");

list($fullName, $lastAccess) = $S->fetchrow('num');

// Get the count of active and honorary members.

$S->query("select count(*) as count from swammembers");

list($count) = $S->fetchrow('num');

// Members Who Visited

$query = "select id, concat(fname, ' ', lname) as name, visits,  addtime(visittime, '1:0') as lastvisit from swammembers ".
         "where visits != 0 order by lastvisit desc";

list($table, $result, $membercnt, $hdr) = $T->maketable($query, array('attr'=>array('id'=>'Swammembers', 'border'=>'1')));

echo <<<EOF
$top
<h3 style="text-align: center">Total Members: $count</h3>
<div>
<p>Members using web site=$membercnt.</p>
<h2>Members Who Visited<br/>
Our Site</h2>
$table

<p>Most recient access (not counting this one):
<ul>
   <li>$lastAccess</li>
   <li>$fullName</li>
</ul>
</p>

EOF;

//**********************************************************
// Start of Show/Hide Tables
//**********************************************************

$table = "";

echo <<<EOF
<hr/>
<h2 class='table'>Page Hits</h2>
<div class='table' name="pageHits">
<p>From the <i>counter</i> table.</p>
<p>Count for all pages on the site.</p>
</div>
<hr/>

<h2 class='table'>IP Hits</h2>
<div class='table' name="ipHits">
<p>From the <i>logip</i> table.</p>
<p>Data is for accesses to all pages</p>
<a name='ipHits'></a>
</div>
<hr/>

<h2 class='table'>IP-AGENT Hits</h2>
<div class='table' name="ipAgentHits">
<p>From the <i>logagent</i> table with join to <i>rotarymembers</i> table.</p>
<p>Data is for accesses to our Home Page only</p>
<a name='ipAgentControl'></a>
</div>
<hr/>

<!-- Side by Side -->
<!--
<h2 class="table">OS and Browsers for Our Site</h2>
<div class="table" name="osoursite">
<p>From <i>memberpagecnt</i> table. This shows <b>Only Members</b> ie. people who have <i>Logged In</i>.</p>
</div>
<hr/>
-->
EOF;

echo <<<EOF
<p>There are several interesting thing to notice in the tables on this page:
<ul>
   <li>The <i>ID</i> field is an internal database number that has no outside meaning. It is simply and index
      into the MySql database that has member information.</li>
   <li>If the <i>ID</i> field is zero of blank then the visitor is not a member of our club or has not yet
      logged in. Be sure to login once.</li>
   <li>Some of the agents are really &quot;Bots&quot;. For example, &quot;Slurp&quot; and &quot;Googlebot&quot;.
      &quot;Bots&quot; are robots that are used to scan the web for interesting thing. In the two cases sited
      here they are indexing the web for <i>Yahoo</i> and <i>Google</i> two of the biggest search providers.</li>
   <li>Most of our members are using <i>Firefox</i> or <i>Safari</i> (Mac OS X). It is good to see that most
      members are smart enough not to use <i>Internet Explorer</i>.</li>
   <li>I, Barton Phillips (the Webmster), have used several different web browsers. I do this to try and make sure the site
      looks OK no matter what a visitor is using as a browser, even a text only browser like <i>Links</i> or
      <i>lynx</i>. I have not been able to test the site to see if special browsers for people with disabilities
      work correctly. If anyone can help me here I would appreciate it.</li>
   <li>Unfortunately, most member are using Microsoft Windows. Fortunately, no one is using old versions of Windows like
      95, 98, or ME. There are a couple of Mac OS X members, and I seem to be the only Linux user (though I have
      tested the site with Windows XP and Windows 7).</li>
</ul>
More information on <b>Bots</b> can be found <a href='http://www.jafsoft.com/searchengines/search_engines.html'>here</a>.<br/>
As I see more interesting trends I will report them here.</p>

<hr/>
$footer
EOF;
