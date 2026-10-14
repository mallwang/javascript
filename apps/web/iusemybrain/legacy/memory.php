<?php $title="Memory"; include 'header.php';?>

<div style="margin-top: 70px;">
	<div align="center">
  	<h2>Ein Memory-Spiel zum Warmwerden...</h2><br><br>	
	</div>
		<script type="text/javascript" src="js/memory.js"></script>
	<table align="center">
		<tr>
		<br><br>
			<td>
				<table align="center">
					<form name="game">
						<tr>
							<td><b>Punkte</b></td>
							<td><b>Züge</b></td>
							<td><b>Fehler</b></td>
						</tr>
						<tr>
							<td><input disabled="disabled" class="input-small" type="text" name="punkt_aus" value="0"></td>
							<td><input disabled="disabled" class="input-small" type="text" name="geszug_aus" value="0"></td>
							<td><input disabled="disabled" class="input-small" type="text" name="falsch_aus" value="0"></td>
						</tr>
					</form>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td>
			<a href="memory.php"><b>neues Spiel</b></a>
			</td>
		</tr>
	</table>
</div>

<?php include 'footer.php';?>