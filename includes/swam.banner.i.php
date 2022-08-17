<?php
return <<<EOF
<div id="swamHdr">
<h1 class='header'>South West Aquatic Masters</h1>
<img id='logo' data-image='$this->trackerImg1' src='images/swamlogo.gif' alt='Swam Logo'>
<img src='tracker.php?page=normal&id=$this->LAST_ID'>
$mainTitle
</div>
<noscript>
<p style='color: red; background-color: #FFE4E1; padding: 10px'>
<img src='tracker.php?page=noscript&id=$this->LAST_ID'>
Your browser either does not support <b>JavaScripts</b> or you have JavaScripts disabled, in either case your browsing
experience will be significantly impaired. If your browser supports JavaScripts but you have it disabled consider enabaling
JavaScripts conditionally if your browser supports that. Sorry for the inconvienence.
</noscript>
EOF;
