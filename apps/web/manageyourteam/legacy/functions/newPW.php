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
	// Neues Passwort erstellen (6-stellig)
	$passwort = "";
	$pool = "qwertzupasdfghkyxcvbnm23456789";
	srand ((double)microtime()*1000000);
	for($n = 0; $n <= 5; $n++) {
		$passwort .= substr($pool,(rand()%(strlen ($pool))), 1);
	}
	// Passwort Hashen
	$hashedPassword = cryptPassword($passwort);
	// Neues PW in DB eintragen
	$dateneintragUser = mysql_query("UPDATE `usertab` SET `userPassword` = '$hashedPassword' WHERE `userEmail` = '$email'");
	// Wenn Eintragungen erfolgreich waren
	if ($dateneintragUser){
		// Daten für Nachricht an Registrar
		$sender = "MAIL_FROM_ADDRESS";
		$receiver = $email;
		$mailtext = "<div>Sehr geehrter User, sie haben soeben ein neues Passwort angefordert,<br>dieses lautet: " . $passwort . "<br>Sie können sich nun damit einloggen.<br><br>Schöne Grüße<br><br>Das Team von ManageYourTeam<br>-----------------------------------------------------------------------------<br>Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.</div>";
		$subject = "Passwortänderung bei manageyourteam.de";
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