<?php
// This is special for zupons.net
// BLP 2021-09-05 --  Note the putenv() and
// ini_set() at the start. This is needed because I do not have root access to this server and
// several things just arn't right, not the least that this site uses PHP5 not 7.

putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");
$_site = require_once(getenv("SITELOAD")."/siteload.php");
ini_set("error_log", "/tmp/PHP_ERROR.log");
ErrorClass::setDevelopment(true);
$S = new $_site->className($_site);
$h->extra = <<<EOF
  <script>
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
ul {
  list-style-type: none;
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
<div class="center">
  <h2><a href="http://www.pierceaquatics.com/">Los Angeles Pierce College</a></h2>

  <p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
  (LAPC) Extension Office Phone Number (818)719-6425 <br>
  <a href="http://www.mapquest.com/cgi-bin/ia_free?width=400&amp;height=400&amp;icontitles=yes&amp;level=8&amp;streetaddress=6201+Winnetka+ave&amp;city=Woodland+Hills&amp;state=CA&amp;zip=91371&amp;POI1streetaddress=6201+Winnetka+ave&amp;POI1city=Woodland+Hills&amp;POI1state=CA&amp;POI1zip=91371&amp;POI1iconID=11">Map To Pierce</a><br>
  <a href="http://www.piercecollege.edu/map.aspx">Campus Map</a></p>
  <p>
  <img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
  </p>
</div>
EOF;

$b->ctrmsg = "Number of Hits Since May 24, 2014";

list($top, $footer) = $S->getPageTopBottom($h, $b);

echo <<<EOF
$top
$greet

<h3>Coach:</h3>

<!-- PICTURE OF FRED AND TEXT -->
<table CELLSPACING="2">
  <tr>
    <td><img SRC="images/fred.jpg" align="LEFT" WIDTH="163" HEIGHT="176" alt=""></td>
    <td><b>Fred Shaw</b> Three time member NCAA Division I Championship Team
    (USC); over 20 years experience in Masters Swimming; 1994 Community College Coach of the Year;
    <a
    href="mailto:swamfred@aol.com">E-mail Fred.</a></td>
  </tr>
</table>

<!-- PICTURES OF POOL AND TEXT -->
<table CELLSPACING='2' width='100%'>
  <tr>
    <td><img SRC="images/poolj_022.JPG" align='LEFT' alt=''></td>

    <td width='65%'><h3>Highlights:</h3>
    <ul>
      <li>South West Masters compete in U.S.M.S. (United States Masters Swimming) meets</li>
      <li>Masters Dual meets between other local masters teams</li>
      <li>Triathlon</li>
      <li>Ocean Swims</li>
      <li>and other fun stuff.</li>
    </ul>
    </td>
  </tr>
</table>

<center>
<img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
</center>
<div id="trainWorkoutMsg" class="buttonMarg">
<a class="buttons1-5em blueButton"
href="http://www.pierceaquatics.com/programs/swam.html">SWAM Pierce Aquatics Information</a>
</div>
<div class='center buttonMarg'>
<a class='buttons1-5em redButton' href='http://www.facebook.com/pages/Southwest-Aquatic-Masters-SWAM/238630129518948?ref=ts'>SWAM on Facebook</a>
</div>
<div class="center">
  <img class="laneline" src="images/laneline.gif" alt="****lane*lines****">
</div>
<!--<ul>
<li><a href="http://www.dalandswim.com/">Daland Swim School et all</a> An interesting site</li>
<li><a href="http://www.usms.org/rules/">Master Meet Rules</a> </li>
<li><a href="handicaps_sc.html">League Handicaps, or &quot;How to swim faster as you get older!&quot;</a>
<i style="font-size: 60%">New, with all events</i></li>
<li><a href="otherlinks.php">Other Swimming Links</a></li>
</ul>-->

EOF;

echo <<<EOF
<table id="weather">
<tr>
<th>Weather in Our area.</th>
</tr>
<tr>
<th style='background: white'>
Here is the Weather in Woodland Hills on the<br>
<a class="buttons1-5em yellowButton" href="https://www.wunderground.com/weather/us/ca/woodland-hills">Weather Underground</a>
<br><br>
</th>
</tr>
</table>
</tbody>
</table>

<ul>
<li><a href="http://www.dalandswim.com/">Daland Swim School et all</a> An interesting site</li>
<li><a href="http://www.usms.org/rules/">Master Meet Rules</a> </li>
<li><a href="handicaps_sc.html">League Handicaps, or &quot;How to swim faster as you get older!&quot;</a>
<i style="font-size: 60%">New, with all events</i></li>
<li><a href="otherlinks.php">Other Swimming Links</a></li>
</ul>

$footer
EOF;

