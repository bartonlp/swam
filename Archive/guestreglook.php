<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->banner = "<h1 style='text-align: center'>South West Aquatic Masters Guest Book</h1><hr>";
$h->title = "Guest Book";

list($top, $footer) = $S->getPageTopBottom($h);

// Look at what has been said in the guest book

$result = $S->query("select id, name, date_format(firsttime, '%m-%d-%Y %H:%i') as first_time " .
                    "from swammembers where visits != 0 order by firsttime desc");

$tbl = "";

while($row = mysql_fetch_assoc($result)) {
  $name = stripslashes(ucwords($row['name']));
  $id = $row['id'];
  $firsttime = $row['first_time'];
    
  list($result2, $cnt) = $S->query("select feedback, date_format(feedback_time, '%m-%d-%Y %H:%i') as feedback_time ".
                                   "from feedback where id=$id", true);

  if(!$cnt) {
    $tbl .= "<tr><td>$name</td><td>$firsttime</td><td>NO COMMENT</td></tr>";
    continue;
  }
   
  $row2 = mysql_fetch_array($result2);

  $feedback = stripslashes($row2['feedback']);
   
  $tbl .= "<tr bgcolor=#FFC1C1><td>$name</td><td>$firsttime</td><td>$feedback</td></tr>" .
         $cnt == 1 ? "" : "S" . $cnt == 1 ? "S" : "";
   
  while($row2 = mysql_fetch_array($result2)) {
    $date = $row2['feedback_time'];
    $feedback = stripslashes($row2['feedback']);

    $tbl .= "<tr bgcolor=#C5FFC5><td style='text-align: right'>More feedback</td><td>$date</td><td>$feedback</td></tr>\n";
  }
}

echo <<<EOF
$top
<h3>Who has signed our book and what did they have to say?</h3>
</div>

<table border=1 bgcolor=#feffcb>
<tr>
<th>Name</th><th>When</th><th>Comment</th>
</tr>
$tbl
</table>
$footer
EOF;
?>



