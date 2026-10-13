<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();

// Variablen abfragen
$vorname = mysql_real_escape_string($_POST["vorname"]);
$nachname = mysql_real_escape_string($_POST["nachname"]);
$verein = mysql_real_escape_string($_POST["verein"]);
$email = mysql_real_escape_string($_POST["email"]);
$passwort = mysql_real_escape_string($_POST["passwort"]);
// Prüfen, ob E-Mail Adresse schon vorhanden ist
$datenEMail = mysql_query("SELECT userID FROM usertab WHERE userEmail = '$email'");
$datenEMailTab = mysql_fetch_array($datenEMail, MYSQL_NUM);
$anzahlGleicheEMail = $datenEMailTab[0];
if ($anzahlGleicheEMail != 0){
	echo "emailSchonVorhanden";
	exit;
}
$hashedPassword = cryptPassword($passwort);
// Zufallskey generieren und diesen an Best�tigungslink anf�gen
$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
$schluessel = md5($zufallskey);
// Neuen Benutzer in DB eintragen
$dateneintragUser = mysql_query("INSERT INTO `usertab`(`userEmail`, `userPassword`, `userKey`, `userActive`, `userRights`) VALUES ('$email', '$hashedPassword', '$schluessel', '0', '0')");
// ID der User-Tabelle
$userTabID = mysql_insert_id();
// Neuen Verein eintragen
$dateneintragClub = mysql_query("INSERT INTO `clubtab`(`userID`, `clubName`, `clubAdminFirstName`, `clubAdminSecondName`) VALUES ('$userTabID', '$verein', '$vorname', '$nachname')");
// Wenn Eintragungen erfolgreich waren
if ($dateneintragUser && $dateneintragClub){
	// Daten f�r Nachricht an Webmaster
	$sender = "MAIL_FROM_ADDRESS";
	$receiver = "MAIL_FROM_ADDRESS";
	$mailtext = "<div>Es hat sich soeben ein neuer Verein registriert!<br>Vereinsname: $verein.<br>Admin: $vorname $nachname.<br>Kontakt: $email.<br></div>";
	$subject = "Neuer Verein - " . $verein . " (Admin: " . $vorname . " " . $nachname . ")";
	// Nachricht senden
	sendEmail($sender, $receiver, $mailtext, $subject);
	// Daten für E-Mail Nachricht festlegen
	$bestaetigungslink = "<a href='http://manageyourteam.de/Bestaetigung/" . $email . "/" . $schluessel . "'>Accont aktivieren</a>";
	$bestaetigungslink_alternativ = "http://manageyourteam.de/Bestaetigung/" . $email . "/" . $schluessel;
	// Daten für Nachricht an Registrar
	$sender = "MAIL_FROM_ADDRESS";
	$receiver = $email;
	$mailtext = "<div>Herzlich Willkommen $vorname $nachname,<br><br>das Team von ManageYourTeam begrüßt Sie recht herzlich und wünscht Ihnen viel Spaß beim Verwalten Ihres Vereins!<br>Bitte betätigen Sie folgenden Link, um Ihr Benutzerkonto sowie Ihren Verein freizuschalten: $bestaetigungslink<br><br>Alternativ können Sie auch folgenden Link in die Adressleiste Ihres Browsers kopieren: <br>$bestaetigungslink_alternativ<br>Im Anschluss daran können Sie sich einloggen.<br><br>Schöne Grüße<br><br>	Das Team von ManageYourTeam<br>-----------------------------------------------------------------------------<br>Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.</div>";
	$subject = "Herzlich Willkommen bei manageyourteam.de";
	// Nachricht senden
	sendEmail($sender, $receiver, $mailtext, $subject);
	echo 'ok';
}
else
	echo "fehler";
?>