<?php
echo <<<EOF
<html>
<head>
<script type='text/javascript' language='JavaScript'
         src='accounts.js'></script>

</head>
<body onload='DoOsCheck()' onunload='ClosePopup()'>

<p>\$_SERVER info<br>
EOF;

foreach($_SERVER as $key=>$item) {
  echo "$key::-> '$item'<br>\n";
}
echo "ARGV array:<br>\n"
   
foreach($_SERVER['argv'] as $item) {
  echo "$item<br>\n";
}

echo <<<EOF
</p>
<br><input type=button value='Check the Features of Your Browser' onclick='CheckOs(1)'>&nbsp;
<input type=button value='Show Navigator Object Info' onclick='ShowAll()'>
</body>
</html>
EOF;
?>