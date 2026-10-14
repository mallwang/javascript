<?php session_start(); include 'connect.php'; include 'funktionen.php';

// LOGIN
if ($_POST["art"] == "login"){
	$nickname = $_POST["nickname"];
	$passwort = md5($_POST["passwort"]);
	// Prüfen, ob Email Adresse eingegeben wurde
	if(preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$^", $nickname)){
		$nickname = strtolower($nickname);
		$daten = anfrage("SELECT email, passwort, aktiviert, id, nickname FROM user WHERE email = '$nickname'");
		$email = $nickname;
		$id = $daten[3];
		$username = $daten[4];
	}
	// Wenn nicht, handelt es sich um einen Benutzernamen
	else{
		$daten = anfrage("SELECT nickname, passwort, aktiviert, email, id FROM user WHERE nickname = '$nickname'");
		$email = $daten[3];
		$id = $daten[4];
		$username = $daten[0];
	}
	$dbNickname = $daten[0];
	$dbPasswort = $daten[1];
	$dbAktiviert = $daten[2];
	// Prüfen, ob logindaten übereinstimmen
	if($nickname == $dbNickname AND $passwort == $dbPasswort){
		// Wenn der Account schon aktiviert wurde
		if ($dbAktiviert == 1){
			// Aus Tabelle loeschen entfernen
			anfrage("DELETE FROM loeschen WHERE user_id = '$id'");
			// Session 'username' erzeugen
			$_SESSION['username'] = $username;
			// Session 'nickname' erzeugen
			$_SESSION['nickname'] = $email;
			// Session 'user_id' erzeugen
			$_SESSION['userId'] = $id;
			echo 'ok';
		}
		// Wenn der Account noch nicht aktiviert wurde
		else{
			echo 'nichtAktiviert';
		}
	}
	// Wenn die logindaten nicht übereinstimmen
	else{
		echo 'no';
	}
}
// REGISTRIERUNG
elseif ($_POST["art"] == "regis"){
	// Alle Variablen initialisieren
	$nickname = $_POST["nickname"];
	$email1 = strtolower($_POST["email1"]);
	$passwort1 = $_POST["passwort1"];
	$passwort2 = $_POST["passwort2"];
	//Passwort md5-verschlüsseln
	$passwort = md5($passwort1);
	// Zufallskey generieren und diesen an Bestätigungslink anfügen
	$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
	$schluessel = md5($zufallskey);
	//Neuen User in DB eintragen
	$dateneintrag = anfrage("INSERT INTO `user`(`schluessel`, `nickname`, `email`, `passwort`) VALUES ('$schluessel', '$nickname', '$email1', '$passwort')");
	// Wenn neuer User erfolgreich eintgetragen wurden
	if($dateneintrag){
		// Mitteilung an Webmaster
		$empfaengerWebmaster = "kontenverwaltung@i-use-my-brain.com";
		$absender = "i-use-my-brain.com <info@i-use-my-brain.com>";
		$betreffWebmaster = "Neuer Benutzer " . $nickname;
		$headerWebmaster  = "MIME-Version: 1.0\r\n";
		$headerWebmaster .= "Content-type: text/html; charset=utf-8\r\n";
		$headerWebmaster .= "From: $absender\r\n";
		$headerWebmaster .= "Reply-To: $absender\r\n";
		// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
		$headerWebmaster .= "X-Mailer: PHP ". phpversion();
		$textWebmaster = "
		<html>
			<body>
				<div>
					Es hat sich soeben ein neuer Benutzer registriert!<br>
					Sein Name ist $nickname.<br>
				</div>
			</body>
		</html>";
		// Mail versenden
		mail($empfaengerWebmaster, $betreffWebmaster, $textWebmaster, $headerWebmaster);
		// Daten für E-Mail Nachricht festlegen
		$bestaetigungslink = "<a href='http://www.i-use-my-brain.com/bestaetigung.php?key=$schluessel&benutzer=$nickname'>Accont aktivieren</a>";
		$bestaetigungslink_alternativ = "http://www.i-use-my-brain.com/bestaetigung.php?key=$schluessel&benutzer=$nickname";
		$empfaenger = $email1;
		$absender = "i-use-my-brain.com <kontoverwaltung@i-use-my-brain.com>";
		$betreff = "Herzliche Willkommen bei i-use-my-brain";
		$header  = "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html; charset=utf-8\r\n";
		$header .= "From: $absender\r\n";
		$header .= "Reply-To: $absender\r\n";
		// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
		$header .= "X-Mailer: PHP ". phpversion();
		$text = "
		<html>
			<body>
				<div>
					Herzlich Willkommen $nickname,<br><br>
					das Team von i-use-my-brain begr&uuml;ßt dich recht herzlich und w&uuml;nscht dir viel Spaß!<br>
					Bitte betätige diesen Link, um deinen Account zu aktivieren: $bestaetigungslink<br>
				</div>
				<br><br>
				<div>
					Alternativ kannst du auch folgenden Link in die Adressleiste deines Browsers kopieren: <br>
					$bestaetigungslink_alternativ
				</div>
			</body>
		</html>";
		// Mail versenden
		mail($empfaenger, $betreff, $text, $header);
		echo 'ok';
	}
	else{
		echo 'fehler';
	}
}
// WEDER LOGIN NOCH REGISTRIERUNG
else{
	echo 'fehler';
}
?>