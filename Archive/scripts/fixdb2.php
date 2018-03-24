<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

list($result, $n) = $S->query("select * from account where team is not null and team != ''", true);

while($row = mysql_fetch_assoc($result)) {
  extract($row);
  
  $S->query("update swammembers set visits='$count' where id='$id'");
}
?>