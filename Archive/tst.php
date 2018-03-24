<?php

$fd = fopen("http://www.bartonphillips.com/wx200/wx200cur.txt", "r");

$line = "";

while(!feof($fd)) {
	$line .= fgets($fd, 1000);
}

fclose($fd);

echo "$line<br>";	

$num = preg_match("/(\d+\.\d*) F[\t ]+(\d+\.\d+) in[\t ]+(\d+\.\d+) m\/s[\t ]+(\d+ .*)/", $line, $match);

echo "num=$num, match=$match[0], $match[1], $match[2], $match[3], $match[4]<br>";	
?>

