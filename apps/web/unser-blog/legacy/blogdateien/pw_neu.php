<?php $title="Passwort &auml;ndern"; include ("header.php");
// Werte von Formular übernehmen
$passwort_alt = htmlentities($_POST["passwort_alt"]);
$passwort_neu1 = htmlentities($_POST["passwort_neu1"]);
$passwort_neu2 = htmlentities($_POST["passwort_neu2"]);
// Weitere Variablen deklarieren und initialisieren
$id = id($_SESSION['nickname']);
$nickname = $_SESSION['nickname'];
$email = email($id);
// Prüfung, ob alle Felder ausgefüllt sind
//if($passwort_alt == "" OR $passwort_neu1 == "" OR $passwort_neu2 == ""){
//	// Mitteilung auf der Homepage
//	echo "
//		<div align='center'>
//		    <h2>PASSWORT &Auml;NDERN</h3>
//		    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
//		</div>
//		<div align='center'>
//			<table class='farbeTable'>
//				<td align='center'>
//				<br>Eingabefehler. Bitte alle Felder korrekt ausf&uuml;llen!<br><br>
//				<a href='pw_aendern.php'>Zur&uuml;ck</a><br><br>
//				</td>
//			</table>
//		</div>
//	";
//}// end if
//else{
	// Passwort aus Datenbank holen
	$abfrage = getProperty('passwort', 'user', 'name', $nickname);
	// Eingegebenes altes Passwort md5-Verschlüsseln
	$passwort_alt_1 = md5($passwort_alt);
	// Passwort aus DB mit eingegebenem alten Passwort vergleichen
	if($abfrage[0] == $passwort_alt_1){
		// Wenn beide neuen Passwörter identisch sind
		if($passwort_neu1 == $passwort_neu2){
			// Neues Passwort md5-verschlüsseln und in die DB schreiben
			$passwort_neu = md5($passwort_neu1);
			updateProperty('user','passwort', $passwort_neu, 'id', $id);
			// Daten für E-Mail Nachricht festlegen
			$empfaenger = $email;
			$absender = "From: Unser Blog <MarkusAllwang@gmx.de>";
			$betreff = "Änderung deines Kennwortes";
			$header  = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$header .= "From: $absender\r\n";
			$header .= "Reply-To: $absender\r\n";
			// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
			$header .= "X-Mailer: PHP ". phpversion();
			$text = "
			<html>							    
				  <body>
					    <div align='center'>
					    <table>
					    Hallo $nickname,<br><br>
					    du hast erfolgreich dein Kennwort ge&auml;ndert.<br>
					    Es lautet nun: $passwort_neu1<br>
					    </div>
					    <br><br>
				  </body>
			</html>";
			// Mail versenden
			mail($empfaenger, $betreff, $text, $header);
			// Mitteilung auf der Homepage
			echo "
				<div align='center'>
					    <h2>PASSWORT &Auml;NDERN</h3>
					    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
				</div>
				<div align='center'>
					<table class='farbeTable'>
						<td align='center'>
							<br>Dein Passwort wurde erfolgreich ge&auml;ndert.<br>
							Dir wurde soeben eine Best&auml;tigungsmail an $email gesendet.<br><br>
							<a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
						</td>
					</table>
				</div>
			";
		}// end if
		// Wenn Passwörter nicht identisch sind
		else{
			// Mitteilung auf der Homepage
			echo "
				<div align='center'>
				    <h2>PASSWORT &Auml;NDERN</h3>
				    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
				</div>
				<div align='center'>
					<table class='farbeTable'>
						<td align='center'>
						<br>Die eingegebenen Passw&ouml;rter stimmen nicht &uuml;berein!<br><br>
						<a href='pw_aendern.php'>Zur&uuml;ck</a><br><br>
						</td>
					</table>
				</div>
			";
		}// end else
	}// end if
	// Wenn das alte Passwort nicht korrekt ist
	else{
		// Mitteilung auf der Homepage
			echo "
				<div align='center'>
				    <h2>PASSWORT &Auml;NDERN</h3>
				    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
				</div>
				<div align='center'>
					<table class='farbeTable'>
						<td align='center'>
						<br>Dein altes Passwort ist nicht korrekt!<br><br>
						<a href='pw_aendern.php'>Zur&uuml;ck</a><br><br>
						</td>
					</table>
				</div>
			";
	}// end else
//}// end else
include ("footer.php");
?>