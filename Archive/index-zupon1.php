<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->extra = <<<EOF
  <style type="text/css">
  #weather-nonmembers {
    width: 13em;
      border: 1px solid black;
        padding: 1em 1em 0 1em;
	  margin-top: 1em;
	    margin-left: 1em;
	      margin-right: auto;
	      }
	        </style>
EOF;

		$h->title = "Zupon Family Web Site ";


                $top =$S->getPageTop1($h);
//		$footer = $S->getFooter();

echo <<<EOF
		$top
		$greet
               <div align=center>
               <h2>Welcome to the Zupon's Family Web Site</h2>
               </div>
               <p align=center>
               <img SRC="zupon/pictures/p51mustang65.jpg" alt="p51mustang65.jpg">
               </p>
		<div class="center">
		<a  class="buttons1-5em blueButton" href="grandkids.php">
				  Pictures of the Family</a></div>\n
                <div class="center">
		  <a class="buttons1-5em blueButton" href="boss429.php">
                		    Pictures of BOSS429 Restoration</a>
								    </div>\n
	        <div class="center">
		    <a class="buttons1-5em redButton" href="zupon/brazilpic/index.htm">
	    			      Brazil Vacation Pictures</a>
									      </div>
<table width="100%" cellspacing="10"> 

<tr>
<td valign="top" align="center">
Here is the weather as reported by<br>

<div id="wx_module_4706">
   <a href="http://www.weather.com/weather/local/91320">Newbury Park Weather Forecast, CA (91320)</a>
   </div>

   <script type="text/javascript">

   var wx_config='SZ=300x250*WX=FHW*LNK=SSNL*UNT=F*BGI=boat*MAP=null|null*DN=swam.us*TIER=0*PID=1040996069*MD5=5f4b40cc5dae4abe19cfc02865d788f9';

      document.write('<scr'+'ipt src="'+document.location.protocol+'//wow.weather.com/weather/wow/module/'+wx_locID+'?config='+wx_config+'&proto='+document.location.protocol+'&target='+wx_targetDiv+'"></scr'+'ipt>');  
      </script>
      <!-- Begin weather.com magnet include -->
      <script src='http://voap.weather.com/weather/oap/91320?template=GENXH&par=1002177394&unit=0&key=da1fcbc31d528bba4c7e37d32ed7835e'></script>
      <!-- End weather.com magnet include -->

           $footer
EOF;
?>



														                                                        
                
