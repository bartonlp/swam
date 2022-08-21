<?php
// This is the Soutwest Masters Website (swam.us)

$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

$h->link =<<<EOF
  <link rel="stylesheet" href="css/swam.css">
EOF;

$h->css =<<<EOF
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
#fred, #pool { width: 100%; }
#fred td, #pool td { padding-right: 10px; }
EOF;

$h->title = "South West Aquatic Masters Home Page -- Coached Masters ".
            "Swimming Workouts and Competition Swimming";

$b->msg = "PhpVersion: " . PHP_VERSION;
$b->msg1 = "<br>SiteClass Version: " . SITE_CLASS_VERSION;

$h->banner = <<<EOF
<div>
  <h2><a href="http://www.pierceaquatics.com/">Los Angeles Pierce College</a></h2>

  <p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
  (LAPC) Extension Office Phone Number (818)719-6425 <br>
  <a href="http://www.mapquest.com/cgi-bin/ia_free?width=400&amp;height=400&amp;icontitles=yes&amp;level=8&amp;streetaddress=6201+Winnetka+ave&amp;city=Woodland+Hills&amp;state=CA&amp;zip=91371&amp;POI1streetaddress=6201+Winnetka+ave&amp;POI1city=Woodland+Hills&amp;POI1state=CA&amp;POI1zip=91371&amp;POI1iconID=11">Map To Pierce</a>
  <p>
  <img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
  </p>
</div>
EOF;

[$top, $footer] = $S->getPageTopBottom($h, $b);

echo <<<EOF
$top
$greet
<h3>Coach:</h3>

<!-- PICTURE OF FRED AND TEXT -->
<table id='fred'>
  <tr>
    <td><img SRC="images/fred.jpg" alt="Fred Shaw"></td>
    <td><b>Fred Shaw</b> Three time member NCAA Division I Championship Team
    (USC); over 20 years experience in Masters Swimming; 1994 Community College Coach of the Year;
    <a
    href="mailto:swamfred@aol.com">E-mail Fred.</a></td>
  </tr>
</table>

<!-- PICTURES OF POOL AND TEXT -->
<table id='pool'>
  <tr>
    <td><img SRC="images/poolj_022.JPG" alt='Pierce Pool'></td>

    <td><h3>Highlights:</h3>
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
<li><a href="handicaps.php">League Handicaps, or &quot;How to swim faster as you get older!&quot;</a>
<i style="font-size: 60%">New, with all events</i></li>
<li><a href="otherlinks.php">Other Swimming Links</a></li>
</ul>
<hr>
$footer
EOF;

