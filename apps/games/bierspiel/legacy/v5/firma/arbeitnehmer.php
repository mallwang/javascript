<?php $title=""; include '../huelle/header.php';

// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$id = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");
// echo $id[0];
// Anzahl Arbeitnehmer abfragen
$anzahlDBEintraege = anfrage("SELECT COUNT(id) FROM `arbeitnehmer` WHERE `arbeitgeber` = '$id[0]' ");
// echo $anzahlDBEintraege[0];

// Wenn keine eigene Arbeitnehmer vorhanden
if ($anzahlDBEintraege[0] == 0){
	echo "In ihrem Unternehmen sind noch keine Arbeitskr&auml;fte besch&auml;ftigt!<br>
		Bitte suchen Sie am <a href='../arbeitsmarkt/arbeitsmarkt.php'>Arbeitsmarkt</a> nach geeigneten Kanidaten.";
}// end if
// Wenn bereits Arbeitnehmer eingestellt
else{
	?>
	<script src="../js/ausklappen.js"></script>
	<script src="arbeitnehmer.js"></script>
	<table class="bordered bg-color-red fg-color-white">
		<thead class="bc-color-gray fg-color-white" width="50%">
			<div align="center"><h2>Eigene Arbeitnehmer</h2></div>
			<tr class="fg-color-white">
				<b>
				<th class="center fg-color-white">Name</th>
				<th class="center fg-color-white">Art</th>
				<th class="center fg-color-white">Arbeitsgebiet</th>
				<th class="center fg-color-white">Alter</th>
				<th class="center fg-color-white">Talent</th>
				<th class="center fg-color-white">Grundwert</th>
				<th class="center fg-color-white">Zufriedenheit</th>
				<th class="center fg-color-white">Arbeitswert</th>
				<th class="center fg-color-white">Gehalt</th>
				<th class="center fg-color-white">Aktion</th>
				</b>
			</tr>
		</thead>
		<tbody>
	<?php
	for ($i=0; $i<$anzahlDBEintraege[0]; $i++){
	// Tabelleninhalt abfragen
	$result = anfrage("SELECT `id`, `art`, `vorname`, `nachname`, `alter`, `talent`, `grundwert`, `zufriedenheit`, `arbeitswert`,  `gehalt`, `arbeitsgebiet`
	FROM `arbeitnehmer` WHERE `arbeitgeber` = '$id[0]' ORDER BY `arbeitswert` DESC LIMIT $i, 1");
	// Art festlegen
	switch ($result[1]){
		case 0:	$art = "Azubi"; break;
		case 1:	$art = "Hilfskraft"; break;
		case 2:	$art = "Fachkraft"; break;
		case 3:	$art = "F%uuml;hrungskraft"; break;
	}
	// Festlegen des Arbeitsgebietes
	switch ($result[10]){
		case 1: $arbeitsgebiet = "Verwaltung"; break;
		case 2: $arbeitsgebiet = "Produktion"; break;
		case 3: $arbeitsgebiet = "Gastst&auml;tte"; break;
	}

	// Tabelleninhalt in einer Tabelle ausgeben
	// [0]=Id, [1]=Art, [2]=Vorname, [3]=Nachname, [4]=Alter, [5]=Talent, [6]=Grundwert, [7]=Zufriedenheit, [8]=Arbeitswert, [9]=Gehalt, 
	echo "<tr>
		<td>$result[2] $result[3]</td>
		<td>$art</td>
		<td>$arbeitsgebiet</td>
		<td>$result[4]</td>
		<td>$result[5]</td>
		<td>$result[6]</td>
		<td>$result[7]</td>
		<td>$result[8]</td>
		<td>$result[9]&euro;</td>
		<td>
			<a href='' class='fortbildung'>Fortbildung</a> / 
			
			<a href='' class='entlassung'>Entlassung
				<input name='id' style='display: none;' value='$result[0]' type='text'>
				<input name='name' style='display: none;' value='$result[2] $result[3]' type='text'>
				<input name='gehalt' style='display: none;' value='$result[9]' type='text'>
				<input name='grundwert' style='display: none;' value='$result[6]' type='text'>
			</a> / 
			
			<ausklapp><a href='#'>Gehalt anpassen</a></ausklapp>
			<form method='POST' class='gehaltserhoehung' action='' style='display: none;'>
				Wie hoch soll das neue Gehalt sein?<br>
				<input style='display: none;' name='id' value='$result[0]' type='text'>
				<input style='display: none;' name='name' value='$result[2] $result[3]'>
				<input style='display: none;' name='grundwert' value='$result[6]'>
				<input name='gehalt' class='span4_marg' placeholder='Gehalt' id='gehalt$i' type='text' size='5' maxlength='5' value='$result[9]'>
				<div class='margin_top' align='center'>
	                   <button class='bg-color-grayDark fg-color-white' name='submit' type='submit'>Angebot absenden</button>
	            </div>
			</form>
		</td>
	</tr>";	
	}// end for
echo "</tbody><tfoot></tfoot></table>";
}// end else


include '../huelle/footer.php';?>