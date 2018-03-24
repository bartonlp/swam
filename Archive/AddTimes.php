<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;
$h->title = "Dual and Master's meet Archives Add info";
$h->banner = <<<EOF
<h1 align="center"><font color="#0000FF">South West Aquatic Masters</font></h1>
<p align="center"><img SRC="/images/swamlogo.gif" align="BOTTOM" WIDTH="253" HEIGHT="98" alt=""> </p>
EOF;

if($_POST['page'] == "post") {
  // second half
  // Time comes in as hours, minutes, seconds, hundreds. 
  // Make hundreds of seconds from the lot.

  $hsec = ($hours * 360000) + ($minutes * 6000) + ($seconds * 100) + $hundreds;

  $meetdate = "$year-$month-$day";

  $results = $S->query("replace into meethistory (name, meetdate, course, sex, agerange, team, event, time, adjtime, useradd) " .
                         "values('$name', '$meetdate', '$course', '$sex', '$agerange', '$team', '$event', '$hsec', '$hsec', 'y')");

  echo "<p>The following information was added to the database:</p>";
  echo "<table border=1>";
  echo "<tr><th>Name</th><th>Date</th><th>Course</th><th>Sex</th><th>Age Group</th><th>Team</th><th>Time</th></tr>";

  $time = sprintf("%02d:%02d:%02d.%02d", $hours, $minutes, $seconds, $hundreds);

  echo "<tr><td>$name</td><td>$meetdate</td><td align=center>$course</td><td align=center>$sex</td><td align=center>$agerange</td><td>$event</td><td>$time</td></tr>";
  echo "</table>";
  echo "<br><a href=\"index.php\">Return to Home Page</a>";

  echo "</body></html>";
    
  // Done

  exit();
}

// First half

$h->banner = "<h1>Add Your Personal Information</h1>";
$top = $S->getPageTop($h);
$footer = $S->getFooter();

echo <<<EOF
$top
<p>You can add information to the database (or edit information). The new data will replace any old data
  if it exists. Note: the primary keys for this table are &quot;NAME&quot;, &quot;MEETDATE&quot; and
  &quot;EVENT&quot;. If a record exists with the name, date, and event you enter here it will be replaced.</p>
<p>I have tried to get most of the dual 
  meet data entered, but it is a big job (and I am a little lazy!). But you can 
  add the events that matter to you. Note: I have not added a way to enter &quot;ADJTIME&quot; for dual meets.
  I have already entered most dual meets. If you have information on a meet that I do not have please
  e-mail me the information and I will enter it along with the adjusted time. Also, if you have events that
  don't match any of the events I have provided drop me an e-mail.
  You are on your honer here -- don't make yourself swim too fast!</p>
<hr>
<form action="$S->self" method="post">
<table border=0>
<tr>
<th align=left>Name:</th>
<td><input type="text" name="name"></td>
</tr>
<tr>
<th align=left>Age Group:</th>
<td><select name="agerange">	
<option>19-29
<option>30-34
<option>35-39
<option>40-44
<option>45-49
<option>50-54
<option>55-59
<option>60-64
<option value="65-">65 pluss
</select></td>
</tr>
<tr>
<th align=left>Sex:</th>
<td><select name="sex">
<option value="m">Men
<option value="w">Women
</select></td>
</tr>              
<tr>
<th align=left>Team:</th>
<td><select name="team">
<option value="">No Team
<option value="s">Southwest Aquatics Masters
<option value="d">Daland Swim School
<option value="v">Ventura
<option value="r">Rose Bowl
</select></td>
</tr>
<tr>
<th align=left>Event:</th>
<td><select name="event">
<option>50fr
<option>100fr
<option>200fr
<option>500fr
<option>1000fr
<option>1650fr
<option>50bk
<option>100bk
<option>200bk
<option value="50fl">50fly
<option value="100fl">100fly
<option value="200fl">200fly
<option value="500fl">500fly
<option>50br
<option>100br
<option>200br
<option>500br
<option>100im
<option>200im
<option>400im
<option>800im
<option value="1mi">1 mile Ocean
<option value="2mi">2 mile Ocean
<option value="3mi">3 mile Ocean
<option value="4mi">4 mile Ocean
<option value="5mi">5 mile Ocean
<option value="6mi">6 mile Ocean
<option value="7mi">7 mile Ocean
<option value="8mi">8 mile Ocean
<option value="9mi">8 mile Ocean
<option value="10mi">10 mile Ocean
</select></td>
</tr>
<tr>
<th align=left>Date:</th>
<td>Month <select name="month">
<option>01
<option>02
<option>03
<option>04
<option>05
<option>06
<option>07
<option>08
<option>09
<option>10
<option>11
<option>12
</select>
Day
<select name="day">
<option>01
<option>02
<option>03
<option>04
<option>05
<option>06
<option>07
<option>08
<option>09
<option>10
<option>11
<option>12
<option>13
<option>14
<option>15
<option>16
<option>17
<option>18
<option>19
<option>20
<option>21
<option>22
<option>23
<option>24
<option>25
<option>26
<option>27
<option>28
<option>29
<option>30
<option>31
</select>
Year
<select name="year">
<option>1990
<option>1991
<option>1992
<option>1993
<option>1994
<option>1995
<option>1996
<option>1997
<option>1998
<option>1999
<option>2000
<option>2001
<option>2002
</select></td>
</tr>
<tr>
<th align=left>Course:</th>
<td><select name="course">
<option value="25y-d">25 yard Dual Meet
<option value="25y-m">25 yard Masters
<option value="25m-m">25 meter Masters
<option value="50m-m">50 meter Masters
<option value="other">Other (ocean etc.)
</select></td>
</tr>
<tr>
<th align=left>Time:</th>
<td>Hours
<select name="hours">
<option>0
<option>1
<option>2
<option>3
<option>4
<option>5
<option>6
<option>7
<option>8
<option>9
</select>
Minutes
<select name="minutes">
EOF;

for($i=0; $i < 61; ++$i) {
  echo "<option>$i\n";
}

echo <<<EOF
</select>
Seconds
<select name="seconds">
EOF;


for($i=0; $i < 61; ++$i) {
  echo "<option>$i\n";
}

echo <<<EOF
</select>
Hundreds: 
<select name="hundreds">
EOF;

for($i=0; $i < 100; ++$i) {
  echo "<option>$i\n";
}

echo <<<EOF
</select></td>
</tr>
<tr>
<input type=hidden name="page" value="post">
<td align=left><input type=submit></td>
</tr>
</table>
</form>
$footer
EOF;
?>

