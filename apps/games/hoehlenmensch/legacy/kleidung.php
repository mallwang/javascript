<?php $title="Werkzeugen"; include ("header.php");?>

<b>Welches Kleidungsstück möchtest du herstellen?</b>
<br><br>
<form name='Verarbeitung' action='produktion.php' method='get'>
	<table border="1" cellpadding="10">
	<tr>
		<td><input type="radio" name="name" value="lendenschurz" checked="checked"> Lendenschurz </td>
		<td>Gesamtzeit: <b>1 Stunde</b><br><br>
			Mit einer Axt geht dir das Holz fällen leichter von der Hand,<br>
			du kannst nun ausgewählte Bäume fällen und bist nicht mehr<br>
			auf das Sammeln von Holz im Wald angewiesen.<br><br>
			<b>Vorteil: </b>Erhöhung sämtlichen Ertrages um 1 je 30 Minuten<br>			
		</td>
		<td><b>4 Arbeitsschritte (je 15 Minuten):</b><br>
			1. Stock schnitzen<br>
			2. geeigneten Stein suchen & bearbeiten/<br>
			3. Liane suchen<br>
			4. zusammenbinden
		</td>
	</tr>
	<tr>
		<td><input type="radio" name="name" value="schuhe"> Schuhe </td>
		<td>Gesamtzeit: <b>1 Stunde</b><br><br>
			Mit einem Jagdspeer bist du nicht mehr lediglich auf<br>
			kleine Tiere angewiesen, sondern kannst nun auch größere<br>
			Lebewesen fangen. Er ermöglicht dir eine erhöhte Reichweite<br>
			sowie eine größere Durchschlagskraft.<br><br>
			<b>Vorteil: </b>Erhöhung sämtlichen Ertrages um 1 je 30 Minuten<br>
		</td>
		<td><b>4 Arbeitsschritte (je 15 Minuten):</b><br>
			1. geeigneten Stock suchen<br>
			2. geeigneten Stein suchen & bearbeiten/<br>
			3. Harz suchen<br>
			4. zusammenbauen
		</td>
	</tr>
	<tr>
		<td><input name="submit" type="submit"><?php echo "<input type='hidden' name='Arbeit' value=$nr>" ?></td>
	</tr>
	</table>
</form>
		
<?php include ("footer.php"); ?>