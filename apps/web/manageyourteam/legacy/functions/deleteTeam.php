<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamID = mysql_real_escape_string($_POST['teamID']);
// teamtab löschen
$deleteTeam = mysql_query("DELETE FROM `teamtab` WHERE `teamID` = $teamID");
// teamcoachtab löschen
$deleteTeamCoach = mysql_query("DELETE FROM `teamcoachtab` WHERE `teamID` = $teamID");
// teammatchtab löschen
$deleteTeamMatch = mysql_query("DELETE FROM `teammatchtab` WHERE `teamID` = $teamID");
// teamtournamenttab löschen
$deleteTeamTournament = mysql_query("DELETE FROM `teamtournamenttab` WHERE `teamID` = $teamID");
// eventtab löschen
$allEvents = mysql_query("SELECT `eventID` FROM `eventtab` WHERE `teamID` = $teamID");
while ($eventRow = mysql_fetch_array($allEvents)) {
	$deleteTeamAttendance = mysql_query("DELETE FROM `attendancetab` WHERE `eventID` = $eventRow[0]");
}
// eventtab löschen
$deleteEvent = mysql_query("DELETE FROM `eventtab` WHERE `teamID` = $teamID");
// Wenn Löschen geklappt hat
if ($deleteTeam && $deleteTeamCoach && $deleteTeamMatch && $deleteTeamAttendance && $deleteTeamTournament && $deleteTeamTournament && $deleteEvent)
	echo "ok";
else
	echo "Löschen fehlgeschlagen";