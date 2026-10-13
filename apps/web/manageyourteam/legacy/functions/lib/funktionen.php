<?php 
// Sicheres Verschlüsseln
function cryptPassword($input){
	$rounds = 9;
	$salt = "";
	// Range erstellt ein Array mit Zeichen von bis...
	// Diese werden mit array_merge zusammengef�gt
	$saltChars = array_merge(range('A','Z'), range('a', 'z'), range(0, 9));
	for($i = 0; $i < 22; $i++){
		$salt .= $saltChars[array_rand($saltChars)];
	}
	// Verwenden von Blowfish ($2y$)
	return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
}

// Mail versenden
function sendEmail($sender, $receiver, $mailtext, $subject) {
	require_once('PHPMailerAutoload.php');
	// Neue Instanz erstellen
	$mailer = new PHPMailer(true);
	// Verbindung aufbauen
	$mailer->IsSMTP();
	$mailer->SMTPAuth = true;
	$mailer->SMTPSecure = 'ssl';
	$mailer->Port = 465;
	$mailer->Host = "SMTP_HOST";
	$mailer->Username = "SMTP_USER";
	$mailer->Password = "SMTP_PASSWORD";
	// Senderinformationen
	$mailer->Sender = $sender;
	$mailer->AddReplyTo($sender, 'Manage Your Team');
	$mailer->SetFrom($sender, 'Manage Your Team');
	// Empfänger eintragen
	$teile = explode(";", $receiver);
	reset ($teile);
	while (list ($key, $val) = each ($teile)) {
		$mailer->AddBCC($val);
	}
	/*
	// Anhänge anhängen
	$teile = explode(";", $anhang);
	reset ($teile);
	while (list ($key, $val) = each ($teile)) {
		$dateiname = explode("/", $val);
		$dateiname = array_pop($dateiname);
		// Anhängen
		$mailer->AddAttachment($val, $dateiname);
	}
	*/
	// Als HTML senden
	$mailer->IsHTML(true);
	$mailer->CharSet = 'utf-8';
	$mailer->Subject = $subject;
	$mailer->Body = $mailtext;
	$mailer->AltBody = $mailtext;
	// Falls Senden fehlschlug
	if(!$mailer->Send())
		exit;
	// Felder zurücksetzen
	$mailer->ClearAddresses();
}
?>