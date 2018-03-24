<?php
if(!$db) {
	require("secureinfo/id.phpi");
	require("trackvisitor.phpi");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<base href='http://zupons.net.mylampsite.com/swam/'/>
<meta name="Author" content="Barton L. Phillips, Applitec Inc., mailto:barton@applitec.com">
<meta name="Description" content="South West Aquatic Master Swimming at Pierce College, Woodland Hills, California. A fun coached Masters swimming workout plus Masters swimming competition.">
<meta name="KeyWords" content="Swimming, Masters swimming, Swim Meets, Sports, Athletics, Swimming Coaching, Swimming Competition, Califronia Swimming, Southern California Swimming,">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link rev=made href="mailto:john@zupons.net">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/swam.css" type="text/css">
<title>South West Aquatic Masters (non members) Home Page -- Coached Masters
Swimming Workouts and Competition Swimming</title>

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
</head>

<!-- NON MEMBERS PAGE -->

<!--SWAM HEADER WITH LOGO IMAGE AND TITLE! BODY TAG IS IN HEADER INCLUDE-->

<body>

<?php
include("header.phpi");
?>

<div class="center">
  <h2><a href="http://www.lapc.cc.ca.us">Los Angeles Pierce College</a></h2>

  <p>6201 Winnetka Avenue, Woodland Hills, CA 91371 <br>
  <a href="http://www.mapquest.com/cgi-bin/ia_free?width=400&amp;height=400&amp;icontitles=yes&amp;level=8&amp;streetaddress=6201+Winnetka+ave&amp;city=Woodland+Hills&amp;state=CA&amp;zip=91371&amp;POI1streetaddress=6201+Winnetka+ave&amp;POI1city=Woodland+Hills&amp;POI1state=CA&amp;POI1zip=91371&amp;POI1iconID=11">Map To Pierce</a><br>
        <a href="http://www.piercecollege.edu/map.aspx">Campus Map</a></p>
  <p>
    <img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
  </p>
</div>

<!-- GREET THOSE WHO GAVE US THEIR NAMES!-->

<?php
  include("greetvisitors.phpi");
?>

<noscript>
<div id="noJava" align="left">WARNING: Your Browser Does Not Support JavaScript, or you have
JavaScript disabled. In either event this site uses JavaScript and without
these your browsing experience will not be very good!<br><br>
You will also need <b>COOKIES</b> in order to
become a member and register.</div>
</noscript>

<script type="text/javascript" language="JavaScript">
<!--
  if(!navigator.cookieEnabled) {
    document.writeln('<div id="noCookie">You do not have <b>COOKIES<\/b> enabled or you browser does not support cookies ' +                     'in either case you will need cookies in order to register and become a member. ' +
                     'Check out <b>"Our Use of Cookies"<\/b> above for more information.<\/div>');
  }
// -->
</script>

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

<!-- Button for "Training Schedule &amp; Workout Information" -->

<div class="center">
<a  class="buttons1-5em blueButton" href='workoutinfo.php'>Training
Schedule&amp;Workout Information</a>

<h3>There is a lot more to this site but you need to be a registered member first</h3>
<a class="buttons1-5em blueButton" href="guestreg.php">Register</a>
</div>

<p class="center">
<img SRC="images/laneline.gif" height="30" width="600" align="BOTTOM" alt="****lane*lines****">
</p>

<p>
<b style="font-size: 1.2em;">Fees:</b><br>
$59.00 per month, $162.00 per quarter. Workouts are 60 minutes. To register contact Los Angeles Pierce College
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
<h3>There is a lot more to this site but you need to be a registered member
first</h3>

<div>
<a class="buttons1-5em redButton" href="needtoreg1.php">Sign Our Guestbook (REGISTER)</a>
</div>

<ul>
<li><a href="dedication07/index.html">Steve Schofield Aquatic Center Pool Dedication</a></li>
<li><a href="fopp/">Friends of Pierce Pool</a></li>

<li>
<a href="http://www.piercecollege.edu/departments/athletics/swimshd.html">
Pierce College Swimming and Diving Team Shedule</a>
<em style="font-size: .7em;">Link to College Site</em></li>
<li><a href="http://www.dalandswim.com/">Daland Swim School et all</a> An interesting site</li>
<li><a href="http://www.usms.org/rules/">Master Meet Rules</a> </li>
<li><a href="handicaps_sc.html">League Handicaps, or &quot;How to swim faster as you get older!&quot;</a>
<i style="font-size: 60%">New, with all events</i></li>
<li><a href="otherlinks.html">Other Swimming Links</a></li>
<li><a href="http://www.surfrider.org/Cal5.htm">Surfrider: California Costal Water Quality</a></li>

<?php include "getfirefox.html"; ?>

<li><a href="browsers.html">Browser Compatability and our site</a></li>
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


<!-- FOOTER DOES ALL OF THE END GAME STUFF -->
<?php include("fullfooter.php"); ?>



                                                        
