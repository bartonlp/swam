<?php
require("secureinfo/id.phpi"); require("trackvisitor.phpi");
?> <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta name="Author" content="Barton L. Phillips, Applitec Inc., mailto:barton@applitec.com">
<meta name="Description" content="South West Aquatic Master Swimming at Pierce College, Woodland Hills, California. A fun coached Masters swimming workout plus Masters swimming competition.">
<meta name="KeyWords" content="Swimming, Masters swimming, Swim Meets, Sports, Athletics, Swimming Coaching, Swimming Competition, Califronia Swimming, Southern California Swimming,">
<link rev=made href="mailto:barton@applitec.com">

<link rel="stylesheet" href="swam.css" type="text/css" title="swam-default">
<link rel="alternate stylesheet" href="swam-big.css" type="text/css" title="swam-big-fonts">

<title>Setup My Links</title>

<script type="text/javascript" language="JavaScript">
<!--

var BASE_URL = "http://www.swam.us";
var pingAJAX;

function addurl(self, acc) {
  desc = document.getElementById('addDesc');
  link = document.getElementById('addLink');

  doAction('add',acc, desc.value, link.value);
}

function deleteitem(desc, acc, link) {
  doAction('del', acc, desc, link);
}


function doAction(act, acc, desc, link) {
  // Instantiate an object
  // Test to see if XMLHttpRequest is a defined object type for the user's browser
  // If not, assume we're running IE and attempty to instantiate the MS XMLHTtp object
  // Don't be confused by the ActiveXObject indicator. Use of this code will not trigger
  // a security alert since the ActiveXObject is baked into IE and you aren't downloading it
  // into the IE runtime engine
  if ( window.XMLHttpRequest ) {
    pingAJAX = new XMLHttpRequest();
  } else {
    pingAJAX = new ActiveXObject("MSXML2.XMLHTTP");
  }

  // In Javascript, everything is an object. Functions are objects, everything inherits from Object
  // So assigning onreadystatechange to pingCallback means that you can call pingCallback by doing the following
  // pingAJAX.onreadystatechange( "blahblah")
  pingAJAX.onreadystatechange = pingCallback;

  // The open statement initializes the request. In this example, we'll just pass the value in the URL.
  pingAJAX.open( "POST", BASE_URL + "/yourlinks-server.php?act=" + encode(act)+ "&desc=" + encode(desc)+"&acc="+encode(acc)+"&link="+encode(link), true );

  // Send request to the server
  pingAJAX.send(null);
}

function pingCallback() {
  if(pingAJAX.readyState == 4) {
    //alert("Got response: "+pingAJAX.responseText);
    ret = pingAJAX.responseText.split(":");
    if(ret[0] == "del") {
      row = document.getElementById(ret[1]);
      row.className = 'hide';
    } else if(ret[0] == "add") {
      //alert("added: "+ ret[1]);
      id = ret[1].split(",");
      //alert("acc="+id[1]+" desc="+id[0]+" link="+id[2]);

      document.getElementById('addDesc').value = "";
      document.getElementById('addLink').value = "";
      tbl = document.getElementById('mylinks');

      tr = document.createElement("tr");
      td1 = document.createElement("td");
      td2 = document.createElement("td");
      td3 = document.createElement("td");
      but = document.createElement("input");

      tr.setAttribute("id", ret[1]);
      td1.setAttribute("class", "liteYellow");
      td2.setAttribute("class", "liteYellow");

      but.setAttribute("type", "button");
      but.setAttribute("value", "Delete");
      but.setAttribute("onclick", 'deleteitem("'+id[0]+'",'+id[1]+',"'+id[2]+'")');

      td1.appendChild(document.createTextNode(id[0]));
      td2.appendChild(document.createTextNode(id[2]));

      td3.appendChild(but);

      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);

      // Add new item to table
      tbl.appendChild(tr);

    } else {
      alert("ERROR: "+ret[0]+" -> "+ret[1]);
    }
  }
}

// If you plan on doing anything outside of North America, then you'd better encode the things you pass back and forth
// the escape() method in Javascript is deprecated -- should use encodeURIComponent if available
function encode( uri ) {
  if (encodeURIComponent) {
    return encodeURIComponent(uri);
  }

  if (escape) {
    return escape(uri);
  }
}

function decode( uri ) {
  uri = uri.replace(/\+/g, ' ');

  if (decodeURIComponent) {
    return decodeURIComponent(uri);
  }

  if (unescape) {
    return unescape(uri);
  }

  return uri;
}


//-->
</script>

</head>

<body>

<?php
include("header.phpi");
?>

<p>You can create a list of your links that will appear on the web
page. Make SWAM your home page and go where you want from here. The
links will appear on the main page just below the first lane line.</p>

<p>To add links just fill in the <u>"Description</u> and the <u>URL of link</u>. For
example, if you want www.yahoo.com as your link you would put
something like "Yahoo Home Page" in <u>Description</u> and "www.yahoo.com" in
<u>URL of link</u>.</p>
<p>When you first start the <u>My Links</u> table is empty.</p>
<p>Once you add a link you will see the links in a table with a
<u>Delete</u> button after it. To remove the link just press the
delete button.</p>
<p class='red'><b>Note:</b> This may not work quite right on Internet
Explorer especially older versions. If an <u>Add</u> does not appear
in the <u>My Links</u> table you may need to do a refresh. On old
version of IE nothing may work at all! Sorry, but for all kinds of
reasons you should at least upgrade to IE6 (better yet Firefox).</p>

<?php
  //print("ID=$SwamId<br>");

  // Check to see if there are any entries in the database.

  $result = mysql_query("select * from mylinks where account = $SwamId", $db);

  if(!$result) {
    PhpError("yourlinks.phpi Error 1: " . mysql_error($db));
    exit();
  }

      print("
<table id='mylinks'>
<tr><th colspan='3'>My Links</th></tr>
");

  if(mysql_num_rows($result) != 0) {
    // Create table


    while($row = mysql_fetch_row($result)) {
      $mylinksAcc = $row[0];
      $mylinksDesc = $row[1];
      $mylinksLink = $row[2];

      // Create link line

      print("
<tr id='$mylinksDesc,$mylinksAcc,$mylinksLink'>
<td class='liteYellow'>$mylinksDesc</td>
<td class='liteYellow'<a href='http://$mylinksLink'>$mylinksLink</a></td>
<td><input type='button' value='Delete' onclick='deleteitem(\"$mylinksDesc\", $mylinksAcc, \"$mylinksLink\")'></td>
</tr>
");
    }
  }

  print("
</table>
<br>
");


?>

<table id='newlink'>
<tr>
<td>
Description: <input id='addDesc' type='text'>
</td>
<td>URL of Link: <input id='addLink' type='text'>
</td>
<td>
<input type='submit' value='Add' onclick='addurl(this, <?php echo $SwamId ?>)'>
</td>
</table>

<?php
include("fullfooter.php");
?>
