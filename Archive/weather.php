<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;
   
$h->extra = <<<EOF
  <style type="text/css">
#OsMsg {
  border: 1px solid black;
  font-family: sans-serif;
  width: 80%;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 10px;
  padding: 5px 10px 5px 10px;
  background-color: #d0ffff;
}
.show {
  display: block;
}
.hide {
  display: none;
}
  </style>
EOF;

$h->title = "Weather Stuff";
$h->banner = "<h1>Weather Info</h1";
$top = $S->getPageTop($h);
$footer = $S->getFooter();

echo <<<EOF
$top
<hr>
<!-- Begin weather.com magnet include
<script type="text/javascript"
src='http://voap.weather.com/weather/oap/91320?template=LAWNH&amp;par=null&amp;unit=0&amp;key=0c8ce3ae39921befbff3dc5fe0e4a2c8?'>
</script><br><br>
<script type="text/javascript"
src='http://voap.weather.com/weather/oap/91304?template=LAWNH&amp;par=null&amp;unit=0&amp;key=0c8ce3ae39921befbff3dc5fe0e4a2c8'>
</script>
 End weather.com magnet include -->

<table>
<tr>
<td>
<div id="wx_module_2453">
   <a href="http://www.weather.com/weather/local/USCA1261">Woodland Hills Weather Forecast, CA</a>
</div>

<script type="text/javascript">
   var wx_locID = 'USCA1261';
   var wx_targetDiv = 'wx_module_2453';
   var wx_config='SZ=300x250*WX=FHW*LNK=SSNL*UNT=F*BGI=seasonal1*MAP=CSC|null*DN=swam.us*TIER=0*PID=1040996069*MD5=f00d9941a0f4768c429ce74ab039a5a9';

   document.write('<scr'+'ipt src="'+document.location.protocol+'//wow.weather.com/weather/wow/module/'+wx_locID+'?config='+wx_config+'&proto='+document.location.protocol+'&target='+wx_targetDiv+'"></scr'+'ipt>');  
</script>
</td>
<td>
<div id="wx_module_4706">
   <a href="http://www.weather.com/weather/local/91320">Newbury Park Weather Forecast, CA</a>
</div>

<script type="text/javascript">
   var wx_locID = '91320';
   var wx_targetDiv = 'wx_module_4706';
   var wx_config='SZ=300x250*WX=FHW*LNK=SSNL*UNT=F*BGI=boat*MAP=null|null*DN=swam.us*TIER=0*PID=1040996069*MD5=5f4b40cc5dae4abe19cfc02865d788f9';

   document.write('<scr'+'ipt src="'+document.location.protocol+'//wow.weather.com/weather/wow/module/'+wx_locID+'?config='+wx_config+'&proto='+document.location.protocol+'&target='+wx_targetDiv+'"></scr'+'ipt>'); 
</script>
</td>
<td>
<div id="wx_module_7259">
   <a href="http://www.weather.com/weather/local/USCO0165">Granby Weather Forecast, CO</a>
</div>

<script type="text/javascript">
   var wx_locID = 'USCO0165';
   var wx_targetDiv = 'wx_module_7259';
   var wx_config='SZ=300x250*WX=FHW*LNK=SSNL*UNT=F*BGI=snow*MAP=CSC|null*DN=swam.us*TIER=0*PID=1040996069*MD5=8ddaa122b451d5a43103860b5fd2c4f1';

   document.write('<scr'+'ipt src="'+document.location.protocol+'//wow.weather.com/weather/wow/module/'+wx_locID+'?config='+wx_config+'&proto='+document.location.protocol+'&target='+wx_targetDiv+'"></scr'+'ipt>');  
</script>
</tr></td>
</table>
$footer
EOF;
?>
