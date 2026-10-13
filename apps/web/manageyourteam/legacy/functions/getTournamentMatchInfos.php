<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamTournamentID = mysql_real_escape_string($_POST['teamTournamentID']);
$email = $_SESSION['email'];
$userID = $_SESSION['userID'];
// DB Abfrage MatchDaten
$datenMatch = mysql_query("SELECT `teamGoals`, `opponentGoals` FROM `teamtournamenttab` WHERE `teamTournamentID` = '$teamTournamentID'");
$datenMatchTab = mysql_fetch_array($datenMatch, MYSQL_NUM);
$teamGoals = $datenMatchTab[0];
$opponentGoals = $datenMatchTab[1];
// JSON Array erstellen
$return = array("teamGoals" => $teamGoals, "opponentGoals" => $opponentGoals);
echo json_encode($return);