<?php
  // Online.php
  // This is started by index.php and monitors who is on the swam web page.
  // Online show who's on line and allows the visitor to click on a name and
  // start a Chat session

  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
  header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
  header("Pragma: no-cache");                                   // HTTP/1.0

  // Show who is on line

  require("secureinfo/id.phpi");

  // The Swam database is now open

  $id = CheckId("no_inc");

  // Add our ID to the ONLINE table and set changed for all other users
  // ONLINE:
  // id int;
  // name varchar(30);
  // changed bool
  // item int autoincrement
  // new bool

  $result = mysql_query("select changed from online where id=$id", $db);

  if(!$result) {
    PhpError("online Error 1: " . mysql_error($db));
    exit();
  }

  // is id there?

  if(!($row = mysql_fetch_row($result))) {
    // ID is not pressent in online table
    // so this is a NEW visitor. Set changed for all others

    $result = mysql_query("update online set changed=1", $db);

    if(!$result) {
      PhpError("online Error 2: " . mysql_error($db));
      exit();
    }

    // Add this visitor to the online table with changed set to ZERO (no change)

    $result = mysql_query("insert into online (id,name,changed) values($id, '$SwamName', 0)", $db);
  } else {
    // If id is already in table then just force changed to zero now.

    $result = mysql_query("update online set changed=0 where id=$id", $db);
  }

  // Now make the page
?>

<html>
<head>
<title>SWAM-CHAT LIST</title>
<script language="JavaScript" type="text/javascript">
<!--
var ximage;
var cnt = 0;
var delay = 30000;      // initial delay between trips to the server is 30 seconds.

// Which browser are we using?

var NS = (document.layers) ? true : false;
var IE = (document.all) ? true : false;

var Reload = 0;

// Get the time info

function getDate()
{
  var d = new Date();

  return d.getTime();
}

// use an image to communicate with the server

function GetImage() {
  // get image and check the width of the return 

  ximage = new Image;
  var u = 'http://www.swam.us/checkchat.php?date=' + getDate();
  ximage.src = u;

  // After we get the image set the timer so we can process the information
  // in loop. We can't process it here! because it is not valid here.

  setTimeout('loop()', 200);
}

function Exit() {
  // When this page exits remove the user from the online table
  // use an image call to do it

  if(Reload == 0)
    open('http://www.swam.us/deleteit.php?date=' + getDate(), 'deleteit', 'width=400,height=100');
}

// Process image information

function loop()
{
  var w = ximage.width;

  // checkchat returns a bit mask:
  // bit 1 set = Always set
  // bit 2 set = change (not set = no change)
  // bit 4 set = message waiting

  // Are there any messages for this user? Check this first!
  // If we find any we open receive and then still look to see
  // if there is any change

  if(w & 4) {
    // Yes so open a message window. 

    window.open('http://www.swam.us/receive.php', 'receive', 'width=400,height=400,scrollbars');
    delay = 10000;      // change to 10 seconds
    cnt = 500;          // keep it there for cnt times through
  }

  if(w & 2) {
    // Online has changed. Someone new or someone left.

    Reload = 1; // we don't want to do the EXIT stuff

    location = 'http://www.swam.us/online.php';
    return;
  }

  Reload = 0;

  if(cnt) {
    --cnt;
  } else {
    delay = 30000;      // set the delay back to 30 seconds after a while
  }

  // Every once in a while we will go to the server via the image again.

  setTimeout('GetImage()', delay);
}	

// Open the Send message window

function OpenSend(target) {
  window.open("/send.php?targetId=" + target, "send", "width=550,height=500,scrollbars");
}

//-->
</script>

</head>

<body onLoad="GetImage();" onUnload="Exit();">

<h1 align=center>These are your team mates who are online now</h1>
<hr>

<table>

<?php
  // get all the people on line now

  $result = mysql_query("select id, name from online where id != $id", $db);

  if(!$result) {
    PhpError("online Error 3: " . mysql_error($db));
    exit();
  }

  $cnt = 0;
  while($row = mysql_fetch_row($result)) {
    // output the information

    $targetId = $row[0];
    $name = $row[1];
    ++$cnt;
    // If user clicks on this name start a send window.

    print("<tr><td><a href=\"javascript:OpenSend($targetId);\">$name</a></td></tr>\n");
  }

  if($cnt == 0) {
    print("<tr><td>No One Online Now</td></tr>\n");
  }
?>

</table>
<hr>
<p>You can chat with whom ever is on line. Just click on the name. A pop-up send dialog
box will appear. Enter your message and click "Send". A pop-up receive dialog box will
pop-up on the other persons machine. You can chat with more than one person at a time. 
For each person you will get one send dialog box. All messages to you will appear in the same
receive dialog box. If you have problems with this new feature let 
<a href="mailto:barton@applitec.com">me</a> know</p>

</body>
</html>


