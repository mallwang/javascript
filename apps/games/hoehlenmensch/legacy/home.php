		<?php	//include  ("functions.php");
				$id = id($_SESSION['nickname']);
				$abfrage_charakter = mysql_query("SELECT erfahrungspunkte, charakterpunkte, kraft, intelligenz, schnelligkeit, geschicklichkeit, ausdauer FROM charakter WHERE login_id = '$id'") or die ("Fehler! Charakter");
				$abfrage_nickname = mysql_query("SELECT nickname FROM login WHERE id = '$id'") or die ("Fehler!");
				$abfrage_rohstoffe = mysql_query("SELECT pflanzliche_nahrung, tierische_nahrung, wasser, holz, stein FROM rohstoffe WHERE login_id = '$id'") or die ("Fehler! Rohstoffe");
				$charakterwerte = mysql_fetch_array($abfrage_charakter);
				$nickname = mysql_fetch_array($abfrage_nickname);
				$rohstoffe = mysql_fetch_array($abfrage_rohstoffe);
		?>				
				Hallo <?php echo $nickname["nickname"]; ?> :)
				<br>
				<br>				
				<table>
				<tr>
				<td>
				<table border = "5" width = "180">
						Dein Charakter:
						<tr>
							<td width="120"> <?php echo "Erfahrung: " ?> </td>
							<td> <?php echo $charakterwerte["erfahrungspunkte"];?> </td>
						</tr>
						<tr>
							<td width="120"> <?php echo "Charakter: " ?> </td>
							<td> <?php echo $charakterwerte["charakterpunkte"];?> </td>
						</tr>
						<tr>
							<td width="120"> <?php echo "Kraft: " ?> </td>
							<td> <?php echo $charakterwerte["kraft"];?> </td>
						</tr>
						<tr>
							<td width="120"> <?php echo "Intelligenz: " ?> </td>
							<td> <?php echo $charakterwerte["intelligenz"];?> </td>
						</tr>
						<tr>
							<td width="120"> <?php echo "Schnelligkeit: " ?> </td>
							<td> <?php echo $charakterwerte["schnelligkeit"];?> </td>
						</tr>
						<tr>
							<td width="120"> <?php echo "Geschicklichkeit: " ?> </td>
							<td> <?php echo $charakterwerte["geschicklichkeit"];?> </td>
						</tr>
						<tr>
							<td width="120"> <?php echo "Ausdauer: " ?> </td>
							<td> <?php echo $charakterwerte["ausdauer"];?> </td>
						</tr>
				</table>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				<table border = "5" width = "250">
						Deine Rohstoffe:
						<tr>
							<td width="190"> <?php echo "Pflanzliche Nahrung: " ?> </td>
							<td> <?php echo $rohstoffe["pflanzliche_nahrung"];?> </td>
						</tr>
						<tr>
							<td width="190"> <?php echo "Tierische Nahrung: " ?> </td>
							<td> <?php echo $rohstoffe["tierische_nahrung"];?> </td>
						</tr>
						<tr>
							<td width="190"> <?php echo "Wasser: " ?> </td>
							<td> <?php echo $rohstoffe["wasser"];?> </td>
						</tr>
						<tr>
							<td width="190"> <?php echo "Holz: " ?> </td>
							<td> <?php echo $rohstoffe["holz"];?> </td>
						</tr>
						<tr>
							<td width="190"> <?php echo "Stein: " ?> </td>
							<td> <?php echo $rohstoffe["stein"];?> </td>
						</tr>
				</table>
				</td>
				</tr>
				</table>
				<br>
				<?php 	include ("status_header.php");?>
	</body>
</html>