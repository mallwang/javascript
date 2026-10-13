<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
// DB Abfrage MatchDaten
$datenMatch = mysql_query("SELECT `teamID`, `clubID`, `eventInfo`, `eventStartDate`, `eventEndDate`, `eventStartTime`, `eventEndTime` FROM `eventtab` WHERE `eventID` = '$eventID'");
$datenMatchTab = mysql_fetch_array($datenMatch, MYSQL_NUM);
$teamID = $datenMatchTab[0];
$clubID = $datenMatchTab[1];
$eventInfo = $datenMatchTab[2];
$eventStartDate = $datenMatchTab[3];
$eventEndDate = $datenMatchTab[4];
$eventStartTime = $datenMatchTab[5];
$eventEndTime = $datenMatchTab[6];
// JSON Array erstellen
$return = array("teamID" => $teamID, "clubID" => $clubID, "eventInfo" => $eventInfo, "eventStartDate" => $eventStartDate, "eventEndDate" => $eventEndDate, "eventStartTime" => $eventStartTime, "eventEndTime" => $eventEndTime);
echo json_encode($return);