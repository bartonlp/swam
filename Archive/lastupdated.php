<?php
	// Include file.
  // returns the last updated time
	
  echo date("M d, Y H:i:s", filemtime("/var/www/html/$PHP_SELF"));
?>
