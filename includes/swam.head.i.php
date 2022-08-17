<?php

return <<<EOF
<head>
  <!-- NEW SERVER -->
  <title>{$arg['title']}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="Content-Language" content="en-us"/>
  <meta name="description" content="{$arg['desc']}"/>
  <meta name="copyright" content="2010-2022, Southwest Aquatic Masters"/>
  <meta name="Author" content="Barton L. Phillips, mailto:barton@bartonphillips.com"/>
  <meta name="keywords" content="Swimming, Masters swimming, Swim Meets, Sports, Athletics, Swimming Coaching,
              Swimming Competition, Califronia Swimming, Southern California Swimming,">

  <!-- Link our custom CSS -->
  <link rel="shortcut icon" href="/swam/favicon.ico" type="image/x-icon"> 
  <link href="css/swam.css" rel="stylesheet">
{$arg['link']}
  <!-- jQuery from Google site. NOTE toggle was depreciated in 1.8 so we need 1.7 -->
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script> var trackerUrl = 'tracker.php', beaconUrl = 'beacon.php'; </script>
  <script data-lastid="$this->LAST_ID" src="js/tracker.js"></script>
{$arg['extra']}
{$arg['script']}
{$arg['css']}
</head>
EOF;

