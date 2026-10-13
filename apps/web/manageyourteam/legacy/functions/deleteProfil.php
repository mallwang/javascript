<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$passwort = mysql_real_escape_string($_POST['passwort']);
$email = $_SESSION['email'];
$userID = $_SESSION['userID'];
// DB Abfrage userPasswort
$datenUser = mysql_query("SELECT `userPassword` FROM `usertab` WHERE `userID` = '$userID'");
$datenUserTab = mysql_fetch_array($datenUser, MYSQL_NUM);
// Prüfen, ob Passwort übereinstimmt
if(crypt($passwort, $datenUserTab[0]) == $datenUserTab[0]){
	// Flag setzen
	$updateUser = mysql_query("UPDATE `usertab` SET `userDelete` = 1 WHERE `userID` = '$userID'");
	// Wenn Dateneintrag erfolgreich war
	if ($updateUser){
		// E-Mail an Benutzer versenden
		$sender = "MAIL_FROM_ADDRESS";
		$receiver = $email;
		$mailtext = "<div>Sehr geehrter Benutzer,<br><br>soeben wurde Ihr Benutzerprofil zur Löschung vorgesehen, welches nach Ablauf einer Dreitagesfrist endgültig gelöscht wird.<br>Sollten Sie sich innerhalb dieser Zeitspanne erneut einloggen, wird das Löschen widerrufen.<br><br><br>Das Team von ManageYourTeam<br>-----------------------------------------------------------------------------<br>Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.</div>";
		$subject = "Profillöschung bei manageyourteam.de";
		
		// Nachricht senden
		sendEmail($sender, $receiver, $mailtext, $subject);
		echo "ok";
	}
	else
		echo "fehlerDateneintrag";
}
// Wenn Passwort nicht übereinstimmt
else
	echo "passwortFalsch";