<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$vereinKurzbeschreibung = mysql_real_escape_string($_POST['vereinKurzbeschreibung']);
$vereinLangbeschreibung = mysql_real_escape_string($_POST['vereinLangbeschreibung']);
$vereinHistorie = mysql_real_escape_string($_POST['vereinHistorie']);
// Verein updaten
$updateClub = mysql_query("UPDATE `clubtab` SET `clubShort` = '$vereinKurzbeschreibung', `clubLong` = '$vereinLangbeschreibung', `clubHistory` = '$vereinHistorie' WHERE `clubID` = '" . $_SESSION['clubID'] . "'");
// Wenn Dateneintrag erfolgreich war
if ($updateClub)
	echo "ok";
else
	echo "fehlerDateneintrag";