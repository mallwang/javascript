<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
// Aus eventtab und teamtournamenttab löschen
$deleteEvent = mysql_query("DELETE FROM `eventtab` WHERE `eventID` = '$eventID'");
$deleteTournament = mysql_query("DELETE FROM `teamtournamenttab` WHERE `eventID` = '$eventID'");
// Wenn Löschen geklappt hat
if ($deleteEvent && $deleteTournament)
	echo "ok";
else
	echo "Löschen fehlgeschlagen";