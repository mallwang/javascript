<?php $title="E-Mail &auml;ndern"; include ("header.php");
// Werte aus Formular übernehmen
$email_neu1 = htmlentities($_POST["email_neu1"]);
$email_neu2 = htmlentities($_POST["email_neu2"]);
// Weitere Variablen deklarieren und initialisieren
$id = id($_SESSION['nickname']);
$nickname = $_SESSION['nickname'];
$email = email($id);
// Prüfung, ob alle Felder ausgefüllt sind
if($email_neu1 == "" OR$email_neu2 == ""){
	// Mitteilung auf der Homepage
	echo "
		<div align='center'>
		    <h2>E-MAIL ADRESSE &Auml;NDERN</h3>
		    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
		</div>
		<div align='center'>
			<table class='farbeTable'>
				<td align='center'>
				<br>Eingabefehler. Bitte alle Felder korrekt ausf&uuml;llen!<br><br>
				<a href='email_aendern.php'>Zur&uuml;ck</a><br><br>
				</td>
			</table>
		</div>
	";
}// end if
else{
	// E-Mail aus Datenbank holen
	$abfrage_email = getProperty('email', 'user', 'email', $email_neu1);
	// Wenn beide E-Mail Adressen übereinstimmen
	if($email_neu1 == $email_neu2){
		// Wenn E-Mail Adresse noch nicht vorhanden
		if($abfrage_email[0] == ""){
			// Wenn E-Mail Adresse gültig ist
			if(check_email($email_neu1)){
				// E-Mail Adresse in Kleinbuchstaben umwandeln und in DB eintragen
				$email_klein = strtolower($email_neu1);
				updateProperty('user', 'email', $email_klein, 'id', $id);
				// Allgemeine E-Mail Daten festlegen
				$absender = "From: Unser Blog <MarkusAllwang@gmx.de>";
				$header  = "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$header .= "From: $absender\r\n";
				$header .= "Reply-To: $absender\r\n";
				// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
				$header .= "X-Mailer: PHP ". phpversion();
				$betreff = "Aenderung deiner E-Mail Adresse";				
				// Daten für E-Mail Nachricht an die neue E-Mail Adresse festlegen
				$empfaenger_neu = $email_klein;
				$text_neu = "
				<html>							    
					  <body>
						    <div align='center'>
						    <table>
						    Hallo $nickname,<br><br>
						    du hast erfolgreich deine E-Mail Adresse von $email auf $email_klein ge&auml;ndert.<br>
						    </div>
						    <br><br>
					  </body>
				</html>";
				// Daten für E-Mail Nachricht an die alte E-Mail Adresse festlegen
				$empfaenger_alt = $email;
				$text_alt = "
				<html>							    
					  <body>
						    <div align='center'>
						    <table>
						    Hallo $nickname,<br><br>
						    deine E-Mail Adresse wurde soeben von $email auf $email_klein ge&auml;ndert.<br>
						    Sollte diese &Auml;nderung nicht in deinem Intersse sein, kannst du auf diese E-Mail antworten.<br>
						    </div>
						    <br><br>
					  </body>
				</html>";
				// Mail an die neue Adresse versenden
				mail($empfaenger_neu, $betreff, $text_neu, $header);
				// Mail an die alte Adresse versenden
				mail($empfaenger_alt, $betreff, $text_alt, $header);
				// Mitteilung auf der Homepage
				echo "
					<div align='center'>
						<h2>E-MAIL ADRESSE &Auml;NDERN</h3>
						<a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
					</div>
					<div align='center'>
						<table class='farbeTable'>
							<td align='center'>
								<br>Deine E-Mail Adresse wurde erfolgreich von $email auf $email_klein ge&auml;ndert.<br><br>
								<br>Dir wurde diesbez&uuml;glich eine Best&auml;tigungsmail gesendet.<br><br>
								<a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
							</td>
						</table>
					</div>
				";
			}// end if
			// Wenn E-Mail Adresse nicht gültig ist
			else{
				echo "
					<div align='center'>
						<h2>E-MAIL ADRESSE &Auml;NDERN</h3>
						<a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
					</div>
					<div align='center'>
						<table class='farbeTable'>
							<td align='center'>
								<br>Das ist keine g&uuml;ltige E-Mail Adresse!<br><br>
							<a href='email_aendern.php'>Zur&uuml;ck</a><br><br>
							</td>
						</table>
					</div>
				";				
			}// end else
		}// end if
		// Wenn E-Mail Adresse bereits vorhanden ist
		else{
			echo "
				<div align='center'>
					<h2>E-MAIL ADRESSE &Auml;NDERN</h3>
					<a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
				</div>
				<div align='center'>
					<table class='farbeTable'>
						<td align='center'>
							<br>Die eingegebene E-Mail Adresse ist bereits vorhanden!<br><br>
						<a href='email_aendern.php'>Zur&uuml;ck</a><br><br>
						</td>
					</table>
				</div>
			";
		}// end else
	}// end if
	// Wenn beide E-Mail Adressen nicht übereinstimmen
	else{
		echo "
			<div align='center'>
			    <h2>E-MAIL ADRESSE &Auml;NDERN</h3>
			    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
			</div>
			<div align='center'>
				<table class='farbeTable'>
					<td align='center'>
						<br>Die eingegebenen E-Mail Adressen stimmen nicht &uuml;berein!<br><br>
					<a href='email_aendern.php'>Zur&uuml;ck</a><br><br>
					</td>
				</table>
			</div>
		";
	}// end else
}// end else
include ("footer.php");
?>