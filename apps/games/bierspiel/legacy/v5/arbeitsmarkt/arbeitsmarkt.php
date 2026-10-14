<?php $title=""; include '../huelle/header.php';

// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$firmenId = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");

?>
<script src="../js/ausklappen.js"></script>
<script src="arbeitsmarkt.js"></script>
<table class="bordered bg-color-red fg-color-white">
	<thead class="bc-color-gray fg-color-white" width="50%">
		<div align="center">
			<div align="center">
				<h2>Arbeitsmarkt</h2>
				Hier k&ouml;nnen Sie einen Arbeitnehmer Ihrer Wahl einstellen. Folgende Arbeitnehmer stehen zur Auswahl: <br>
			</div>
			<div align="center">
			- Ungelernte Arbeitskr&auml;fte<br>
			- Fachkr&auml;fte<br>
			- F&uuml;hrungskr&auml;fte<br><br>
			Jeder Arbeitnehmer hat seine St&auml;rke in einem unterschiedlichen Arbeitsgebiet.<br>
			Produktion (Brauen, Lager, Versand, Warenannahme)<br>
			Verwaltung (Vertrieb, Buchhaltung, Export, Einkauf) oder<br>
			Gastst&auml;tte (Ausschank, K&uuml;che, Gaststube)<br><br>
			</div>
		</div>
		<tr class="fg-color-white">
		<b>
		<th class="center fg-color-white">Name</th>
		<th class="center fg-color-white">Alter</th>
		<th class="center fg-color-white">Arbeitsgebiet</th>
		<th class="center fg-color-white">Talent</th>
		<th class="center fg-color-white">Grundwert</th>
		<th class="center fg-color-white">Gehalt</th>
		<th class="center fg-color-white">Aktion</th>
		</b>
		</tr>
	</thead>
	<tbody>
<?php

$anzahlDBEintraege = anfrage("SELECT COUNT(id) FROM arbeitnehmer WHERE `art`='2' OR `art`='3' AND `arbeitgeber` = '-1' ");

for ($i=0; $i<$anzahlDBEintraege[0]; $i++){

// Tabelleninhalt abfragen
$result = anfrage("SELECT `id`, `vorname`, `nachname`, `alter`, `grundwert`, `talent`, `gehalt`, `arbeitsgebiet`
FROM `arbeitnehmer` WHERE `art` IN ('2', '3') AND `arbeitgeber` = '-1' ORDER BY `grundwert` DESC LIMIT $i, 1 ");

// Abfragen der aktuellen Gebote des aktuellen Users und festsetzen des Mindestgebotes
$anzahlGebote = anfrage("SELECT COUNT(id) FROM gebote WHERE arbeitnehmer_id = '$result[0]' AND arbeitgeber_id = '$firmenId[0]' ");
$aktuellesGebot = anfrage("SELECT MAX(gebot) FROM gebote WHERE arbeitnehmer_id = '$result[0]' AND arbeitgeber_id = '$firmenId[0]'");
if($anzahlGebote[0]>=1){
	$gebote = "/ Ihre Gebote: $anzahlGebote[0]";
} else{
	$gebote = "";
}

if($aktuellesGebot[0]>=1){
	$aktGebot = "/ Aktuell: $aktuellesGebot[0]&euro;";
	$gebot = $aktuellesGebot[0]+1;
} else{
	$aktGebot = "";
	$gebot = $result[6]+1;
}

// Festlegen des Arbeitsgebietes
switch ($result[7]){
	case 1: $arbeitsgebiet = "Verwaltung"; break;
	case 2: $arbeitsgebiet = "Produktion"; break;
	case 3: $arbeitsgebiet = "Gastst&auml;tte"; break;
}

// Arrayinhalt in einer Tabelle ausgeben
	echo "<tr>
		<td>$result[1] $result[2]</td>
		<td>$result[3]</td>
		<td>$arbeitsgebiet</td>
		<td>$result[5]</td>
		<td>$result[4]</td>
		<td>$result[6]&euro;</td>
		<td>	
			<ausklapp><a href='#'>verhandeln</a> $gebote $aktGebot</ausklapp>
			<form method='POST' id='bieten$i' class='checkme' action='' style='display: none;'>
				<div class='angebotUebermittelt' style='display: none;' name='verhandlungsstatus$i' id='verhandlungsstatus$i'><b>Ihr Angebot wurde &uuml;bermittelt</b></div>
				<div class='bereitsGeboten' style='display: none;' name='bereitsGeboten$i' id='bereitsGeboten$i'><b>Ihr bisheriges Gebot ist h&ouml;her!</b></div>
				Wieviel Gehalt moechten Sie diesem Arbeitnehmer bieten?<br>
				<input name='myid' type='hidden' value='{incremented value}'>
				<input name='id' style='display: none;' value='$result[0]' type='text'>
				<input name='gehalt' class='span4_marg' placeholder='Gehalt' id='gehalt$i' type='text' size='5' maxlength='5' value='$gebot'>
				<div class='margin_top' align='center'>
	                   <button class='bg-color-grayDark fg-color-white' name='submit' type='submit'>Angebot absenden</button>
	            </div>
			</form>
		</td>
	</tr>";
}

echo "</tbody><tfoot></tfoot></table>";

include '../huelle/footer.php';?>
