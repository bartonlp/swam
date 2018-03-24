<?php
if ($_GET['havecookies'] != 'yes') {
  // Set cookie
  setcookie ('cookietest', 'set', time() + 60);

  // Reload page
  header ("Location: index-nonmembers.php?havecookies=yes");
} else {
  // Check if cookie exists
  if(empty($_COOKIE['cookietest'])) {
    $nocookiemsg = <<<EOF
<div style="background-color: red;color: white;text-align: center;padding: 10px;">Cookies are NOT enabled on your browser. Without COOKIES this site will not work
correctly. <!--You will not be able to <b>Register</b> and therefore will not be able to become a member.
You should enable cookies for this site if you want to become a member.--></div>
EOF;
  }
}

require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

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

$h->title = "South West Aquatic Masters (NON MEMBERS) Home Page -- Coached Masters ".
            "Swimming Workouts and Competition Swimming";

$h->banner = <<<EOF
<div class="center">
  <h2><a href="http://www.piercecollege.edu/">Los Angeles Pierce College</a></h2>

  <p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
  <a href="http://www.mapquest.com/cgi-bin/ia_free?width=400&amp;height=400&amp;icontitles=yes&amp;level=8&amp;streetaddress=6201+Winnetka+ave&amp;city=Woodland+Hills&amp;state=CA&amp;zip=91371&amp;POI1streetaddress=6201+Winnetka+ave&amp;POI1city=Woodland+Hills&amp;POI1state=CA&amp;POI1zip=91371&amp;POI1iconID=11">Map To Pierce</a><br>
        <a href="http://www.piercecollege.edu/map.aspx">Campus Map</a></p>
  <p>
    <img src="images/laneline.gif" height="30" width="600" align="bottom" alt="****lane*lines****">
  </p>
</div>
EOF;

list($top, $footer) = $S->getPageTopBottom($h);

if($nocookiemsg) {
  echo <<<EOF
$top
$nocookiemsg
$footer
EOF;
  exit();
}

$greet = $S->greetvisitors();

echo <<<EOF
$top
$greet
<p><b>South West Aquatic Masters</b> swimming is a chartered swim team affiliated with the United
States Masters Swimming through its local association, Southern Pacific Masters. Swim with
<b>SWAM (<i>South West Aquatic Masters)</i></b>
swim team at Pierce College, Woodland Hills, California; if you are
serious about improving your swimming skill level (19 years old and older.)
From lap swimmers to national champions
these coached workouts are designed to increase your swimming efficiency and improve your
endurance and strength. SWAM is located at Pierce College, Woodland Hills, California; and swimmers interested in
swimming for the college team can call Fred Shaw at (818) 347-1637 or
<a href="mailto:swamfred@aol.com">E-mail Fred.</a>
</p>

<div class="center">
<a  class="buttons1-5em blueButton" href='workoutinfo.php'>Training
Schedule&amp;Workout Information</a>

<!--<h3>There is a lot more to this site but you need to be a registered member first</h3>-->
<!--<a class="buttons1-5em blueButton" href="guestreg.php">Register</a>-->
</div>
<div class='center buttonMarg'>
<a class='buttons1-5em redButton' href='http://www.facebook.com/pages/Southwest-Aquatic-Masters-SWAM/238630129518948?ref=ts'>SWAM on Facebook</a>
</div>
<p class="center">
<img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
</p>

<p>
<b style="font-size: 1.2em;">Fees:</b><br>
\$59.00 per month, \$162.00 per quarter. Workouts are 60 minutes. To register contact Los Angeles Pierce College
(LAPC) Extension Office at (818)719-6425
</p>

<h3>Coaches:</h3>

<!-- PICTURE OF FRED AND TEXT -->
<table CELLSPACING="2">
  <tr>
    <td><img SRC="images/fred.jpg" align="LEFT" WIDTH="163" HEIGHT="176" alt=""></td>
    <td><b>Fred Shaw</b> Three time member NCAA Division I Championship Team
    (USC); 20 years experience in Masters Swimming; 1994 Community College Coach of the Year;
    Current Pierce College Men's and Women's Head Swim Coach. <a
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

<!-- GUEST REGISTRATION -->
<!--<h3>There is a lot more to this site but you need to be a registered member
first</h3>-->

<!--<div>
<a class="buttons1-5em redButton" href="needtoreg.php">Sign Our Guestbook (REGISTER)</a>
</div>-->

<ul>
<li>
<a href="http://www.piercecollege.edu/departments/athletics/swimshd.html">
Pierce College Swimming and Diving Team Shedule</a>
<em style="font-size: .7em;">Link to College Site</em></li>
<li><a href="http://www.dalandswim.com/">Daland Swim School et all</a> An interesting site</li>
<li><a href="http://www.usms.org/rules/">Master Meet Rules</a> </li>
<li><a href="handicaps_sc.html">League Handicaps, or &quot;How to swim faster as you get older!&quot;</a>
<i style="font-size: 60%">New, with all events</i></li>
<li><a href="otherlinks.php">Other Swimming Links</a></li>
</ul>
<p>
<a href="http://validator.w3.org/check?uri=referer"><img border="0"
src="http://www.w3.org/Icons/valid-html40"
alt="Valid HTML 4.0!" height="31" width="88"></a>
</p>

<p>
 <a href="http://jigsaw.w3.org/css-validator/">
  <img style="border:0;width:88px;height:31px"
       src="http://jigsaw.w3.org/css-validator/images/vcss"
       alt="Valid CSS!">
 </a>
</p>
$footer
EOF;
?>



                                                        
