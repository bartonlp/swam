<?php
require("secureinfo/id.phpi");

$DEBUG = 0;

// server for yourlinks.php

   Header("Content-type: text/plain");



   if($act == "add") {
     // add new entry
     $query = "insert into mylinks values('$acc', '$desc', '$link')";

     if($DEBUG) {
       $fd = fopen("/ajaxlog.txt", "a");
       fputs($fd, "query=$query\n");
       fclose($fd);
     }

     $result = mysql_query($query, $db);

     if(!$result) {
       //PhpError("yourlinks.phpi Error 1: " . mysql_error($db));
       print("ERROR: ADD " . mysql_error($db));
       exit();
     }
   } else {
     // delete

     $query = "delete from mylinks where account=$acc and description='$desc' and link='$link'";


     if($DEBUG) {
       $fd = fopen("/ajaxlog.txt", "a");
       fputs($fd, "query=$query\n");
       fclose($fd);
     }

     $result = mysql_query($query, $db);

     if(!$result) {
       //PhpError("yourlinks.phpi Error 1: " . mysql_error($db));
       print("ERROR: DELETE " . mysql_error($db));
       exit();
     }
   }

   $value = "$act:$desc,$acc,$link";
   print($value);
?>
