<?php $title="Werkzeuge"; include ("header.php");?>

<b>Welches Werkzeug möchtest du herstellen?</b>
<br><br>
<?php //$nr=(integer)$_GET["Arbeit"]; ?>
<form name='Verarbeitung' action='produktion.php' method='get'>
	<table border="1" cellpadding="10">
	<tr>
		<td><input type="radio" name="name" value="axt" checked="checked"> Axt </td>
		<td>Gesamtzeit: <b>1 Stunde</b><br><br>
			Mit einer Axt geht dir das Holz fällen leichter von der Hand,<br>
			du kannst nun ausgewählte Bäume fällen und bist nicht mehr<br>
			auf das Sammeln von Holz im Wald angewiesen.<br><br>
			<b>Vorteil: </b>Erhöhung des Ertrages um 1-2 / 30 Minuten<br>			
		</td>
		<td><b>4 Arbeitsschritte (je 15 Minuten):</b><br>
			1. Stock schnitzen<br>
			2. geeigneten Stein suchen & bearbeiten/<br>
			3. Liane suchen<br>
			4. zusammenbinden
		</td>
	</tr>
	<tr>
		<td><input type="radio" name="name" value="jagdspeer"> Jagdspeer </td>
		<td>Gesamtzeit: <b>1 Stunde</b><br><br>
			Mit einem Jagdspeer bist du nicht mehr lediglich auf<br>
			kleine Tiere angewiesen, sondern kannst nun auch größere<br>
			Lebewesen fangen. Er ermöglicht dir eine erhöhte Reichweite<br>
			sowie eine größere Durchschlagskraft.<br><br>
			<b>Vorteil: </b>Erhöhung des Ertrages um 1-2 / 30 Minuten<br>
		</td>
		<td><b>4 Arbeitsschritte (je 15 Minuten):</b><br>
			1. geeigneten Stock suchen<br>
			2. geeigneten Stein suchen & bearbeiten/<br>
			3. Harz suchen<br>
			4. zusammenbauen
		</td>
	</tr>
	<tr>
		<td><input type="radio" name="name" value="wasserbeutel"> Wasserbeutel </td>
		<td>Gesamtzeit: <b>1 Stunde</b><br><br>
			Ein Wasserbeutel hilft dir bei dem Transport von Wasser.<br>
			Durch ihn bist du erstmals in der Lage mehrere Liter von<br>
			diesem kostbaren Gut nach Hause zu schaffen ohne Unterwegs<br>
			einiges davon zu verlieren.<br><br>
			<b>Vorteil: </b>Erhöhung des Ertrages um 1-2 / 30 Minuten<br>
		</td>
		<td><b>2 Arbeitsschritte (je 30 Minuten):</b><br>
			1. geeignetes Tier jagen und ausnehmen<br>
			2. Magen gerben
		</td>
	</tr>
	<tr>
		<td><input type="radio" name="name" value="korb"> Korb </td>
		<td>Gesamtzeit: <b>1 Stunde</b><br><br>
			Um ein effektives Sammeln von Früchten oder Pilzen zu ermöglichen,<br>
			ist ein Korb von sehr hoher Bedeutung.<br><br>
			<b>Vorteil: </b>Erhöhung des Ertrages um 1-2 / 30 Minuten<br>
		</td>
		<td><b>2 Arbeitsschritte (je 30 Minuten):</b><br>
			1. Weide suchen<br>
			2. Korb flechten
		</td>
	</tr>
	<tr>
		<td><input name="submit" type="submit"></td>
	</tr>
	</table>
</form>
<?php include ("footer.php"); ?>