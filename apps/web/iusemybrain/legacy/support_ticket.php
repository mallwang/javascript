<?php session_start(); include 'connect.php'; include 'funktionen.php';

// Prüfen, ob alle Felder ausgefüllt sind
if (!($_POST["betreff"] == "" || $_POST["email"] == "" || $_POST["text"] == "")){
	$email = $_POST["email"];
	// Wenn korrekte Email Adresse eingegeben wurde
	if(preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$^", $email)){
		$betreff = $_POST["betreff"];
		$text = $_POST["text"];
		// Daten in DB speichern
		$dateneintrag = anfrage("INSERT INTO `support`(`betreff`, `email`, `text`) VALUES ('$betreff', '$email', '$text')");
		// Wenn Datenbankeintrag erfolgreich
		if ($dateneintrag){
			// Mitteilung an Webmaster
			$empfaengerWebmaster = "support@i-use-my-brain.com";
			$absender = "i-use-my-brain.com <info@i-use-my-brain.com>";
			$betreffWebmaster = $betreff;
			$headerWebmaster  = "MIME-Version: 1.0\r\n";
			$headerWebmaster .= "Content-type: text/html; charset=utf-8\r\n";
			$headerWebmaster .= "From: $absender\r\n";
			$headerWebmaster .= "Reply-To: $absender\r\n";
			// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
			$headerWebmaster .= "X-Mailer: PHP ". phpversion();
			$textWebmaster = "
			<!DOCTYPE HTML>
			<html>
				<head>
				</head>
				<body>
					<div>
						Neue Nachricht von $email!<br><br>
						Betreff: $betreff<br>
						Nachricht:<br>
						$text
					</div>
				</body>
			</html>";
			// Mail versenden
			mail($empfaengerWebmaster, $betreffWebmaster, $textWebmaster, $headerWebmaster);
			// Daten für E-Mail Nachricht festlegen
			$empfaenger = $email;
			$absender = "i-use-my-brain.com <support@i-use-my-brain.com>";
			$betreff = "Dein Support-Ticket wurde übermittelt";
			$header  = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=utf-8\r\n  Content-Transfer-Encoding: quoted-printable";
			$header .= "From: $absender\r\n";
			$header .= "Reply-To: $absender\r\n";
			// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
			$header .= "X-Mailer: PHP ". phpversion();
			$text = "
					<html>
						<body>
							<div>
								Hallo,<br><br>
								vielen Dank für deine Nachricht!<br>
								Das Team von i-use-my-brain wird sich um dein Anliegen kümmern.<br><br>
								Sobald sich etwas diesbezüglich getan hat, wirst du informiert.<br>
								Bis bald!<br><br>
								$email schrieb:<br>
								$text
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
	// Keine E-Mail
	else{
		echo 'keineEmail';
	}
}
// Nicht alle Felder ausgefüllt
else{
	echo 'nichtAlleFelder';
}
?>