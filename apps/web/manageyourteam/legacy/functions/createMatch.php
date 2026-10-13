<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
$teamID = mysql_real_escape_string($_POST['teamID']);
$startdatum = mysql_real_escape_string($_POST['matchStartDate']);
$enddatum = mysql_real_escape_string($_POST['matchEndDate']);
$startzeit = mysql_real_escape_string($_POST['matchStartTime']);
$endzeit = mysql_real_escape_string($_POST['matchEndTime']);
$opponent = mysql_real_escape_string($_POST['opponent']);
// Neue Mannschaft eintragen
$dateneintragMatch = mysql_query("INSERT INTO `teamtournamenttab`(`eventID`, `teamID`, `matchStartDate`, `matchEndDate`, `matchStartTime`, `matchEndTime`, `opponent`) VALUES ('$eventID', '$teamID', '$startdatum', '$enddatum', '$startzeit', '$endzeit', '$opponent')");
// Wenn Eintragungen erfolgreich waren
if ($dateneintragMatch)
	echo 'ok';
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';