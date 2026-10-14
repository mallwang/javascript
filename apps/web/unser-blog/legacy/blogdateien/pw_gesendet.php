<?php session_start(); include ("functions.php"); include ("connect.php");

$meldung = "";

// Werte von Formular übernehmen
$nickname = htmlentities($_POST["nickname"]);
$email = strtolower(htmlentities($_POST["email"]));

// Prüfung ob alle Felder ausgefüllt sind
//if($nickname == "" OR $email == "")
//	{
//		$meldung = "
//		<div align='center'>
//			  <table class='farbeTable'><br><br>
//					<td align='center'>
//						<div>
//							<br><br>Eingabefehler. Bitte alle Felder korrekt ausf&uuml;llen!<br>
//							<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a>
//						</div>
//					</td>
//			  </table> 
//		</div>
//		";
//	}// end if
//
//else
//{
	// Suche nach Einträgen in tabelle 'user'
	$abfrage = getProperty('email', 'user', 'name', $nickname);
	$email_kontrolle = $abfrage[0];
	
	/*$abfrage = "SELECT email FROM user WHERE nickname = '$nickname' LIMIT 1";
	$ergebnis = mysql_query($abfrage);
	$row = mysql_fetch_object($ergebnis);
	$email_kontrolle = $row->email;*/
	
	// Wenn eingegebene E-Mail Adresse mit derjenigen aus db 'user' übereinstimmt
	if ($email_kontrolle == $email)
	{
		// Neues Passwort per Zufallsalgorithmus erstellen und verschlüsseln
		$random_pw = substr(md5(microtime()), mt_rand(0, 24), 8);
		$neues_pw = md5($random_pw);
	
		// Daten für E-Mail Nachricht festlegen
		$empfaenger = $email;
		$absender = "From: Unser Blog <MarkusAllwang@gmx.de>";
		$betreff = "Neues Kennwort";
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
				    <table style='background-image: url('images/hintergrund.jpg');'>
				    Hallo $nickname,<br><br>
				    für dich wurde ein neues Kennwort gesetzt.<br>
				    Es lautet: $random_pw<br>
				    Du kannst dich damit sofort einloggen:
				    <a href='http://www.unser-source.co.de/index.php'>http://www.unser-source.co.de/index.php</a><br>
				    </div>
				    <br><br>
			  </body>
		</html>";
		// Mail versenden
		mail($empfaenger, $betreff, $text, $header);
		
		// Neues Passwort eintragen
		updateProperty('user', 'passwort', $neues_pw, 'email', $email);
		//$eintragen = mysql_query("UPDATE user SET passwort ='$neues_pw' WHERE email = '$email'") or die ("Kein Eintrag neues PW!");
		
		// Bestätigungsnachricht
		$meldung = "
		<div align='center'>
			  <table class='farbeTable'><br><br>
					<td align='center'>
						<div>
							<h3 class='farbe_grau'><br>Ein neues Passwort wurde dir per E-Mail an $email gesendet </h3><br><br>
							<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck zur Hauptseite</a><br><br>
						</div>
					</td>
			  </table> 
		</div>
		";
	}// end if
	
	else
	{
		$meldung = "
		<div align='center'>
			  <table class='farbeTable'><br><br>
					<td align='center'>
						<div>
							<h3 class='farbe_grau'><br>Der Nickname stimmt nicht mit der angegebenen E-Mail-Adresse überein!</h3><br><br>
							<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
						</div>
					</td>
			  </table> 
		</div>
		";
	}// end else
//}// end else
mysql_close();
include ("header_outside.php");
echo $meldung;
include ("footer_outside.php");
?>