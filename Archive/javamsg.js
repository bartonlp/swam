
<!-- Ticker Tape with upcoming information -->

<script language="JavaScript">
//<!--
								 
	// global variables

var max = 0;

function textlist() {
	max = textlist.arguments.length;

	for(i=0; i < max; i++)
		this[i] = textlist.arguments[i];
}

tl = new textlist(
									"          Merry Christmas",
									"                    and",
									"A Very Happy New Year"
								 );

var x = 0; pos = 0;
var l = tl[0].length;

function textticker() {
	document.tickform.tickfield.value = tl[x].substring(0, pos)+"_";

	if(pos++ == l) {
		pos=0;

		setTimeout("textticker()",1000);
		x++;

		if(x==max)
			x=0;

		l = tl[x].length;
	} else
		setTimeout("textticker()", 50);
}
//	 -->
</script>



	 <!-- ******************************** -->
	 <!-- Ticker Tape with UPCOMING EVENTS -->
	 <!--
	 <br>
	 <table align="center" border=0>
																<tr> 
																<font style="color:red; font-family: Times; font-size:30pt">Upcoming Events</font>
																						</th>
																						</tr>

																						<tr>
																						<td>
																						<form name="tickform">
	<input style="color:red;font-family: Times; font-size:20pt" name="tickfield" size="21">
	</form>
	</td>
	</tr>
	</table>
	-->
