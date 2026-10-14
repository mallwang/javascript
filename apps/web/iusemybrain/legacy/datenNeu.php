<?php session_start(); include 'connect.php'; include 'funktionen.php';

// PASSWORT INTERN
if ($_POST["art"] == "passwortIntern"){
	// Alle Variablen initialisieren
	$aktUser = $_SESSION["nickname"];
	$passwort1 = md5($_POST["passwort1"]);
	$passwort2 = $_POST["passwort2"];
	$passwort = md5($_POST["passwort"]);
	// Abfragen ob Passwort übereinstimmt
	$passwortDB = anfrage("SELECT passwort, nickname FROM user WHERE email = '$aktUser'");
	if ($passwort == $passwortDB[0]){
		// Prüfen, ob altes Passwort != neuem Passwort
		if ($passwort1 != $passwortDB[0]){
			// Nickname festlegen (für E-Mail)
			$benutzer = $passwortDB[1];
			// Passwort eintragen
			$daten = anfrage("UPDATE `user` SET `passwort`='$passwort1' WHERE email = '$aktUser'");
			// Wenn Update erfolgreich war
			if($daten){
				// E-Mail an versenden
				$empfaenger = $aktUser;
				$absender = "kontenverwaltung@i-use-my-brain.com";
				$betreff = "Neues Passwort";
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
				Hallo $benutzer,<br><br>
				dies ist eine automatische Mitteilung &uuml;ber die &Auml;nderung deines Passworts.<br>
				Es lautet nun: <b>$passwort2</b><br><br>
				Sch&ouml;ne Gr&uuml;&szlig;e<br><br>
				Das Team von i-use-my-brain.com<br>
				-----------------------------------------------------------------------------<br>
				Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.
				</div>
				</body>
				</html>";
				// Mail versenden
				$versandNeu = mail($empfaenger, $betreff, $text, $header);
				// Wenn E-Mail ersandt wurde
				if ($versandNeu){
					echo "ok"; exit;
				}
				// Wenn E-Mail nicht versandt wurde
				else{
					echo "fehlerEmailVersand"; exit;
				}
			}
			// Wenn Update nicht erfolgreich war
			else{
				echo "fehlerUpdate"; exit;
			}
		}
		// Wenn altes Passwort != neuem Passwort
		else{
			echo "gleichesPW"; exit;
		}
	}
	// Wenn Passwort nicht übereinstimmt
	else{
		echo "pwFalsch"; exit;
	}
}

// EMAIL
if ($_POST["art"] == "email"){
	// Alle Variablen initialisieren
	$aktUser = $_SESSION["nickname"];
	$email = strtolower($_POST["email1"]);
	$passwort = md5($_POST["passwort"]);
	// Prüfen, ob E-Mail Adresse korrekt ist
	if(preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$^", $email)){
		// Passwort abfragen
		$passwortDB = anfrage("SELECT passwort, nickname FROM user WHERE email = '$aktUser'");
		// Wenn Passwort übereinstimmt
		if ($passwort == $passwortDB[0]){
			// Prüfen, ob neue E-Mail Adresse bereits existiert
			$emailVorhanden = anfrage("SELECT COUNT(id) as anzahl FROM user WHERE email = '$email'");
			if($emailVorhanden[0] == 0){
				// Nickname festlegen (für E-Mail)
				$benutzer = $passwortDB[1];
				// E-Mail Adresse eintragen
				$daten = anfrage("UPDATE `user` SET `email`='$email' WHERE email = '$aktUser'");
				// Wenn Update erfolgreich war
				if($daten){
					// Session neu setzen
					$_SESSION["nickname"] = $email;
					// E-Mail an neue Adresse versenden
					$empfaenger = $email;
					$absender = "kontenverwaltung@i-use-my-brain.com";
					$betreff = "Neue E-Mail Adresse";
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
					Hallo $benutzer,<br><br>
					dies ist eine automatische Mitteilung &uuml;ber die &Auml;nderung deiner E-Mail Adresse.<br>
					Diese wurde soeben von <b>$aktUser</b> auf <b>$email</b> ge&auml;ndert.<br><br>
					Sch&ouml;ne Gr&uuml;&szlig;e<br><br>
					Das Team von i-use-my-brain.com<br>
					-----------------------------------------------------------------------------<br>
					Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.
					</div>
					</body>
					</html>";
					// Mail versenden
					$versandNeu = mail($empfaenger, $betreff, $text, $header);
					// Wenn E-Mail an neue Adresse versandt wurde
					if ($versandNeu){
						// E-Mail an alte Adresse versenden
						$empfaenger = $aktUser;
						$absender = "kontenverwaltung@i-use-my-brain.com";
						$betreff = "Neue E-Mail Adresse";
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
						Hallo $benutzer,<br><br>
						dies ist eine automatische Mitteilung &uuml;ber die &Auml;nderung deiner E-Mail Adresse.<br>
						Diese wurde soeben von <b>$aktUser</b> auf <b>$email</b> ge&auml;ndert.<br><br>
						Sch&ouml;ne Gr&uuml;&szlig;e<br><br>
						Das Team von i-use-my-brain.com<br>
						-----------------------------------------------------------------------------<br>
						Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.
						</div>
						</body>
						</html>";
						// Mail versenden
						$versandAlt = mail($empfaenger, $betreff, $text, $header);
						// Wenn E-Mail an alte Adresse versandt wurde
						if ($versandAlt){
							echo "ok"; exit;
						}
						// Wenn E-Mail an alte Adresse nicht versandt wurde
						else{
							echo "fehlerAlteEmailVersand"; exit;
						}
					}
					// Wenn E-Mail an neue Adresse nicht versandt wurde
					else{
						echo "fehlerNeueEmailVersand"; exit;
					}
				}
				// Wenn Update nicht erfolgreich war
				else{
					echo "fehlerUpdate"; exit;
				}
			}
			// Wenn E-Mail Adresse bereits existiert
			else{
				echo "eMailVorhanden"; exit;
			}
		}
		// Wenn Passwort nicht übereinstimmt
		else{
			echo "pwFalsch"; exit;
		}
	}
	// Wenn E-Mail Adresse falsch
	else{
		echo "emailFalsch"; exit;
	}
}

// ACCOUNT LÖSCHEN
if ($_POST["art"] == "accountLoeschen"){
	// Alle Variablen initialisieren
	$aktUser = $_SESSION["nickname"];
	$userId = $_SESSION["userId"];
	$passwort = md5($_POST["passwort"]);
	// Abfragen ob Passwort übereinstimmt
	$passwortDB = anfrage("SELECT passwort, nickname FROM user WHERE email = '$aktUser'");
	if ($passwort == $passwortDB[0]){
		// Nickname festlegen (für E-Mail)
		$benutzer = $passwortDB[1];
		// In Tabelle 'loeschen' eintragen
		$loeschen = anfrage("INSERT INTO `loeschen`(`user_id`) VALUES ('$userId')");
		// Wenn in löschen eingetragen
		if ($loeschen){
			// E-Mail versenden
			$empfaenger = $aktUser;
			$absender = "kontenverwaltung@i-use-my-brain.com";
			$betreff = "Account wurde zum Loeschen vorgemerkt";
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
			Hallo $benutzer,<br><br>
			soeben wurde die L&ouml;schung deines Accounts beantragt.<br>
			Um Mi&szlig;brauch und Handlungsfehlern vorzubeugen, wird dieser nach Ablauf von <b>3 Tagen</b> gel&ouml;scht.<br>
			Solltest du dich innerhalb dieser Frist gegen das L&ouml;schen entscheiden, gen&uuml;gt es, dich wie gewohnt einzuloggen.<br>
			Die L&ouml;schung wird anschlie&szlig;end abgebrochen.<br><br>
			Sch&ouml;ne Gr&uuml;&szlig;e<br><br>
			Das Team von i-use-my-brain.com<br>
			-----------------------------------------------------------------------------<br>
			Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.
			</div>
			</body>
			</html>";
			// Mail versenden
			$versandNeu = mail($empfaenger, $betreff, $text, $header);
			// Wenn E-Mail ersandt wurde
			if ($versandNeu){
				echo "ok"; exit;
			}
			// Wenn E-Mail nicht versandt wurde
			else{
				echo "fehlerEmailVersand"; exit;
			}
		}
		// Wenn in löschen nicht eingetragen
		else{
			echo 'fehlerEintrag'; exit;
		}
	}
	// Wenn Passwort nicht übereinstimmt
	else{
		echo "pwFalsch"; exit;
	}
}

// PASSWORT
if ($_POST["art"] == "passwort"){
	// Wenn ein Feld leer ist
	if ($_POST["nickname"] == ""){
		echo "nichtAlleWerte";
	}
	else{
		$nickname = $_POST["nickname"];
		// neues PW erstellen
		$passwort1 = substr(md5(microtime()), mt_rand(0, 24), 8);
		$passwort = md5($passwort1);
		// Prüfen, ob Email Adresse eingegeben wurde
		if(preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$^", $nickname)){
			$nickname = strtolower($nickname);
			// Prüfen, ob in DB enthalten
			$user = anfrage("SELECT COUNT(id) as anzahl, nickname FROM user WHERE email = '$nickname'");
			// Wenn User existiert
			if ($user[0] == 1){
				// neues PW eintragen
				$daten = anfrage("UPDATE `user` SET `passwort`='$passwort' WHERE email = '$nickname'");
				$email = $nickname;
				$benutzer = $user[1];
			}
			else{
				echo 'nichtExistentEmail'; exit;
			}

		}
		// Wenn nicht, handelt es sich um einen Benutzernamen
		else{
			// Prüfen, ob in DB enthalten
			$user = anfrage("SELECT COUNT(id) as anzahl, email FROM user WHERE nickname = '$nickname'");
			// Wenn User existiert
			if ($user[0] == 1){
				// neues PW eintragen
				$daten = anfrage("UPDATE `user` SET `passwort`='$passwort' WHERE nickname = '$nickname'");
				$email = $user[1];
				$benutzer = $nickname;
			}
			else{
				echo 'nichtExistentBenutzer'; exit;
			}

		}
		// Wenn PW erfolgreich geupdatet wurde
		if($daten){
			// Daten für E-Mail Nachricht festlegen
			$empfaenger = $email;
			$absender = "i-use-my-brain.com <kontenverwaltung@i-use-my-brain.com>";
			$betreff = "Neues Passwort";
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
			Hallo $benutzer,<br><br>
			soeben wurde für dich ein neues Passwort beantragt.<br>
			Es lautet: $passwort1<br><br>
			Du kannst dich nun wieder wie gewohnt einloggen:<br>
			<a href='http://www.i-use-my-brain.com/login.php?benutzer=$benutzer'>http://www.i-use-my-brain.com</a><br>
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
}
// KEY
elseif ($_POST["art"] == "best"){
	// Wenn ein Feld leer ist
	if ($_POST["email"] == ""){
		echo "nichtAlleWerte";
	}
	// Wenn kein Feld leer ist
	else{
		// Alle Variablen initialisieren
		$email = strtolower($_POST["email"]);
		// Prüfen, ob in DB enthalten
		$user = anfrage("SELECT COUNT(id) as anzahl, nickname FROM user WHERE email = '$email'");
		// Wenn User existiert
		if ($user[0] == 1){
			// Daten zuweisen
			$benutzer = $user[1];
		}
		else{
			echo 'nichtExistentEmail'; exit;
		}
		// Prüfen, ob schon aktiviert
		$user = anfrage("SELECT aktiviert FROM user WHERE email = '$email'");
		// Wenn noch nicht aktiviert
		if ($user[0] == 0){
			// Neuen Zufallskey generieren und diesen an Bestätigungslink anfügen
			$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
			$schluessel = md5($zufallskey);
			// Datenbank updaten
			$daten = anfrage("UPDATE `user` SET `schluessel`='$schluessel' WHERE email = '$email'");
			// Wenn erfolgreich aktualisiert
			if ($daten){
				// Daten für E-Mail Nachricht festlegen
				$bestaetigungslink = "<a href='http://www.i-use-my-brain.com/bestaetigung.php?key=$schluessel&benutzer=$benutzer'>Accont aktivieren</a>";
				$bestaetigungslink_alternativ = "http://www.i-use-my-brain.com/bestaetigung.php?key=$schluessel&benutzer=$benutzer";
				$empfaenger = $email;
				$absender = "i-use-my-brain.com <kontenverwaltung@i-use-my-brain.com>";
				$betreff = "Neue Bestätigungsmail";
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
							Hallo $nickname,<br><br>
							soeben wurde für dich ein neuer Bestätigungscode beantragt.<br>
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
			// Wenn fehler beim aktualisieren
			else{
				echo 'fehler';
			}
		}
		// Wenn schon aktiviert
		else{
			echo 'schonAktiviert';
		}
	}
}
// WEDER LOGIN NOCH REGISTRIERUNG
else{
	echo 'fehler';
}
?>