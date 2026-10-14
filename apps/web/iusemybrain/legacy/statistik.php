<?php $title="Statistik"; include 'header.php';?>
<div class="statistik">
	<table>
		<tr> 
			<td>
				<b><img src="img/counter.gif" width="16" height="16"/> Besucherstatistik</b>
			</td>
		</tr>
		<tr> 
			<td> 
				<font face="Arial, Helvetica, sans-serif" size="1">
					<pre>Online	&raquo;	<? echo $online; ?> <br>Heute	&raquo;	<? echo $day; ?> <br>Woche	&raquo;	<? echo $week; ?> <br>Monat	&raquo;	<? echo $month; ?><br>Jahr	&raquo	<? echo $year; ?><br>Gesamt	&raquo;	<? echo $all; ?><br><br>Rekord	&raquo;	<? echo $record; ?><br>(<? echo date("d.m.Y", $record_time) ?>)</pre>
				</font>
				<div align="center">
	      			<font style="font-size:9px;" face="Arial, Helvetica, sans-serif">(<a href="http://www.vonderborn.com/php_counter_script.php" target="_blank">PHP Counter</a>)</font>
	      		</div>
	    	</td>
	  	</tr>
	</table>
</div>
<?php include 'footer.php';?>	