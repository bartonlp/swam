<?php
  // Reset user Cookies

  $PHP_SELF = $_SERVER['PHP_SELF'];
  
  $expire = time() -(60*60*24*3);  // expire at once

  $ref = ".zupons.net";

  // Type is Blank on entry

  $type = $_POST['type'];
  
  if($type) {
    if(is_array($_COOKIE[$type])) {
      while(list($key, $val) = each($_COOKIE[$type])) {
        setcookie("$type" . "[$key]", "", $expire, "/", $ref);
        $ar[$key] = "Delete $type" . "[$key]";
      }
      while(list($it, $itval) = each($ar))
        print("$itval<br>");

    } else
      setcookie("$type", "", $expire, "/", $ref);
  } else {
    print("
<html>
<head>
<link rel='stylesheet' href='swam.css' type='text/css'>
</head>
<body>
<h1 align=center>Remove Cookies from this Computer</h1>
<hr>
<p>The list below are the 'cookie' keys on this system. If you select (using the radio buttons)
an entry and then press 'submit' all the entries for that 'cookie' key will be removed. 
You may want to do this if you are using someone elses computer so they do not have access to
your information. Please NOTE that you could also be removing something that does not belong to 
you, but that is unlikely as ONLY keys for the domain 'applitec.com' are removed.</p>
<hr>

<table border=1 bgcolor=yellow cellpadding=5>
<tr>
<th>Key</th><th>Remove It</th>
</tr>
<form action=\"$PHP_SELF\" method=post>\n"
    );

    // Loop through all of the cookies we got from the client

    while(list($key, $val) = each($_COOKIE)) {
      // Print a line for each with a radio button

      print("<tr><td>$key:</td><th><input type=radio name=type value=\"$key\"></th></tr>\n");
    }

    print("
<tr>
<td align=center colspan=2><input type=submit></td>
</tr>
</form>
</table>
<hr><a href='index-members.php'>Return to Swam Home Page</a>
</body>
</html>\n"
    );

    exit();
  }
?>

<html>
<body>
<?php echo "<h1>Cookies for $type deleted</h1>"; ?>
</body>
</html>

