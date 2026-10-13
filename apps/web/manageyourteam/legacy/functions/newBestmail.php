<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Variablen abfragen
$email = mysql_real_escape_string($_POST["email"]);
// Prüfen, ob E-Mail Adresse existiert
$datenEMail = mysql_query("SELECT `userID` FROM `usertab` WHERE `userEmail` = '$email'");
$datenEMailTab = mysql_fetch_array($datenEMail, MYSQL_NUM);
$anzahlGleicheEMail = $datenEMailTab[0];
// Wenn E-Mail in DB vorhanden
if ($anzahlGleicheEMail != 0){
	// Vereinsname abfragen
	$datenClub = mysql_query("SELECT `clubName` FROM `clubtab` WHERE `userID` = '$anzahlGleicheEMail'");
	$datenClubTab = mysql_fetch_array($datenClub, MYSQL_NUM);
	$verein = $datenClubTab[0];
	// Zufallskey generieren
	$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
	$schluessel = md5($zufallskey);
	// Neuen Schlüsswl in DB eintragen
	$dateneintragUser = mysql_query("UPDATE `usertab` SET `userKey` = '$schluessel' WHERE `userEmail` = '$email'");
	// Wenn Eintragung erfolgreich war
	if ($dateneintragUser){
		// Daten für E-Mail Nachricht festlegen
		//$bestaetigungslink = "<a href='manageyourteam.de/bestaetigung.php?schluessel=".$schluessel."&verein=".$verein."'>Accont aktivieren</a>";
		$bestaetigungslink = "<a href='http://manageyourteam.de/Bestaetigung/" . $email . "/" . $schluessel . "'>Account aktivieren</a>";
		$bestaetigungslink_alternativ = "http://manageyourteam.de/Bestaetigung/" . $email . "/" . $schluessel;
		// Daten für Nachricht an Registrar
		$sender = "MAIL_FROM_ADDRESS";
		$receiver = $email;
		$mailtext = "<div>Sehr geehrter User,<br>Bitte betätigen Sie folgenden Link, um Ihr Benutzerkonto sowie Ihren Verein freizuschalten: $bestaetigungslink<br><br>Alternativ können Sie auch folgenden Link in die Adressleiste Ihres Browsers kopieren: <br>$bestaetigungslink_alternativ<br>Im Anschluss daran können Sie sich einloggen.<br><br>Schöne Grüße<br><br>	Das Team von ManageYourTeam<br>-----------------------------------------------------------------------------<br>Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.</div>";
		$subject = "Neue Bestätigungsmail";
		// Nachricht senden
		sendEmail($sender, $receiver, $mailtext, $subject);
		echo "ok";
	}
	else
		echo "fehler";
}
else
	echo "emailExistiertNicht";
?>