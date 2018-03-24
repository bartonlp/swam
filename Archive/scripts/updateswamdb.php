<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

/*
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
*/

// We want to update the swammembers table from the account table. This is used when moving the database from John's home
// machine to Lamphost
// Most account id's are already on the new server and we want to just update some info.
// However, there may be some new members and we want to move their info over.

// First get all of the records from account where the team field is not blank!
// We skip all of the rest

list($result, $n) = $S->query("select * from account where team is not null and team != ''", true);

while($row = mysql_fetch_assoc($result)) {
  // Look at the swammembers table and see if the id is already there

  list($result2, $n) = $S->query("select * from swammembers where id='{$row['id']}'", true);

  if($n) {
    // There is already an entry for this member so just update the fields that may be new
    // The following fields should be updated from the account table:
    // visits=$count
    // email=$email (if not the same)
    // lasttime=$lasttime
    // visittime=$visittime
    // address, city, state, zip, phone bday, lastmsg, secret, and realEmail if they have changed.

    // now look at each of the items from the account and swammembers tables to see what is new

    $row2 = mysql_fetch_assoc($result2);

    $query = '';
    
    foreach($row as $k=>$v) {
      // First is the such a field in swammembers? then is the record in swammembers the same as the record in account?

      $k1 = ($k == "count") ? "visits" : $k;
      
      if(($row2[$k1]) && ($row2[$k1] != $v)) {
        if($k == "count") {
          if($v < $row2[$k1]) {
            continue;
          }
        }
        
        if($k == "lasttime") {
          if($v < $row2[$k]) continue;
        }
        
        //echo "name={$row2['name']}: $k=$v: k1=$k1, row2={$row2[$k1]}\n";
        
        $v = $S->escape($v); // make it mysql safe
        $query .= "$k1='$v',";
      }
    }
    
    if(!$query) {
      //echo "No Change for id={$row2['id']}: {$row2['name']}\n";
      continue; // The record that's in swammembers is up to date.
    }
    
    $query = "update swammembers set " . (rtrim($query, ',')) . " where id='{$row['id']}'";
    echo "Name: {$row['name']} = $query\n";

     $S->query($query);
  } else {
    // Escape all of the fields for mysql
  
    foreach($row as $k=>$v) {
      $$k = $S->escape($v); // This is like extract() but escapes fields
    }

    // There is no record in swammembers so add it like we did originally
    
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

    $query = "insert into swammembers (id, fname, lname, name, email, visits, lasttime, visittime, " .
              "firsttime, address, city, state, ".
              "gender, zip, country, phone, bday, team, lastmsg, secret, realEmail) ".
              "values('$id', '$fname', '$lname', '$name', '$email', '$count', '$lasttime', '$visittime', '$firsttime', " .
              "'$address', '$city', '$state', ".
              "'$gender', '$zip', '$country', '$phone', '$bday', '$team', '$lastmsg', '$secret', '$realEmail')";

    echo "$query\n";
    $S->query($query);
  }
}
?>