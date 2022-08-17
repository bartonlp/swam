<?php

echo "<h1>Show PHP Errors</h1>";

if($_POST['page'] == 'Submit') {
  echo "<h2>DELETE</h2>";
  if(file_put_contents("/tmp/PHP_ERROR.log", '') === false) {
    echo "Delete Failed<br>";
  }
}

$log = file_get_contents("/tmp/PHP_ERROR.log");

echo <<<EOF
<style>
  input { border-radius: 5px; background: green; color: white; }
</style>  
<form action="showErrorLog.php" method='post'>
  <input type='submit' name='page' value='Submit'>
</form>
<pre>$log</pre>                                      
EOF;
