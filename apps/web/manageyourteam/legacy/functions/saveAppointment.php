<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
$teamID = mysql_real_escape_string($_POST['teamID']);
$appointmentType = mysql_real_escape_string($_POST['appointmentType']);
$info = mysql_real_escape_string($_POST['info']);
$location = mysql_real_escape_string($_POST['location']);
$startdatum = mysql_real_escape_string($_POST['startdatum']);
$enddatum = mysql_real_escape_string($_POST['enddatum']);
$startuhrzeit = mysql_real_escape_string($_POST['startuhrzeit']);
$enduhrzeit = mysql_real_escape_string($_POST['enduhrzeit']);
// Termin in DB updaten
$updateEvent = mysql_query("UPDATE `eventtab` SET `teamID` = '$teamID', `eventType` = '$appointmentType', `eventInfo` = '$info', `eventStartDate` = '$startdatum', `eventEndDate` = '$enddatum', `eventStartTime` = '$startuhrzeit', `eventEndTime` = '$enduhrzeit', `location` = '$location' WHERE `eventID` = '$eventID'");
// Wenn ein Turnier zu einem anderen Typ geändert wird
if (isset($_POST['typeOld'])){
	// Wenn ein Turnier zu einem anderen Typ geändert wird
	if (mysql_real_escape_string($_POST['typeOld']) != $appointmentType){
		// Alle Turnierspiele löschen
		$deleteMatch = mysql_query("DELETE FROM `teamtournamenttab` WHERE `eventID` = '$eventID'");
		// Wenn es ein Spiel ist
		if ($appointmentType == 2){
			// Eintrag in teammatchtab eintragen
			$updateTeamMatch = mysql_query("INSERT INTO `teammatchtab` (`teamID`, `eventID`, `opponent`, `location`) VALUES ('$teamID', '$eventID', '$info', '$location')");
		}
		else
			$updateTeamMatch = true;
	}
	// Wenn Daten des Turniers geändert werden
	else
		$updateTeamMatch = true;
}
else{
	// Wenn es ein Spiel ist
	if ($appointmentType == 2)
		// Spiel updaten
		$updateTeamMatch = mysql_query("UPDATE `teammatchtab` SET `opponent` = '$info', `location` = '$location' WHERE `eventID` = '$eventID'");
	else
		$updateTeamMatch = true;
}
if ($updateEvent && $updateTeamMatch)
	echo "ok";
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';