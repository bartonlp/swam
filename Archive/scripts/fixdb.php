<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

// Make the swammembers table
$query = <<<EOF
create table swammembers (
  `id` int(11) not null auto_increment,
  `fname` varchar(255),
  `lname` varchar(255),
  `name` varchar(255) comment 'full name as it was in account table',
  `email` varchar(255),
  `visits` int(11),
  `lasttime` timestamp,
  `visittime` datetime,
  `firsttime` datetime,
  `address` varchar(30) default NULL,
  `city` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `gender` varchar(7) default NULL,
  `zip` varchar(15) default NULL,
  `country` varchar(10) default NULL,
  `phone` varchar(20) default NULL,
  `bday` varchar(12) default NULL,
  `team` varchar(20) default NULL,
  `lastmsg` int(11) default NULL,
  `secret` char(3) default NULL,
  `realEmail` varchar(40) default NULL,
  primary key(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOF;

$S->query("drop table if exists swammembers");

$S->query($query);
list($result, $n) = $S->query("select * from account where team is not null and team != ''", true);

while($row = mysql_fetch_assoc($result)) {
  foreach($row as $k=>$v) {
    $$k = $S->escape($v);
  }
  
  $ar = preg_split("/\s/", $name);
  
  if(count($ar) > 2) {
    echo "extra " . count($ar) ." : name=$name\n";
    echo "Enter fname: ";
    $t = fread(STDIN, 255);
    $fname = $S->escape(rtrim($t, "\n"));
    echo "Enter lname: ";
    $t = fread(STDIN, 255);
    $lname = $S->escape(rtrim($t, "\n"));
    
  } else {
    $fname = $ar[0];
    $lname = $ar[1];
  }
  echo "fname=$fname, lname=$lname, name=$name\n";
  
  $S->query("insert into swammembers (id, fname, lname, name, email, visits, lasttime, visittime, firsttime, address, city, state, ".
            "gender, zip, country, phone, bday, team, lastmsg, secret, realEmail) ".
            "values('$id', '$fname', '$lname', '$name', '$email', '$count', '$lasttime', '$visittime', '$firsttime', " .
            "'$address', '$city', '$state', ".
            "'$gender', '$zip', '$country', '$phone', '$bday', '$team', '$lastmsg', '$secret', '$realEmail')");
    
  
}
?>