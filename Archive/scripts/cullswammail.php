#!/usr/bin/php -q
<?php

require("secureinfo/id.phpi");

try {
  mysql_query("delete from swammailmsg where datesent < date_sub(curdate(), interval 2 week)", $db);
  $n = mysql_affected_rows($db);
  echo "rows deleted from swammail: $n\n";
} catch(Exception $e) {
  echo "$e\n";
  exit();
}

?>

