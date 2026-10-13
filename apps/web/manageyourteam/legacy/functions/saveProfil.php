<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();

// Daten übernehmen
$vorname = mysql_real_escape_string($_POST['vorname']);
$nachname = mysql_real_escape_string($_POST['nachname']);
$email = mysql_real_escape_string($_POST['email']);
$userID = $_SESSION['userID'];
// Wenn Administrator
if ($_SESSION['rights'] == 0){
	$updateUser = mysql_query("UPDATE `usertab` SET `userEmail`='$email' WHERE `userID` = '$userID'");
	$updateSpecific = mysql_query("UPDATE `clubtab` SET `clubAdminFirstName`='$vorname', `clubAdminSecondName`='$nachname' WHERE `userID` = $userID");
}
// Wenn Trainer
else if ($_SESSION['rights'] == 1){
	$updateUser = mysql_query("UPDATE `usertab` SET `userEmail`='$email' WHERE `userID` = '$userID'");
	$updateSpecific = mysql_query("UPDATE `coachtab` SET `coachFirstName`='$vorname', `coachSecondName`='$nachname' WHERE `userID` = $userID");
}
// Wenn Spieler
else if ($_SESSION['rights'] == 2){
	$updateUser = mysql_query("UPDATE `usertab` SET `userEmail`='$email' WHERE `userID` = '$userID'");
	$updateSpecific = mysql_query("UPDATE `playertab` SET `playerFirstName`='$vorname', `playerSecondName`='$nachname' WHERE `userID` = $userID");
}
// Wenn Eintragungen erfolgreich waren
if ($updateUser && $updateSpecific){
	// Sessions neu setzen
	$_SESSION['vorname'] = $vorname;
	$_SESSION['nachname'] = $nachname;
	$_SESSION['email'] = $email;
	echo 'ok';
}
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';