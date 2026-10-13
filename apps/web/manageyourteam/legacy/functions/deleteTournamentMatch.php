<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamTournamentID = mysql_real_escape_string($_POST['teamTournamentID']);
// Aus eventtab löschen
$deleteTournamentMatch = mysql_query("DELETE FROM `teamtournamenttab` WHERE `teamTournamentID` = '$teamTournamentID'");
// Wenn Löschen geklappt hat
if ($deleteTournamentMatch)
	echo "ok";
else
	echo "Löschen fehlgeschlagen";