<?php
// This is the Soutwest Masters Website (swam.us)

$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

$S->link =<<<EOF
  <link rel="stylesheet" href="css/swam.css">
EOF;

$S->css =<<<EOF
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

$S->title = "South West Aquatic Masters Home Page -- Coached Masters ".
            "Swimming Workouts and Competition Swimming";

$S->msg = "PhpVersion: " . PHP_VERSION;
$S->msg1 = "<br>SiteClass Version: " . SITE_CLASS_VERSION;

$S->banner = <<<EOF
<div>
  <h2><a href="http://www.pierceaquatics.com/">Los Angeles Pierce College</a></h2>

  <p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
  (LAPC) Extension Office Phone Number (818)719-6425 <br>
  <!--<a href="https://www.mapquest.com/latlng/34.182971,-118.570951?centerOnResults=1">Map To Pierce</a>-->
  <a href="https://www.google.com/maps/@34.1854824,-118.5750625,2068m/data=!3m1!1e3!5m2!1e4!1e3?hl=en">Map To Pierce</a>
  <p>
  <img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
  </p>
</div>
EOF;

[$top, $footer] = $S->getPageTopBottom();

echo <<<EOF
$top
$greet
<!-- PICTURE OF FRED AND TEXT -->
<table id='fred'>
  <tr>
    <td><img SRC="images/fred.jpg" alt="Fred Shaw"></td>
    <td><b>Coach Fred Shaw:</b><br>Three time member NCAA Division I Championship Team
    (USC); over 20 years experience in Masters Swimming; 1994 Community College Coach of the Year;
    <a
    href="mailto:swamfred@aol.com">E-mail Fred.</a></td>
  </tr>
</table>

<!-- PICTURES OF POOL AND TEXT -->
<table id='pool'>
  <tr>
    <td><img SRC="images/poolj_022.JPG" alt='Pierce Pool'></td>

    <td><p><b>Masters Highlights:</b><br>
    South West Masters compete in United States Masters Swimming meets,
    Masters Dual meets,
    Triathlons, Ocean Swims and other fun stuff.</p>
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
<li><a href="http://www.usms.org/rules/">Master Meet Rules</a> </li>
<li><a href="otherlinks.php">Other Swimming Links</a></li>
</ul>
<hr>
$footer
EOF;

