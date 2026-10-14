<?php session_start(); include '../connect.php'; include '../funktionen.php';

// UserId und User abfragen
$userId = $_SESSION['userId'];
$user = $_SESSION['user'];
// Alle Variablen initialisieren
$betreff = $_POST["betreff"];
$empfaenger = $_POST["empfaenger"];
$text = nl2br($_POST["text"]);
$checked = $_POST["checked"];
// Prüfen ob Empfänger existiert
$empfaengerVorhanden = anfrage("SELECT COUNT(id), id FROM user WHERE nickname = '$empfaenger'");
// Wenn Empfänger vorhanden
if ($empfaengerVorhanden[0] == 1){
	// EmpfängerId festlegen
	$empfaengerId = $empfaengerVorhanden[1];
	// Abfragen wieviele Nachrichten Empfänger in Posteingang
	$anzahlImPosteingang = anfrage("SELECT COUNT(id) FROM pns WHERE zu_id = '$empfaengerId'");
	// Wenn Anzahl < 20
	if ($anzahlImPosteingang[0] < 20){
		// Wenn checked == 0
		if ($checked == 0){
			// Prüfen, wieviele Nachrichten im Postausgang
			$anzahlImPostausgang = anfrage("SELECT COUNT(id) FROM pns WHERE von_id = '$userId' AND sender_geloescht = '0'");
			// Wenn Anzahl < 20
			if ($anzahlImPostausgang[0] < 20){
				// In Tabelle eintragen
				$dateneintrag = anfrage("INSERT INTO `pns`(`betreff`, `von_id`, `von_user`, `zu_id`, `zu_user`, `text`, `sender_geloescht`) 
						VALUES ('$betreff', '$userId', '$user', '$empfaengerId', '$empfaenger', '$text', '$checked')");
				// Wenn dateneintrag erfolgreich
				if ($dateneintrag){
					echo "ok";
				}
				// Wenn dateneintrag nicht erfolgreich
				else{
					echo "fehlerDateneintrag";
				}
			}
			// Wenn Postausgang voll
			else{
				echo "postausgangVoll";
			}
		}
		// Wenn nicht gecheckt
		else{
			// In Tabelle eintragen
			$dateneintrag = anfrage("INSERT INTO `pns`(`betreff`, `von_id`, `von_user`, `zu_id`, `zu_user`, `text`, `sender_geloescht`)
					VALUES ('$betreff', '$userId', '$user', '$empfaengerId', '$empfaenger', '$text', '$checked')");
			// Wenn dateneintrag erfolgreich
			if ($dateneintrag){
				echo "ok";
			}
			// Wenn dateneintrag nicht erfolgreich
			else{
				echo "fehlerDateneintrag";
			}
		}
	}
	// Wenn Posteingang des Empfängers voll
	else{
		echo "posteingangVoll";
	}
}
// Wenn Empfänger nicht vorhanden
else{
	echo "empfaengerNichtExistent";
}