<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$vorname = mysql_real_escape_string($_POST['vorname']);
$nachname = mysql_real_escape_string($_POST['nachname']);
$email = strtolower(mysql_real_escape_string($_POST['email']));
$gebdatum = strtolower(mysql_real_escape_string($_POST['gebdatum']));
$teamID = strtolower(mysql_real_escape_string($_POST['teamID']));
$clubID = $_SESSION['clubID'];
// Wenn E-Mail Adresse vorhanden, prüfen ob bereits existiert
if ($email != ""){
	$datenEMail = mysql_query("SELECT `userID` FROM `usertab` WHERE `userEmail` = '$email'");
	$datenEMailTab = mysql_fetch_array($datenEMail, MYSQL_NUM);
	$anzahlGleicheEMail = $datenEMailTab[0];
	// Wenn E-Mail Adresse schon existiert
	if ($anzahlGleicheEMail != 0){
		echo "emailSchonVorhanden";
		exit();
	}
	// Wenn E-Mail Adresse frei
	else{
		// Zufälliges Passwort generieren (6-stellig)
		$passwort = "";
		$pool = "qwertzupasdfghkyxcvbnm23456789";
		srand ((double)microtime()*1000000);
		for($n = 0; $n <= 5; $n++) {
			$passwort .= substr($pool,(rand()%(strlen ($pool))), 1);
		}
		// Passwort Hashen
		$hashedPassword = cryptPassword($passwort);
		// Zufallskey generieren und diesen an Best�tigungslink anf�gen
		$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
		$schluessel = md5($zufallskey);
		// Daten für E-Mail Nachricht festlegen
		$bestaetigungslink = "<a href='http://manageyourteam.de/Bestaetigung/" . $email . "/" . $schluessel . "'>Benutzerkonto aktivieren</a>";
		$bestaetigungslink_alternativ = "http://manageyourteam.de/Bestaetigung/" . $email . "/" . $schluessel;
		// Daten für Nachricht an Registrar
		$sender = "MAIL_FROM_ADDRESS";
		$receiver = $email;
		$mailtext = "<div>Herzlich Willkommen $vorname $nachname,<br><br>soeben wurde auf ManageYourTeam ein Spielerprofil für Sie angelegt.<br>Das Team von ManageYourTeam begrüßt Sie recht herzlich und wünscht Ihnen viel Spaß!<br>Ihr Passwort lautet: $passwort<br><br>Bitte betätigen Sie folgenden Link, um Ihr Benutzerkonto freizuschalten: $bestaetigungslink<br><br>Alternativ können Sie auch folgenden Link in die Adressleiste Ihres Browsers kopieren: <br>$bestaetigungslink_alternativ<br>Im Anschluss daran können Sie sich einloggen.<br><br>Schöne Grüße<br><br>	Das Team von ManageYourTeam<br>-----------------------------------------------------------------------------<br>Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.</div>";
		$subject = "Herzlich Willkommen bei manageyourteam.de";
		// Nachricht senden
		sendEmail($sender, $receiver, $mailtext, $subject);
		// Neuen Benutzer in DB eintragen
		$dateneintragUser = mysql_query("INSERT INTO `usertab`(`userEmail`, `userPassword`, `userKey`, `userActive`, `userRights`) VALUES ('$email', '$hashedPassword', '$schluessel', '0', '2')");
	}
}
// Wenn keine E-Mail Adresse gesetzt
else
	// Neuen Benutzer in DB eintragen
	$dateneintragUser = mysql_query("INSERT INTO `usertab`(`userRights`) VALUES ('2')");
// ID der User-Tabelle
$userTabID = mysql_insert_id();
// Neuen Spieler eintragen
$dateneintragPlayer = mysql_query("INSERT INTO `playertab`(`userID`, `teamID`, `clubID`, `playerFirstName`, `playerSecondName`, `playerBirthdate`) VALUES ('$userTabID', '$teamID', '$clubID', '$vorname', '$nachname', '$gebdatum')");
// Wenn Eintragungen erfolgreich waren
if ($dateneintragUser && $dateneintragPlayer)
	echo "ok";
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';