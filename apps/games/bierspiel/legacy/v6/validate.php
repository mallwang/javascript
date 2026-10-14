<?php session_start(); include 'connect.php'; include 'funktionen.php';

// LOGIN
if ($_POST["art"] == "login"){
	$nickname = $_POST["nickname"];
	$passwort = md5($_POST["passwort"]);
	// Prüfen, ob Email Adresse eingegeben wurde
	if(preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$^", $nickname)){
		$nickname = strtolower($nickname);
		$daten = anfrage("SELECT email, passwort, aktiviert, id, anzahlLogins FROM user WHERE email = '$nickname'");
		$email = $nickname;
		$id = $daten[3];
		$anzahlLogins = $daten[4];
	}
	// Wenn nicht, handelt es sich um einen Benutzernamen
	else{
		$daten = anfrage("SELECT nickname, passwort, aktiviert, email, id, anzahlLogins FROM user WHERE nickname = '$nickname'");
		$email = $daten[3];
		$id = $daten[4];
		$anzahlLogins = $daten[5];
	}
	$dbNickname = $daten[0];
	$dbPasswort = $daten[1];
	$dbAktiviert = $daten[2];
	// Prüfen, ob logindaten übereinstimmen
	if($nickname == $dbNickname AND $passwort == $dbPasswort){
			// Firmenname abfragen
			$firmenname = anfrage("SELECT firmenname, id FROM firmendaten WHERE user_id = '$id'");
			// Wenn der Account schon aktiviert wurde
			if ($dbAktiviert == 1){
				// Anzahl Logins um 1 erhöhen und eintragen
				$anzahlLogins = $daten[5];
				$anzahlLogins += 1;
				$letzterLogin = date("Y-m-d H:i:s");
				$loginsEintragen = mysql_query("UPDATE user SET anzahlLogins = '$anzahlLogins', status = 1, letzterLogin = '$letzterLogin' WHERE id = '$id'");
				// Wenn neuer loginwert eingetragen wurde
				if ($loginsEintragen){
					// Aus Tabelle loeschen entfernen
					anfrage("DELETE FROM loeschen WHERE firma_id = '$firmenname[1]'");
					// Session 'nickname' erzeugen
					$_SESSION['nickname'] = $email;
					// Session 'user' erzeugen
					$_SESSION['user'] = $dbNickname;
					// Session 'user_id' erzeugen
					$_SESSION['userId'] = $id;
					// Session 'firmenname' erzeugen
					$_SESSION['firmenname'] = $firmenname[0];
					// Session 'firmaId' erzeugen
					$_SESSION['firmaId'] = $firmenname[1];
					echo 'ok';
				}
				// Wenn der loginwert nicht eingetragen wurde
				else{
					echo 'fehlerEintragAnzahlLogin';
				}
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
	$firmenname = $_POST["firmenname"];
	$standort = $_POST["standort"];
	// TODO: evtl. Name des Standortes zuweisen
	$bargeld = "200000";
	$festgeld = "300000";
	// Prüfen, ob ein Feld leer ist
	if ($nickname==""||$email1==""||$passwort1==""||$passwort2==""||$firmenname==""||$standort==""){
		echo 'nichtAlleAusgefuellt';
	}
	else{
		// Prüfen, ob Nickname oder E-Mail Adresse in der Zwischenzeit noch vorhanden sind
		$user = anfrage("SELECT COUNT(id) as anzahl FROM user WHERE nickname = '$nickname' OR email = '$email1'");
		if($user[0] != 0){
			echo 'benEmailVorhanden';
		}
		// Benutzer und/oder E-Mail Adresse noch frei
		else{
			// Abfrage ob Firmenname noch vorhanden ist
			$firma = anfrage("SELECT COUNT(id) as anzahl FROM firmendaten WHERE firmenname = '$firmenname'");
			if($firma[0] != 0){
				echo 'firmaVorhanden';
			}
			// Firmenname noch frei
			else{
				// Passwort md5-verschlüsseln
				$passwort = md5($passwort1);
				// Zufallskey generieren und diesen an Bestätigungslink anfügen
				$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
				$schluessel = md5($zufallskey);
				// Datum abfragen
				$heute = date("Y-m-d");
				// Neuen User in DB eintragen
				$dateneintrag = anfrage("INSERT INTO `user`(`schluessel`, `nickname`, `email`, `passwort`, `anzahlLogins`, `anmeldedatum`) VALUES ('$schluessel', '$nickname', '$email1', '$passwort', '0', '$heute')");
				// Wenn neuer User erfolgreich eintgetragen wurden
				if($dateneintrag){
					// User_id festlegen
					$user_id = mysql_insert_id();
					// Firma eintragen
					$firmaAnlegen = anfrage("INSERT INTO `firmendaten` (`user_id`, `hauptsitz`, `konto`, `kredit`, `gehaltskonto`, `woche`, `mitarbeiter`, `bekanntheit`, `beliebtheit`, `firmenname`)
						 VALUES ('$user_id', '$standort', '$bargeld', '0', '0', '0', '0', '0', '0', '$firmenname')");
					// Wenn Startrohstoffe erfolgreich eingetragen wurden
					if ($firmaAnlegen){
						// Firmen_id festlegen
						$firmenId = mysql_insert_id();
						// Finanzen eintragen
						$finanzenAnlegen = anfrage("INSERT INTO `finanzen` (`firma_id`, `konto`, `kredit`, `kreditrahmen`) VALUES ('$firmenId', '200000', '0', '200000')");
						if ($finanzenAnlegen){
							// Mitteilung an Webmaster
							$empfaengerWebmaster = "MAIL_WEBMASTER@example.com";
							$absenderWebmaster = "MAIL_SENDER@example.com";
							$betreffWebmaster = "Neuer Benutzer";
							$headerWebmaster  = "MIME-Version: 1.0\r\n";
							$headerWebmaster .= "Content-type: text/html; charset=utf-8\r\n";
							$headerWebmaster .= "From: $absenderWebmaster\r\n";
							$headerWebmaster .= "Reply-To: $absenderWebmaster\r\n";
							// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
							$headerWebmaster .= "X-Mailer: PHP ". phpversion();
							$textWebmaster = "
							<html>
								<body>
									<div>
										Es hat sich soeben ein neuer Benutzer registriert!<br>
										Nickname: $nickname.<br>
										Firma: $firmenname.<br>
										Standort: $standort.<br>
									</div>
								</body>
							</html>";
							// Mail versenden
							$versandtWebmaster = mail($empfaengerWebmaster, $betreffWebmaster, $textWebmaster, $headerWebmaster);
							// Wenn Nachricht an Webmaster versandt wurde
							if ($versandtWebmaster){
								// Daten für E-Mail Nachricht festlegen
								$bestaetigungslink = "<a href='example.com/Bestaetigung/$schluessel/$nickname/neuerAccount'>Accont aktivieren</a>";
								$bestaetigungslink_alternativ = "example.com/Bestaetigung/$schluessel/$nickname/neuerAccount";
								$empfaenger = $email1;
								$absender = "MAIL_SENDER@example.com";
								$betreff = "Herzliche Willkommen";
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
											das Team des Schankimpierums begr&uuml;&szlig;t dich recht herzlich und w&uuml;nscht dir viel Spa&szlig;!<br>
											Bitte bet&auml;tige diesen Link, um deinen Account zu aktivieren: $bestaetigungslink<br><br>
											Alternativ kannst du auch folgenden Link in die Adressleiste deines Browsers kopieren: <br>
											$bestaetigungslink_alternativ<br><br><br>
											Sch&ouml;ne Gr&uuml;&szlig;e<br><br>
											Das Team des Schankimperiums<br>
											-----------------------------------------------------------------------------<br>
											Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.
										</div>
									</body>
								</html>";
								// Mail versenden
								$versandt = mail($empfaenger, $betreff, $text, $header);
								// Wenn Nachricht versandt wurde
								if ($versandt){
									echo 'ok';
								}
								// Wenn Nachricht nicht versandt wurde
								else{
									echo 'fehlerVersandUser';
								}
							}
							// Wenn Nachricht an Webmaster nicht versandt wurde
							else{
								echo 'fehlerVersandWebmaster';
							}
						}
						else{
							echo 'fehlerFinanzenEintragen';
						}
					}
					// Wenn keine Startrohstoffe eingetragen wurden
					else{
						echo 'fehlerFirmaEintragen';
					}
				}
				// Wenn kein User eingetragen werden konnte
				else{
					echo 'fehlerUserAnlegen';
				}
			}
		}
	}
}

// WEDER LOGIN NOCH REGISTRIERUNG
else{
	echo 'fehler';
}
?>