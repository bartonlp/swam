<?php
// Hit counter using MySql
//
// Table counter:
// filename is key; name of the file we are counting
// count; number of times accessed
// timestamp; last time counted
//
//CREATE TABLE IF NOT EXISTS `counter` (
//   `filename` varchar(255) NOT NULL,
//   `count` int(11),
//   `last` timestamp,
//  PRIMARY KEY  (`filename`)
//) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// First thing to do is open the database
// For SWAM this should already have happended in the header
// includes, require("secureinfo/id.phpi");. Now you can use $db.

$filename = $_SERVER['PHP_SELF']; // get the name of the file

// A message can be passed by setting the $CounterMessage variable in
// the caller. If not set then defaults to 'Number of Hits'

if(!isset($CounterMessage)) {
  $CounterMessage = "Number of Hits
                  Since March 15 2010";
}

// Now select the 'count' for the web page filename.

$query = "select count from counter where filename='$filename'";

$result = mysql_query($query, $db);

if(!$result) {
  // NOTE: I have changed PhpError to include the query. The new code
  // looks like this:
// 
// if(!function_exists('phperror')) {
// 
//   function PhpError($msg, $query) {
//     global $PHP_SELF;
// 
//     echo "<h1>DEBUG INFO: ERROR in $PHP_SELF:</h1>\n";
//     echo "<p>$msg</p>\n";
//     echo "<p>Query: $query</p>\n";
// //  mail("info@granbyrotary.org", "PhpError in $PHP_SELF", "Message:
// //  $msg\nQuery=$query");  
//   }
// }

  // If you don't change your code you can either change this to
  // match your code, or add the above function to this file and
  // change the name.
  PhpError(mysql_error($db), $query);
  exit();
}

// Check that we found a row. If not then we need to insert the new
// page name.

if(mysql_num_rows($result) == 0) {
  // Not in database yet so create an entry for this file

  $query = "insert into counter (filename, count) values('$filename', '1')";
  $result = mysql_query($query, $db);

  if(!$result) {
    PhpError(mysql_error($db), $query);
    exit();
  }

$count = 1;
} else {
  // The page name is already in the table so get the old value and
  // update it.
  
  $row = mysql_fetch_row($result);
  $count = $row[0]; // current count

  ++$count; // add one and update the table
    
  $query = "update counter set count='$count' where filename='$filename'";
  $result = mysql_query($query, $db);

  if(!$result) {
    PhpError(mysql_error($db), $query);
    exit();
  }
}

// The ctrnumber.php returns an image. The possible arguments are:
// s=font size. if not pressent defaults to 11
// text=the message which is usually a number like 11 etc. If not
// pressent then blank.
// font=font file, like TIMES.TTF. If not pressent defaults to
// TIMESBD.TTF

// the id's hitCounter, hitCounterp, hitCountertbl, hitCountertr,
// hitCounterth, and ctrnumbers can be defined in the CSS, here is an
// example:
// /* Hit Counter (counter.php) */
// 
// #hitCounter {
//         margin-left: auto;
//         margin-right: auto;
//         width: 50%;
//         text-align: center;
// }
// 
// #hitCountertbl { /* the table that the number of hits is contained in */
//         border: 8px ridge yellow;
//         margin-left: auto;
//         margin-right: auto;
//         background-color: #F5DEB3; /* wheat, rgb=245,222,179 hex=#F5DEB3 */
// }
// 
// #ctrnumbers { /* the img inside the table */
//         background-color: #F5DEB3; /* wheat, rgb=245,222,179 hex=#F5DEB3 */
// }
// 

print("
<div id='hitCounter'>
   <p id='hitCounterp'>$CounterMessage
      <table id='hitCountertbl'>
         <tr id='hitCountertr'>
            <th id='hitCounterth'><img id='ctrnumbers' src='ctrnumbers.php?s=16&amp;text=$count' alt='$count'></th>
         </tr>
      </table>
   </p>
</div>
");
  
?>
