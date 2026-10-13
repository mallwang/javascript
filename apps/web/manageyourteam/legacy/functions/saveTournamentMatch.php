<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamTournamentID = mysql_real_escape_string($_POST['teamTournamentID']);
$teamGoals = mysql_real_escape_string($_POST['teamGoals']);
$opponentGoals = mysql_real_escape_string($_POST['opponentGoals']);
// Spieldaten eintragen
$datenupdateMatch = mysql_query("UPDATE `teamtournamenttab` SET `teamGoals` = '$teamGoals', `opponentGoals` = '$opponentGoals' WHERE `teamTournamentID` = $teamTournamentID");
// Wenn Eintragungen erfolgreich waren
if ($datenupdateMatch)
	echo "ok";
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';