<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
$email = $_SESSION['email'];
$userID = $_SESSION['userID'];
// DB Abfrage EventDaten
$datenEvent = mysql_query("SELECT * FROM `eventtab` WHERE `eventID` = '$eventID'");
$datenEventTab = mysql_fetch_array($datenEvent, MYSQL_NUM);
$teamID = $datenEventTab[1];
$clubID = $datenEventTab[2];
$eventType = $datenEventTab[3];
$eventInfo = $datenEventTab[4];
$location = $datenEventTab[5];
$eventStartDate = $datenEventTab[6];
$eventEndDate = $datenEventTab[7];
$eventStartTime = $datenEventTab[8];
$eventEndTime = $datenEventTab[9];
// JSON Array erstellen
$return = array("teamID" => $teamID, "clubID" => $clubID, "eventType" => $eventType, "eventInfo" => $eventInfo, "location" => $location, "eventStartDate" => $eventStartDate, "eventEndDate" => $eventEndDate, "eventStartTime" => $eventStartTime, "eventEndTime" => $eventEndTime);
echo json_encode($return);