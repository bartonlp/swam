<?php require("secureinfo/id.phpi"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>

<head>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Author" content="Barton L. Phillips, Applitec Inc., mailto:barton@applitec.com">
<meta name="Description" content="South West Aquatic Master Swimming at Pierce College. A fun coached Masters swimming workout plus Masters swimming competition.">
<meta name="KeyWords" content="Swimming, Masters swimming, Swim Meets, Sports, Athletics, Swimming Coaching, Swimming Competiton">
<title>Dual and Master's meet Archives</title>
</head>

<!-- Standard Header -->
<?php include("header.phpi"); ?>

<!-- Check to see if first or second half of script -->

<?php
  if($fields) {
    // For Geeks!!!!

    // Look at the query and make sure it is a simple select

		$fields = trim($fields);
		$fields = strtolower($fields);
    $where = trim($where);
    $where = strtolower($where);

		$query = "select " . $fields . " from meethistory where " . $where;
    $query = ereg_replace("[\]", "", $query);
    
    echo ("<hr><h1 align=center>Your Search Was:</h1><p align=center><i>$query</i></p>\n<hr><br>\n");
    echo ("<p><a href=\"index-members.php\">Return to home page</a></p>");
    
    if(strstr($query, ";")) {
      echo "<h1>Please NO &quot;;&quot; in query</h1>";
      exit();
    }

		if(strpos($query, "/*")) {
			echo "<h1>Please NO Comments in query</h1>";
			exit();
		}
		
    $result = mysql_query($query, $db);

    printf("<table border=1>\n<tr><th>Name</th><th>Date (YYYY-MM-DD)</th><th>Course</th><th>Team</th><th>Event</th><th>Age Group</th><th>Time</th><th>Adj Time</th></tr>\n");
    
    if($result > 0) {
      while($myrow = mysql_fetch_array($result)) {
        printf("<tr>\n");

        printf("<td>%s</td>", $myrow['name']);
        printf("<td align=center>%s</td>", $myrow['meetdate']);
        printf("<td align=center>%s</td>", $myrow['course']);
        printf("<td align=center>%s</td>", $myrow['team']);
        printf("<td align=center>%s</td>", $myrow['event']);
        printf("<td align=center>%s</td>", $myrow['agerange']);
        $time = $myrow['time'];

        printf("<td>%02d:%02d.%02d</td>", $time / 6000, ($time % 6000) / 100, $time % 100);

        $time = $myrow['adjtime'];

        printf("<td>%02d:%02d.%02d</td>", $time / 6000, ($time % 6000) / 100, $time % 100);

        printf("</tr>\n");
      }
    }
    printf("</table>\n");
    printf("<p><a href=\"index-members.php\">Return to home page</a></p>\n</body></html>\n");
    exit();
  }

  if($dummy) {
    $select = "select * from meethistory ";
    $and = "";

    if($name || $event || $team || $course || $agerange || $month || $day || $year || $sex) {
      $select .= "where ";
    }

    if($name) {
      // Get records for swimmer
      // We make name into first initial and last name. Or just last name.

      $name = trim($name);

      $parts = split(" ", $name);

      if(sizeof($parts) > 1) {
        $name = substr($parts[0], 0, 1) . "%" . $parts[sizeof($parts) -1];
      } else {
        $name = "%" . $name;
      }

      $select .= "name like '$name'";
      $and = "and";
    }

    if($event) {
      $select .= " $and event='$event'";
      $and = "and";
    }

    if($sex) {
      $select .= " $and sex='$sex'";
      $and = "and";
    }

    if($team) {
      $select .= " $and team='$team'";
      $and = "and";
    }

    if($course) {
      $select .= " $and course='$course'";
      $and = "and";
    }
    
    if($agerange) {
      $select .= " $and agerange $agematch '$agerange'";
      $and = "and";
    }

    if($month && $day && $year) {
      $select .= " $and meetdate $datematch '$year-$month-$day'";
    } elseif($year && $month) {
      $select .= " $and year(meetdate) $datematch '$year' and month(meetdate) $datematch '$month'";
    } elseif($year && $day) {
      $select .= " $and year(meetdate) $datematch '$year' and dayofmonth(meetdate) $datematch '$day'";
    } elseif($month && $day) {
      $select .= " $and month(meetdate) $datematch '$month' and dayofmonth(meetdate) $datematch '$day'";
    } elseif($day) {
      $select .= " $and dayofmonth(meetdate) $datematch '$day'";
    } elseif($month) {
      $select .= " $and month(meetdate) $datematch '$month'";
    } elseif($year) {
      $select .= " $and year(meetdate) $datematch '$year'";
    }

    $select .= " order by adjtime";

    $result = mysql_query($select, $db);

    echo ("<hr><h1 align=center>Your Search Was:</h1><p align=center><i>$select</i></p>\n<hr><br>\n");
    echo ("<p><a href=\"index-members.php\">Return to home page</a></p>");
    
    printf("<table border=1>\n<tr><th>Name</th><th>Date (YYYY-MM-DD)</th><th>Course</th><th>Team</th><th>Event</th><th>Age Group</th><th>Time</th><th>Adj Time</th></tr>\n");
    
    if($result != 0) {
      while($myrow = mysql_fetch_array($result)) {
        printf("<tr>\n");

        printf("<td>%s</td>", $myrow['name']);
        printf("<td align=center>%s</td>", $myrow['meetdate']);
        printf("<td align=center>%s</td>", $myrow['course']);
        printf("<td align=center>%s</td>", $myrow['team']);
        printf("<td align=center>%s</td>", $myrow['event']);
        printf("<td align=center>%s</td>", $myrow['agerange']);
        $time = $myrow['time'];

        printf("<td>%02d:%02d.%02d</td>", $time / 6000, ($time % 6000) / 100, $time % 100);

        $time = $myrow['adjtime'];

        printf("<td>%02d:%02d.%02d</td>", $time / 6000, ($time % 6000) / 100, $time % 100);

        printf("</tr>\n");
      }
    }
    printf("</table>\n");
    printf("<p><a href=\"index-members.php\">Return to home page</a></p>\n</body></html>\n");
    exit();
  }
?>

<!-- ****************** Main line ********** -->

<?php
    $result = mysql_query("select max(meetdate), min(meetdate) from meethistory", $db);

    $myrow = mysql_fetch_row($result);
?>
   
<h1 align=center>Dual and Master's Meet History</h1><hr>
<p>In our archives we have meet history from

<?php   
   echo "$myrow[1] to $myrow[0]. ";
?>
   
These are mostly Dual meets at
   this time but I will try to get all the Master's meets I have data on into the archive as well.
   </p>
<p>You may search the archive on any or all of the fields bellow. If you do not enter a name then
   all swimmers will be listed. If you do enter a name only that swimmer will be listed.
   If you select ALL or Any then you will see everything. For the Date you can select a match
   criteria. For example if you select Greater Than with Month Any, Day 05, and Year Any; then
   you will get meets that were on a day of the month after day five.</p>
<p>If you supply a name I use some huristics to create a search name. This is because
  the meet results are not all the same. Sometime the names are &quot;first last&quot;,
  sometime, &quot;first-initial period last&quot; and sometime just &quot;last&quot;.
  Therefore, the following entries create the following queries:</p>
<table border=1>
<tr><th>Entered Name</th><th>Query Name</th></tr>
<tr>
<td>Barton Phillips</td><td>b%phillips</td>
</tr><tr>
<td>B. Phillips</td><td>b%phillips</td>
</tr><tr>
<td>Barton L. Phillips</td><td>b%phillips</td>
</tr><tr>
<td>Phillips</td><td>%phillips</td>
</tr>
</table>
<p>The &quot;%&quot; stands for any characters. This means that if &quot;Bob Perry&quot;
  entered just &quot;Perry&quot; he would also get all of Melanies results but he would
  also see all the results that where entered as &quot;Robert Perry&quot;. Life is hard
  when you think like a computer. The moral of this story is that while 
  &quot;Consistancy is the hobgoblin of small minds&quot;, if you want to use computers
  you better think <b>small</b>. The solution for poor Bob is to use &quot;Perry&quot;
  and then ask for only <b>MENS</b> results</p>


<h3>You can add your special information to the database.</h3>
<p>For example, say you did the La Jolla Rough Water Swim for the last couple of years.
  You can add these times to  your personal times. 
<a href="AddTimes.php">Add My Times</a> -- Enjoy</p>

<hr>
<br>
   
<form action="/meetinfo.php" method="post">
                                          
<table border=0>
<tr>
<th align=left>Name:</th>
<td><input type="text" name="name"></td>
</tr>

<tr>
<th align=left>Age:</th>
<th align=left><select name="agematch">
<option value="=">Equal
<option value="<">Less Than
<option value=">">Greater Than
<option value="<=">Less Than or Equal
<option value=">=">Greater Than or Equal
</select>
Group:              
<select name="agerange">	
<option value="">ALL
<option>19-29
<option>30-34
<option>35-39
<option>40-44
<option>45-49
<option>50-54
<option>55-59
<option>60-64
<option value="65-">65 pluss
</select></th>
</tr>
              
<tr>
<th align=left>Sex:</th>
<td><select name="sex">
<option value="">I like both
<option value="m">Men
<option value="w">Women
</select></td>
</tr>              

<tr>
<th align=left>Course:</th>
<td><select name="course">
<option value="">All                 
<option value="25y-d">25 yard Dual Meet
<option value="25y-dc">25 yard Dual Meet Championship
<option value="25y-m">25 yard Masters
<option value="25y-mc">25 yard Masters Championship
<option value="25m-m">25 meter Masters
<option value="25m-mc">25 meter Masters Championship
<option value="50m-m">50 meter Masters
<option value="50m-mc">50 meter Masters Championship
<option value="other">Other (ocean etc.)
</select></td>
</tr>

<tr>
<th align=left>Team:</th>
<td><select name="team">
<option value="">All                 
<option value="s">Southwest Aquatic Masters
<option value="d">Daland Swim School
<option value="v">Ventura  
<option value="r">Rose Bowl             
</select></td>
</tr>              

<tr>
<th align=left>Event:</th>
<td><select name="event">
<option value="">ALL
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
<option>50br
<option>100br
<option>200br
<option>100im
<option>200im
<option>400im
<option>800im
</select></td>
</tr>
<tr>
<th align=left>Date:</th>
<th align=left>          
<select name="datematch">
<option value="=">Equal
<option value="<">Less Than
<option value=">">Greater Than
<option value="<=">Less Than or Equal
<option value=">=">Greater Than or Equal
</select>
Month:
<select name="month">
<option value="">Any
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
Day:
<select name="day">
<option value="">Any
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
Year:
<select name="year">
<option value="">Any
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
<option>2003
<option>2004
</select></th>
</tr>
<tr>
<input type=hidden name="dummy" value=1>
<td align=left><input type=submit></td>
</tr>
</table>

</form>

<h2>For computer Geeks <b>Only</b></h2>
<p>If you know SQL and would like to investigate the database in more detail you can submit
  full SQL queries here. For your protection and that of the database you can <b>only</b>
  submit <i>select</i> queries. The name of the table is <i>meethistory</i>.</p>
<p>Here are a few SQL examples:</p>
<table border=1>
<tr><th align=left>select * from meethistory where name='strybel' and event='50fl'</th></tr>
<tr><th align=left>select * from meethistory where name like '%strybel' and event='50fl'</th></tr>
<tr><th align=left>select name, time from meethistory where name like '%strybel' and event like '%fl'</th></tr>
<tr><th align=left>select max(time) as time, max(time) as agerange from meethistory where name like '%strybel' and event like '%fl'</th></tr>
<tr><th align=left>select count(*) as name from meethistory</th></tr>
</table>
<p>The first query will probably have no resulting rows. This is because &quot;name='strybel'&quot;
   requires an exact match with <b>strybel</b> and the entries in the table are either <b>Dave Strybel</b>
   or <b>David Strybel</b> (NOTE: case does <b>NOT</b> mater)</p>
<p>The second query will find results because we are looking for a partial match. The <i>like</i> comparison
  allows you to use wild cards (the % is the wind card designator) and means <b>zero or more</b>.</p>
<p>The thrid query will match any fly event. Dave has both 50 and 100 fly results. The code for the 50 and 
  100 fly are <b>50fl, 100fl</b>. This query only retuns two fields, the name and the time.</p>
<p>The fourth example selects the maxumum time for Dave's fly events. This time is in hundreds of a second in the 
  database. The <b>time</b> field in the results is converted to hours, munutes, seconds, and hundreds and
  is output as hh:mm:ss.xx (xx is humdreds). The <b>agerange</b> field does not under go any conversion
  so the raw hundreds will print out.</p>
<p>You are restricted to using the field names below, but the fields (except for time and adjtime) can be 
  used for agregate functions like <i>min, max, count, avg, sum</i> and others</p>
<p>The final example counts all the rows in the table.</p>
<p>You can cut and past the examples into the query box and try them out.</p>
<p>The database has the following schema:<br>

<pre>                           
+----------+-------------+------+-----+------------+-------+
| Field    | Type        | Null | Key | Default    | Extra |
+----------+-------------+------+-----+------------+-------+
| name     | varchar(40) |      | PRI |            |       |
| meetdate | date        |      | PRI | 0000-00-00 |       |
| course   | varchar(6)  | YES  |     | NULL       |       |
| sex      | varchar(4)  | YES  |     | NULL       |       |
| agerange | varchar(8)  | YES  |     | NULL       |       |
| team     | varchar(8)  | YES  |     | NULL       |       |
| event    | varchar(10) |      | PRI |            |       |
| time     | int(7)      | YES  |     | NULL       |       |
| adjtime  | int(7)      | YES  |     | NULL       |       |
+----------+-------------+------+-----+------------+-------+
</pre></p>

<p>Enjoy, and remember; Geeks will rule the world!</p>
<form action="/meetinfo.php" method="POST">
The two fields below are inserted into a 'select' query as follows:<br>
<code>select <i><b>fields</b></i> from meethistory where <i><b>where-clause</b></i></code><br>
If you want all of the fields put an '*' in the <i><b>fields</b></i> box, or put a comma ','
seperated list of the above fields.<br>
Put the body of the <i><b>where-clause</b></i> in the second text box.<br>For example,
<code>name like '%barton%'</code>
Don't put the 'select', 'from meethistory' or 'where' in the boxes.<br>
Enter <i><b>fields</b></i> you want in results: <input type=text name=fields size=60><br>
Enter <i><b>where-clause</b></i>: <input type=text name="where" size=60>
<br><input type=submit>
</form>

<?php include("fullfooter.php"); ?>
