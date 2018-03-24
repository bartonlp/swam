<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->extra = <<<EOF
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

$h->title = "South West Aquatic Masters Workout and training Schedule ";

$h->banner = <<<EOF
<div class="center">
<h2><a href="http://www.lapc.cc.ca.us">Los Angeles Pierce College</a></h2>

<p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
<a href="http://www.mapquest.com/cgi-bin/ia_free?width=400&amp;height=400&amp;icontitles=yes&amp;level=8&amp;streetaddress=6201+Winnetka+ave&amp;city=Woodland+Hills&amp;state=CA&amp;zip=91371&amp;POI1streetaddress=6201+Winnetka+ave&amp;POI1city=Woodland+Hills&amp;POI1state=CA&amp;POI1zip=91371&amp;POI1iconID=11">Map To Pierce</a><br>
   <a href="http://www.piercecollege.edu/map.aspx">Campus Map</a></p>
</div>
EOF;
$top =$S->getPageTop($h);
$footer = $S->getFooter();

echo <<<EOF
$top
<table align=center bordercolor=blue border=3 cellspacing=2 cellpadding=5>
<tr> 
  <th colspan=6><a name="workout">Workouts and who is coaching them</a></th>
</tr>
<tr>
  <th>Time</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th>
</tr>
<tr>
  <th>6AM</th><td class=center>Franz</td><td class=center>&nbsp;</td><td class=center>Franz</td><td class=center>&nbsp;</td><td class=center>Jim</td>
</tr>
<tr>
  <th>Noon</th><td class=center>Fred</td><td class=center>Fred</td><td class=center>Fred</td><td class=center>Fred</td><td class=center>Fred</td>
</tr>
<tr>
 <th>8:30am</th><td>Saturday Workout</td>
</tr>
</table>
$footer
EOF;


