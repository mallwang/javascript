<?php session_start(); include '../connect.php'; include '../funktionen.php';

// UserId abfragen
$firmaId = $_SESSION['firmaId'];
// Alle Variablen initialisieren
$id = $_POST["id"];
$status = $_POST["status"];
// Wenn Empfänger dann prüfen, ob von Sender bereits gelöscht
if ($status == "empfaenger"){
	// Prüfen, ob von Sender gelöscht
	$geloescht = anfrage("SELECT sender_geloescht FROM pns WHERE id = '$id'");
	// Wenn von Sender bereits gelöscht, dann komplett löschen
	if ($geloescht[0] == 1){
		// Nachricht löschen
		$loeschen = anfrage("DELETE FROM pns WHERE id = '$id'");
		// Wenn löschen erfolgreich war
		if ($loeschen){
			echo "ok";
		}
		// Wenn löschen nicht erfolgreich war
		else{
			echo "fehlerLoeschen";
		}
	}
	// Wenn von Sender noch nicht gelöscht, dann nur empfaenger_geloescht = 1
	else{
		$loeschenEintragen = anfrage("UPDATE pns SET empfaenger_geloescht = '1' WHERE id = '$id'");
		// Wenn eintrag erfolgreich war
		if ($loeschenEintragen){
			echo "ok";
		}
		// Wenn löschen nicht erfolgreich war
		else{
			echo "fehlerLoeschenEintragen";
		}
	}
}
// Sender, also prüfen, ob von Empfänger bereits gelöscht
if ($status == "sender"){
	// Prüfen, ob von Sender gelöscht
	$geloescht = anfrage("SELECT empfaenger_geloescht FROM pns WHERE id = '$id'");
	// Wenn von Empfänger bereits gelöscht, dann komplett löschen
	if ($geloescht[0] == 1){
		// Nachricht löschen
		$loeschen = anfrage("DELETE FROM pns WHERE id = '$id'");
		// Wenn löschen erfolgreich war
		if ($loeschen){
			echo "ok";
		}
		// Wenn löschen nicht erfolgreich war
		else{
			echo "fehlerLoeschen";
		}
	}
	// Wenn von Empfänger noch nicht gelöscht, dann nur sender_geloescht = 1
	else{
		$loeschenEintragen = anfrage("UPDATE pns SET sender_geloescht = '1' WHERE id = '$id'");
		// Wenn eintrag erfolgreich war
		if ($loeschenEintragen){
			echo "ok";
		}
		// Wenn löschen nicht erfolgreich war
		else{
			echo "fehlerLoeschenEintragen";
		}
	}
}
