<?php
  // Count number of hits for this site
  // This file is included at the point we want the counter to occur!

  // But DONT Count Applitec.com

  // For all others check to see if the count file already exists

  $filename = str_replace("/", "_", substr($PHP_SELF, 1)) . ".log";
//  $fd = @fopen("/var/www/html/counterLogs/$filename", "r");
  $fd = @fopen("/usr/lib/cgi-bin/counterLogs/$filename", "r");
//  $fd = @fopen("/swam/counterLogs/index-zupons.php.log" , "r");
//  print "$filename<br>";

  // If it does then get the count else set count to 1

  if(!$fd) {
    $line = 1;
  } else {
    $line = trim(fgets($fd, 100));
    fclose($fd);
  }

  $dispnum = chunk_split($line, 1, "+");

  // Display the visitor box

  echo "<center>You are visitor<br><table border=2><tr><th><img src='/ctrnumbers.php?s=16&amp;text=$dispnum' alt=$line></th></tr></table>Since Jan. 1, 2007</center>";

// <div id="hitCntr">
//   Number of Hits
//   <div>
//    <span>
//     <?php print("$line");
//    </span>
//   </div>
//  Since. Jan ,2004
//</div>


//  if(!strstr($REMOTE_HOST, "applitec.com")) {
    // Now open for write and update the count
//    $fd = fopen("/swam/counterLogs/index-zupons.php.log", "w");
//    $fd = fopen("/var/www/html/counterLogs/$filename", "w");
    $fd = fopen("/usr/lib/cgi-bin/counterLogs/$filename", "w");  
    fputs($fd, ++$line);
    fclose($fd);
//  }
?>
