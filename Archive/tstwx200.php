<?php
// test wx200 via a pipe

$wx = popen("/usr/local/bin/wx200 --mph --nounits --temp --humidity --sea --average bartonphillips.com", "r");

$line = fread($wx, 2000);

pclose($wx);

$date = date("l, F d, Y T (O \G\M\T)");

preg_match("/(\d+\.\d+)[\t ]+(\d+)[\t ]+(\d+\.\d+)[\t ]+(\d+\.\d+)[\t ]+(\d+)/", $line, $match);

$temp = $match[1];
$humid = $match[2];
$baro = $match[3];
$windsp = $match[4];
$winddir = $match[5];

print("Current Readings for $date<br>\n".
			"<li>Temperature: $temp&deg; F</li>\n".
			"<li>Humidity: $humid%</li>\n".
			"<li>Barometer: $baro in hg.</li>\n".
			"<li>Winds: $windsp mph at $winddir&deg; </li>\n");

exit();

?>