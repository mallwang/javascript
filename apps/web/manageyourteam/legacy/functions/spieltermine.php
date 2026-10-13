<?php session_start();
require_once '../inc/DB.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();

// Daten übernehmen
$startTs = mysql_real_escape_string($_GET["start"]);
$endTs = mysql_real_escape_string($_GET["end"]);
// Datum aus Timestamp erhalten
$startDate = date('Y-m-d', $startTs);
$endDate = date('Y-m-d', $endTs);
// Alle Termine anhand den Rechten abfragen
// Admin
if ($_SESSION['rights'] == 0)
	$appointmentResult = mysql_query("SELECT * FROM `eventtab` WHERE `eventType` = 2 AND `clubID` = '" . $_SESSION['clubID'] . "' AND `eventStartDate` >= '$startDate' AND `eventEndDate` <= '$endDate'");
// Trainer
else if ($_SESSION['rights'] == 1){
	// Zugehöriges Team abfragen
	$allCoaches = $db->getCoachByID($_SESSION['coachID']);
	// Wenn ein Team zugewiesen ist
	if(isset($allCoaches['team'][0]	)){
		// Mannschaft zuweisen
		$teamID = $allCoaches['team'][0];
		$appointmentResult = mysql_query("SELECT * FROM `eventtab` WHERE `eventType` = 2 AND `teamID` = '" . $teamID . "' AND `eventStartDate` >= '$startDate' AND `eventEndDate` <= '$endDate'");
	}
	// Wenn kein Team zugewiesen ist
	else
		$appointmentResult = false;
}
// Spieler
else if ($_SESSION['rights'] == 2)
	$appointmentResult = mysql_query("SELECT * FROM `eventtab` WHERE `eventType` = 2 AND `teamID` = '" . $_SESSION['teamID'] . "' AND `eventStartDate` >= '$startDate' AND `eventEndDate` <= '$endDate'");

// Wenn ein Result vorhanden ist
if ($appointmentResult != false){
	// Array erstellen
	$events = array();
	$eventCount = 0;
	// Schleifendurchlauf durch alle Ergebnisse
	while($appointmentRow=mysql_fetch_array($appointmentResult)){
		$event = '';
		$eventTeamID = $appointmentRow['teamID'];
		$eventID = $appointmentRow['eventID'];
		$eventType = $appointmentRow['eventType'];
		$eventInfo = $appointmentRow['eventInfo'];
		$location = $appointmentRow['location'];
		$eventStartDate = $appointmentRow['eventStartDate'];
		$eventStartTime = $appointmentRow['eventStartTime'];
		$eventEndDate = $appointmentRow['eventEndDate'];
		$eventEndTime = $appointmentRow['eventEndTime'];
		$start_date = $eventStartDate . "T" . $eventStartTime;
		$end_date = $eventEndDate . "T" . $eventEndTime;
		// Title festlegen
		if($eventType == 1){
			$title = "Training";
			$color = "#08C700";
		}
		else if($eventType == 2){
			$eventInfo .= " (" . $location . ")";
			$title = "Spiel";
			$color = "#cc0000";
		}
		else if($eventType == 3){
			$title = "Turnier";
			$color = "#fa6800";
		}
		
		// Ein Admin sieht alle Termine -> muss unterscheiden können
		if ($_SESSION['rights'] == 0){
			$team = $db->getTeamByID($eventTeamID);
			// Wenn Mannschaften existieren
			if ($team != false){
				$teamType = convertTeamTypeID($team['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($team['teamName'] != -1)
					$teamBez = $teamType . " (" . $team['teamName'] . ")";
				else 
					$teamBez = $teamType;
				if ($eventInfo == "")
					$eventInfo .= "<i>" . $teamBez . "</i>";
				else
					$eventInfo .= "<br><i>" . $teamBez . "</i>";
			}
		}
		$event = array("allDay" => false, "type" => $eventType, "title" => $title, "start" => $start_date, "end" => $end_date, "color" => $color, "id" => $eventID, "info" => $eventInfo, "location" => $location);
		$events[$eventCount]=$event;
		$eventCount++;
	}
	echo json_encode($events);
}
// Wenn kein Result vorhanden ist
else
	echo "Kein Result vorhanden";

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
?>