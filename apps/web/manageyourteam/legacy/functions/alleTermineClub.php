<?php require_once '../inc/DB.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Alle Termine zwischen Start- und End-Timestamp abfragen
$clubID = mysql_real_escape_string($_GET["clubID"]);
$startTs = mysql_real_escape_string($_GET["start"]);
$endTs = mysql_real_escape_string($_GET["end"]);
if (isset($_GET["teamID"]))
	$teamID = mysql_real_escape_string($_GET["teamID"]);
if (isset($_GET["appointmentType"]))
	$appointmentType = mysql_real_escape_string($_GET["appointmentType"]);
// Datum aus Timestamp erhalten
$startDate = date('Y-m-d', $startTs);
$endDate = date('Y-m-d', $endTs);

// Variablen deklarieren
$str_teamID = "";
$str_appointmentType = "";

if (isset($_GET["teamID"])) {
	// Wenn alle Teams angezeigt werden sollen
	if($teamID != 0)
		$str_teamID = " AND `teamID` = '$teamID' ";
}
if (isset($_GET["appointmentType"])) {
	// Wenn Alle Termine abgefragt werden sollen
	if ($appointmentType != 0)
		$str_appointmentType = " AND `eventType` = '$appointmentType' ";
}
// DB-Anfrage
$appointmentResult = mysql_query("SELECT * FROM `eventtab` WHERE `clubID` = '$clubID' " . $str_teamID . " " . $str_appointmentType . " AND `eventStartDate` >= '$startDate' AND `eventEndDate` <= '$endDate'");
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
		
		// Titel und Farben anhand des Typs festlegen
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
		
		// Wenn alle Teams abgefragt werden -> zu Termin Mannschaftsbezeichnung und -typ anzeigen
		if (isset($_GET["teamID"]) && $teamID == 0){
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
else{
	echo "Kein Result vorhanden";
}

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