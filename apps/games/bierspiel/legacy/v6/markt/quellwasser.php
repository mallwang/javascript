<!-- Hinweise -->
<div class="support">
	<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
	<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
</div>
<?php error_reporting(0); session_start(); include '../funktionen.php';
// Abfrage der Tabelle 'quellwasser'
$ergebnis = mysql_query("SELECT * FROM quellwasser ORDER BY preis, qualitaet DESC");
// Tabellenanfang
echo "<table class='table table-condensed'>";
// Tabellenkopf
echo "
	<thead>
		<tr>
			<th style='width: 30px;'>ID</th>
			<th style='width: 200px;'>Menge</th>
			<th style='width: 30px;'>Qualit&auml;t</th>
			<th style='width: 40px;'>Preis</th>
			<th style='width: 150px;'>Kaufen</th>
		</tr>
	</thead>
";
// Tabellenbody anfang
echo "<tbody>";
// Zählvariable
$i = 1;
$firmaId = $_SESSION["firmaId"];
while($row = mysql_fetch_object($ergebnis)){
	// Wenn Angebot von aktuellen User -> nicht anzeigen
	if ($row->anbieter_id == $firmaId){
		// Nichts anzeigen
	}
	// Wenn Angebot von anderen User -> anzeigen
	else{
		// Reihenanfang
		echo "<tr>";
		// Gesamte Spalte
		echo "<td>" . $i . "</td>";
		//echo "<td>" . $row->anbieter_id . "</td>";
		echo "<td id='menge$row->id'>" . $row->menge . "</td>";
		echo "<td id='qualitaet$row->id'>" . $row->qualitaet . "</td>";
		echo "<td id='preis$row->id'>" . $row->preis . "</td>";
		// Inputfeld zum Kaufen
		echo "	<td>
					<div class='input-append'>
						<input onkeyup='pruefeMenge(this.id, this.value, $row->menge)' placeholder='Menge' style='height: 20px;' class='span1-2' id='$row->id' type='text'>
						<button disabled='disabled' onclick='kaufen(this.id, $row->preis, \"quellwasser\", $row->menge)' id='btn$row->id' class='btn' type='button' title='Kaufen'><i class='icon-shopping-cart'></i></button>
						<img style='display: none;' id='wait$row->id' src='/img/loading.gif'/>
					</div>
				</td>";
		// Reihenende
		echo "</tr>";
		// Variable erhöhen
		$i++;
	}
}
// Tabellenbody ende
echo "</tbody>";
// Tabellenende
echo "</table>";
?>
<script type="text/javascript">
// Funktion zum Mengenüberprüfen
function pruefeMenge(element, wert, menge){
	// Wenn mindestens ein Zeichen eingegeben wurde
	if (wert.length != 0){
		// Menge abfragen
		//var menge = document.getElementById("menge" + element).value;
		// Prüfen ob Zahlen im Bereich von null bis Menge eingegeben wurden
		if (wert>0 && wert <= menge){
			// Feld grün hinterlegen
			document.getElementById(element).setAttribute('style', 'border-color: green; background-color: palegreen; height: 20px;');
			// Button aktivieren
			document.getElementById("btn" + element).removeAttribute('disabled');
		}
		// Wenn Zahl ausser dem Bereich ist
		else{
			// Feld rot hinterlegen
			document.getElementById(element).setAttribute('style', 'border-color: red; background-color: salmon; height: 20px;');
			// Button deaktivieren
			document.getElementById("btn" + element).setAttribute('disabled', 'disabled');
		}
	}
	// Wenn kein Zeichen eingegeben wurde
	else{
		document.getElementById(element).setAttribute('style', 'border-color: rgb(204,204,204); background-color: white; height: 20px;');
		// Button deaktivieren
		document.getElementById("btn" + element).setAttribute('disabled', 'disabled');
	}
}
</script>