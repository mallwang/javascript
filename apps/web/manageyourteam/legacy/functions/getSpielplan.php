<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamID = mysql_real_escape_string($_POST['teamID']);
$return = "";
// Alle Mannschaften abfragen
$allTeams = $db->getTeamByID($teamID);
// print_r($allTeams);
// Wenn Mannschaften existieren
if ($allTeams != false){
	$con = $db->getConnection();
	// DB Abfrage nach der eventID im gewählten Zeitraum
	$allEventData = mysql_query("SELECT `eventID`, `eventStartDate`, `eventStartTime`, `eventInfo` FROM `eventtab` WHERE `teamID` = '$teamID' AND `eventType`= '2' ORDER BY `eventStartDate`");
	// Admin und Trainer können Aktionen durchführen
	if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1)
		// Anfang der Tabelle erstellen
		$return .= 	'<div class="spielplan"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Datum</th><th>Gegner</th><th>Ergebnis</th><th>Aktionen</th></tr></thead><tbody>';
	// Spieler können keine Aktionen durchführen
	else if ($_SESSION['rights'] == 2)
		// Anfang der Tabelle erstellen
		$return .= 	'<div><table id="myTable" class="display" cellspacing="0" width="100%"><thead></thead><tfoot></tfoot><tbody>';
	// Zählvariable erstellen
	$i = 0;
	// Inhalt der Tabelle erstellen
	while($matchRow=mysql_fetch_array($allEventData)){
		$date1_tmp = date_create($matchRow[1]);
		$date2_tmp = date_create($matchRow[2]);
		$startdatum = "" . date_format($date1_tmp, 'd.m.Y') . " " . date_format($date2_tmp, 'H:i');
		// DB Abfrage zu jedem event die entsprechenden spezifischen Daten (Gegner, Ergebnis)
		$eventDataTab = mysql_query("SELECT `teamGoals`, `opponentGoals`, `opponent`, `location` FROM `teammatchtab` WHERE `eventID` = '$matchRow[0]'");
		$eventData = mysql_fetch_array($eventDataTab, MYSQL_NUM);
		// Ergebnis berechnen
		if ($eventData[0] == -1)
			$str_result = "offen";
		else
			$str_result = $eventData[0] . ':' . $eventData[1];
		// Für jeden Eintrag ein Optionsfeld erstellen
		$return .= 	'<tr><td>' . $startdatum . '</td><td>' . $matchRow[3] . ' (' . $eventData[3] . ')</td><td>' . $str_result . '</td>';
		// Admin und Trainer können Aktionen durchführen
		if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1)
			$return .= '<td><button onclick="setResult(' . $matchRow[0] . ', &quot;' . $startdatum . '&quot;, &quot;' . $eventData[2] . '&quot;)" style="background-color: transparent;"><i title="Ergebnis eintragen" class="icon-earth"></i></button><button onclick="editMatch(' . $matchRow[0] . ', &quot;' . $startdatum . '&quot;, &quot;' . $eventData[2] . '&quot;)" style="background-color: transparent;"><i title="Spiel bearbeiten" class="icon-pencil"></i></button><button onclick="deleteMatch(' . $matchRow[0] . ')" style="background-color: transparent;"><i title="Spiel löschen" class="icon-remove"></i></button></td></tr>';
		// Spieler können keine Aktionen durchführen
		else if ($_SESSION['rights'] == 2)
			$return .= '</tr>';
		$i++;
	}
	// Ende der Tabelle erstellen
	$return .= '</tbody></table></div>';
	// Wenn kein Turniertermin gefunden wurde
	if ($i == 0)
		$return = 'keineTermine';
}
echo $return;

// Funktion zum Ersetzen der teamTypeID durch den teamType
function convertTeamTypeID($teamTypeID){
	$teamType = "";
	// TeamID durch Namen ersetzen
	switch ($teamTypeID) {
		case 1:
			$teamType = "1. Mannschaft";
			break;
		case 2:
			$teamType = "2. Mannschaft";
			break;
		case 3:
			$teamType = "A-Junioren";
			break;
		case 4:
			$teamType = "B-Junioren";
			break;
		case 5:
			$teamType = "C-Junioren";
			break;
		case 6:
			$teamType = "D-Junioren";
			break;
		case 7:
			$teamType = "E-Junioren";
			break;
		case 8:
			$teamType = "F-Junioren";
			break;
		case 9:
			$teamType = "Hobby";
			break;
		default:
			$teamType = "Fehler";
			break;
	}
	return $teamType;
}