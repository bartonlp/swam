#!/usr/bin/php -q
<?php

require("secureinfo/id.phpi");

try {
  mysql_query("delete from bboard where bbtime < date_sub(curdate(), interval 2 week)", $db);
  $n = mysql_affected_rows($db);
  echo "rows deleted from bboard: $n\n";
} catch(Exception $e) {
  echo "$e\n";
  exit();
}
try {
  mysql_query("delete from bbsreadmsg where msg_date <  date_sub(curdate(), interval 2 week)", $db);
  $n = mysql_affected_rows($db);
  echo "rows deleted from bbsreadmsg: $n\n";
} catch(Exception $e) {
  echo "$e\n";
  exit();
}

?>
