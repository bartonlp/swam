<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->title = "About Our Use of Cookies";
$h->banner = "<h1 align=center>About Our Use of Cookies</h1><hr>";
list($top, $footer) = $S->getPageTopBottom($h);

$list = "";

while(list($key, $val) = each($_COOKIE)) {
  if(is_array($val)) {
    ksort($val);
    while(list($key1, $val1) = each($val)) {
      $list .= "<li>$key[$key1] = $val1</li>";
    }
  } else {
    $list .= "<li>$key = $val</li>";
  }
}

echo <<<EOF
$top
<p><b style="font-size: 1.2em;">First, if you have lost your COOKIE:</b><br>
If you have registered and now your cookie is missing we can try to find you in our database and
reset your cookie. <a href="needtoreg.php">Find Me!</a></p>

<p><b style="font-size: 1.2em;">Now about cookies:</b><br>
South West Aquatic Master's stores some information on your
local system to help us better service your needs. We use browser based
&quot;<u>COOKIES</u>&quot; to do this. If your browser does not support
cookies or you have disabled the use of cookies via the browser's preferences,
or options we will be unable to provide the extra services. If you have 
disabled the use of cookies you might wish to reconsider. Cookies are 
usually enabled in both Netscape and Internet Explorer by default. If you 
are especially concerned you can set the options to ask if you will allow
cookies on an individual bases rather than disabling cookies altogether.</p>

<h3>South West Aquatic Master's uses the following cookies:</h3>

<ul>
<li>SiteId</li>
<li>SWAM_visitor[1] (old)</li>
</ul>

<p>We store a unique ID number in <b>SiteId</b> the first time
you visit our site. We use this ID number to keep track of your visits. 
Should you register with us we will provide you with access to areas of
our site that are not accessible by non-registered visitors.</p>

<p>The <i>RemindMe</i> service saves information in <b>password</b> if you have used this service.</p>

<p>The <i>YourAddressBook</i> service saves information in <b>addrfinder</b> if you have used this 
service.</p>

<h3>Here is a list of all the cookies that South West Aquatic Master's currently has on you:</h3>

<table border=1 bgcolor=yellow cellpadding=10>
<tr>
<td>
$list
</td>
</tr>
</table>
<h3>If you want to remove any of the above keys you can do so: 
<a href='resetcookie.php'>REMOVE COOKIE</a>.</h3>
<p>You may want to remove a cookie if you are using someone elses computer and you don't
want them to have access to you information. The above link will ONLY remove cookies for the
domain "swam.us", it will not remove ANY other cookies from the computer you are using.</p>

<h3>Where are the Cookies?</h3>
<p>Your cookies are saved as plain text files by both Netscape and Internet 
Explorer. Each browser handles the files differently. Netscape maintains one
file for all the cookies. It is usually located in a directory under Netscape|Users
and then the user name. The file is &quot;cookies.txt&quot;.</p>

<p>Internet Explorer on the other hand keeps its cookies under either Windows or WINNT
directory. There are a lot of files all with the &quot;.txt&quot; extension.
For Windows they are in a directory under
Windows called cookies. For NT they are under Winnt|Profiles|&lt;user&gt;|Cookies. 
The files are named after the site that owns the cookie. For us that will be
something like &quot;&lt;username&gt;@swam.txt&quot;. 
It is possible that there
may be more than one swam.txt in which case there is a number appended to
the domain name like &quot;&lt;username&gt;@swam[1].txt&quot;. The username will
be your user name.</p>

<h3>You can find information about cookies at:</h3>
<ul>
  <li><a href="http://www.cookiecentral.com/">http://www.cookiecentral.com</a>.</li>
  <li><a href="http://msdn.microsoft.com/library/partbook/vb6/persistentclientsidedatausingcookies.htm">www.microsoft.com</a>.</li>
</ul>
$footer
EOF;
?>
