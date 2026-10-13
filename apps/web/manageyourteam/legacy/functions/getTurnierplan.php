<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamID = mysql_real_escape_string($_POST['teamID']);
// Rückgabevariable
$return = "";
// Alle Mannschaften abfragen
$allTeams = $db->getTeamByID($teamID);
// Wenn Mannschaften existieren
if ($allTeams != false){
	$con = $db->getConnection();
	// DB Abfrage nach der eventID im gewählten Zeitraum
	$allEventData = mysql_query("SELECT `eventID`, `eventStartDate`, `eventStartTime`, `eventEndDate`, `eventEndTime`, `eventInfo` FROM `eventtab` WHERE `teamID` = '$teamID' AND `eventType`= '3' ORDER BY `eventStartDate`");
	// Admin und Trainer können Aktionen durchführen
	if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1)
		// Anfang der Tabelle erstellen
		$return .= 	'<div class="turnier"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Datum</th><th>Turnier</th><th>Gegner</th><th>Ergebnis</th><th>Aktionen</th></tr></thead><tbody>';
	// Spieler können keine Aktionen durchführen
	else if ($_SESSION['rights'] == 2)
		// Anfang der Tabelle erstellen
		$return .= 	'<div><table id="myTable" class="display" cellspacing="0" width="100%"><thead></thead><tfoot></tfoot><tbody>';
	// Zählvariable erstellen
	$i = 0;
	// Inhalt der Tabelle erstellen
	while($tournamentRow = mysql_fetch_array($allEventData)){
		// Datum erstellen
		$date1_tmp = date_create($tournamentRow[1]);
		$date2_tmp = date_create($tournamentRow[2]);
		$startdatum = "" . date_format($date1_tmp, 'd.m.Y') . " " . date_format($date2_tmp, 'H:i');
		$date3_tmp = date_create($tournamentRow[3]);
		$date4_tmp = date_create($tournamentRow[4]);
		$enddatum = "" . date_format($date3_tmp, 'd.m.Y') . " " . date_format($date4_tmp, 'H:i');
		$return .= 	'<tr><td><b>Start: ' . $startdatum . '<br>Ende: ' . $enddatum . '</b></td><td><b>' . $tournamentRow[5] . '</b></td><td></td><td></td>';
		// Admin und Trainer können Aktionen durchführen
		if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1)
			$return .= '<td><button onclick="createDialogCreateNewMatch(' . $tournamentRow[0] . ')" style="background-color: transparent;"><i title="Neues Spiel erstellen" class="icon-new"></i></button><button onclick="editTournament(' . $tournamentRow[0] . ', &quot;' . $startdatum . '&quot;, &quot;' . $tournamentRow[5] . '&quot;)" style="background-color: transparent;"><i title="Turnier bearbeiten" class="icon-pencil"></i></button><button onclick="deleteTournament(' . $tournamentRow[0] . ', 3)" style="background-color: transparent;"><i title="Turnier löschen" class="icon-remove"></i></button></td></tr>';
		// Spieler können keine Aktionen durchführen
		else if ($_SESSION['rights'] == 2)
			$return .= '</tr>';
		// Für jedes Turnier die entsprechenden Spiele abfragen
		$tournamentData = mysql_query("SELECT `teamTournamentID`, `matchStartDate`, `matchStartTime`, `opponent`, `teamGoals`, `opponentGoals`, `opponent` FROM `teamtournamenttab` WHERE `eventID` = '$tournamentRow[0]'");
		$j = 0;
		// Schleifendurchlauf für alle Spezifischen Spiele
		while($matchRow = mysql_fetch_array($tournamentData)){
			// Datum erstellen
			$date1_tmp = date_create($matchRow[1]);
			$date2_tmp = date_create($matchRow[2]);
			$startdatum = "" . date_format($date1_tmp, 'd.m.Y') . " " . date_format($date2_tmp, 'H:i');
			// Ergebnis berechnen
			if ($matchRow[4] == -1)
				$str_result = "offen";
			else
				$str_result = $matchRow[4] . ':' . $matchRow[5];
			// Für jeden Eintrag ein Optionsfeld erstellen
			$return .= 	'<tr><td>' . $startdatum . '</td><td></td><td>' . $matchRow[3] . '</td><td>' . $str_result . '</td>';
			// Admin und Trainer können Aktionen durchführen
			if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1)
				$return .= '<td><button onclick="setResult(' . $matchRow[0] . ', &quot;' . $startdatum . '&quot;, &quot;' . $matchRow[3] . '&quot;)" style="background-color: transparent;"><i title="Ergebnis eintragen" class="icon-earth"></i></button><button onclick="editMatch(' . $matchRow[0] . ', &quot;' . $startdatum . '&quot;, &quot;' . $matchRow[3] . '&quot;)" style="background-color: transparent;"><i title="Spiel bearbeiten" class="icon-pencil"></i></button><button onclick="deleteMatch(' . $matchRow[0] . ')" style="background-color: transparent;"><i title="Spiel löschen" class="icon-remove"></i></button></td></tr>';
			// Spieler können keine Aktionen durchführen
			else if ($_SESSION['rights'] == 2)
				$return .= '</tr>';
			$j++;
		}
		// Wenn kein Ergebnis gefunden wurde
		if ($j == 0)
			$return .= '<tr><td>Es existieren keine Spiele dieses Turniers.</td><td></td><td></td><td></td><td></td></tr>';
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