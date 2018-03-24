<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

if($id = $_GET['memberid']) {
  $id = $S->setIdCookie($id);
  $S->checkId($id);
  $S->doCounts();
}
$h->extra = <<<EOF
  <script language="javascript" type="text/javascript">
if (self != top) {
    if (document.images)
        top.location.replace(window.location.href);
    else
        top.location.href = window.location.href;
}
  </script>
  <style type="text/css">
#weather-nonmembers {
  width: 13em;
  border: 1px solid black;
  padding: 1em 1em 0 1em;
  margin-top: 1em;
  margin-left: 1em;
  margin-right: auto;
}
  </style>
EOF;

$h->title = "South West Aquatic Masters Home Page -- Coached Masters ".
            "Swimming Workouts and Competition Swimming";

$h->banner = <<<EOF
<!--<div align=center>
               <img src="images/ny14.gif" alt=**>
               <h1>Happy New Year</h1>
               </div>-->
<div class="center">
<!--<div align=center>
               <img src="images/xmastreeblinkinganm.gif" alt=**>
               <h1>Happy Holidays</h1>
               </div>-->
  <h2><a href="http://www.piercecollege.edu/">Los Angeles Pierce College</a></h2>

  <p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
  (LAPC) Extension Office Phone Number (818)719-6425 <br>
  <a href="http://www.mapquest.com/cgi-bin/ia_free?width=400&amp;height=400&amp;icontitles=yes&amp;level=8&amp;streetaddress=6201+Winnetka+ave&amp;city=Woodland+Hills&amp;state=CA&amp;zip=91371&amp;POI1streetaddress=6201+Winnetka+ave&amp;POI1city=Woodland+Hills&amp;POI1state=CA&amp;POI1zip=91371&amp;POI1iconID=11">Map To Pierce</a><br>
  <a href="http://www.piercecollege.edu/map.aspx">Campus Map</a></p>
  <p>
  <img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
  </p>
</div>
EOF;

list($top, $footer) = $S->getPageTopBottom($h);

$greet = $S->greetvisitors();

//
// <blp date="1/3/2007">
// For testing. If validator.w3.org (128.30.52.13) (133.27.228.132)or
// www.websiteoptimization.com (66.135.38.137) then set the user
// information to ME

if(ereg("128\.30\.52\.", $_SERVER['REMOTE_ADDR']) ||
   ($_SERVER['REMOTE_ADDR'] == "133.27.228.132") ||
   ($_SERVER['REMOTE_ADDR'] == "66.135.38.137")) {
  
  $S->name = "barton";	// set name to me
  $S->id = 192;	// set id to me
}

if(!$S->id) {
  //echo "<h2>Goto <a href='http://zupons.net/swam/index-nonmembers.php'>non member page</a></h2>";
 
  header("Location: http://zupons.net/swam/index-nonmembers.php");
  exit();
}   

ob_start();
include("getfirefox.html");
$getfirefox = ob_get_clean();

echo <<<EOF
$top
$greet
<div id="trainWorkoutMsg" class="buttonMarg">
<a class="buttons1-5em blueButton"
href='workoutinfo.php'>Training Schedule&amp;Workout Information</a>
</div>
<!--<div class='center buttonMarg'>
<a class='buttons1-5em blueButton' href='swammail.php?page=sendmail'>
Send SwamMail</a>
</div>-->
<!--<div class='center buttonMarg'>
<a class='buttons1-5em pinkButton' href='bboard.php'>
Bulletin Board</a>
</div>-->
<div class='center buttonMarg'>
<a class='buttons1-5em redButton' href='http://www.facebook.com/pages/Southwest-Aquatic-Masters-SWAM/238630129518948?ref=ts'>SWAM on Facebook</a>
</div>
<div class="center">
  <img class="laneline" src="images/laneline.gif" alt="****lane*lines****">
</div>
<table id="newsEvents">
<tr>
    <th>Pool News</th>
    <th>Upcoming Events</th>
</tr>

<tr>
<td class="liteYellow">
  <ul>
   <li><a href="Etiquette.html">Pool Etiquette</a> <i>from Fred</i></li>
    <li><a href="howtowritehtml.php">How to Use HTML in Bulletin Board Messages</a></li>
    <li><a href="http://www.bartonphillips.com/howtowritehtml.php">How to write HTML from Barton Phillips' website</a></li>
  </ul>
</td>
<td class="liteYellow">
  <ul>
    <li><a href="http://www.mortonsaltreport.homestead.com/raceschedule.html">Morton Salt Ocean
    Swims Schedule for 2013</a></li>
    <li><a href="http://www.spma.net/open_water.php">SPMA Swin Schedule for 2013</a></li>
    <!-- LINK TO USMS.ORG CALENDAR -->
    <li><a href="http://www.usms.org/comp/calendar.htm#SOUTHWEST">Calendar of Events</a></li>
  </ul>
</td>
</tr>
</table>
<table id="masterInfo">
<tr><th class="Yellow">Masters Info.</th>
</tr>
<tr>
<td class="liteYellow">
<ul>
<li><a href="http://www.usms.org/rules/">Master Meet Rules</a> </li>
<li><a href="league_handicaps.html">League Handicaps, or &quot;How to swim faster as you get older!&quot;</a>
<i style="font-size: 60%">New, with all events, including 50's</i></li>
<li><a href="otherlinks.php">Other Swimming Links</a></li>
$getfirefox
</ul>
</td>
</tr>
</table>
<table id="specialServices">
<tr>
<th colspan=2><a name="special">Special Services</a></th>
</tr>
<tr>
<td><div class="center buttons1-5em yellowButton"><a href="mo-bdays.php">
Who&nbsp;Has&nbsp;A&nbsp;Birthday</a></div>
</td>
<td>See who has a birthday this month
</td>
</tr>
</table>
<table id="weather">
<tr>
<th colspan="2">Weather in Our area.</th>
</tr>
<tr>
<td>
Here is the weather as reported by<br>
<a class="buttons1-5em blueButton" href="weather.php">www.weather.com</a>
<br><br>
Here is the Weather in Woodland Hills on the<br>
<a class="buttons1-5em yellowButton" href="http://www.wunderground.com/US/CA/Woodland_Hills.html">Weather Underground</a>
<br><br>
</td>
<td>
Here is the Weather in Newbury Park<br>
<a class="buttons1-5em greenButton" href="http://www.wunderground.com/US/CA/Newbury_Park.html">Newbury Park Calif</a>
<br><br>
Here is the Weather at Barton's Home<br>
<a class="buttons1-5em redButton" href="http://www.wunderground.com/US/CO/Granby.html">Granby
   Colorado</a>
<br><br>
</td>
</tr>
</table>
<br>
<div class="buttonMarg">
<a class="buttons1-5em greenButton" href="guestreg.php">
Update Your Profile</a><br><br>
<a class="buttons1-5em blueButton" href="guestreglook.php">
Who Signed Already</a>
</div>
<a href="guestcounts.php">Who is active -- guest counts</a>
<table id="todayGuests">
<tbody id="whobody">
<tr>
<th>Who's been here today?</th>
<th>Last Time</th>
</tr>
EOF;

$result = $S->query("select concat(fname, ' ', lname) as name, date_format(lasttime, '%H:%i:%s') as date from swammembers ".
                    "where name is not null and visittime > current_date() and visits > 0 order by visittime desc");

while($row = mysql_fetch_assoc($result)) {
  extract($row);
  echo "<tr><td>$name</td><td class='center'>$date</td></tr>\n";
}

echo <<<EOF
</tbody>
</table>
<ul>
<!--<li><a href="steve.html">Tributes to Steve Schofield</a></li>-->
<li>
<a href="http://www.piercecollege.edu/departments/athletics/swimshd.html">
Pierce College Swimming and Diving Team Shedule</a></li>
</ul>
<hr>
<p>
<a href="http://validator.w3.org/check?uri=referer"><img border="0" src="images/valid-html401.png"
alt="Valid HTML 4.01!" height="31" width="88"></a>
<a href="http://jigsaw.w3.org/css-validator/">
<img style="border:0;width:88px;height:31px" src="images/vcss.png" alt="Valid CSS!">
</a>
</p>
$footer
EOF;
?>

