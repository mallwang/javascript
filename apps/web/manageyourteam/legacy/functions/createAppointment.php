<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamID = mysql_real_escape_string($_POST['teamID']);
$appointmentType = mysql_real_escape_string($_POST['appointmentType']);
$info = mysql_real_escape_string($_POST['info']);
$location = mysql_real_escape_string($_POST['location']);
$startdatum = mysql_real_escape_string($_POST['startdatum']);
$enddatum = mysql_real_escape_string($_POST['enddatum']);
$startuhrzeit = mysql_real_escape_string($_POST['startuhrzeit']);
$enduhrzeit = mysql_real_escape_string($_POST['enduhrzeit']);
$clubID = $_SESSION['clubID'];
// Neuen Termin in eventtab eintragen
$dateneintragEvent = mysql_query("INSERT INTO `eventtab`(`teamID`, `clubID`, `eventType`, `eventInfo`, `location`, `eventStartDate`, `eventEndDate`, `eventStartTime`, `eventEndTime`) VALUES ('$teamID', '$clubID', '$appointmentType', '$info', '$location', '$startdatum', '$enddatum', '$startuhrzeit', '$enduhrzeit')");
// Wenn es ein Spiel ist
if ($appointmentType == 2){
	// EventID abfragen
	$eventID = mysql_insert_id();
	// Eintrag in teammatchtab eintragen
	$dateneintragTeamMatch = mysql_query("INSERT INTO `teammatchtab` (`teamID`, `eventID`, `opponent`, `location`) VALUES ('$teamID', '$eventID', '$info', '$location')");
}
else
	$dateneintragTeamMatch = true;

if ($dateneintragEvent && $dateneintragTeamMatch)
	echo "ok";
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';
