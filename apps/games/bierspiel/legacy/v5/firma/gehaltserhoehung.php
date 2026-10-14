<?php session_start(); include '../huelle/connect.php'; include '../funktionen/funktionen.php';

// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$firmenId = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");

$id = $_POST["id"];
$gehaltNeu = $_POST["gehalt"];
$grundwert = $_POST["grundwert"];

// aktuelles Gehalt abfragen
$werteAN = anfrage("SELECT gehalt, zufriedenheit FROM arbeitnehmer WHERE id = '$id'");
// verdientesGehalt berechnen
//$grundgehalt = grundgehaltBerechnen($id, $grundwert);
// Wenn das Gehalt eine Zahlenfolge ist
if (is_numeric($gehaltNeu)){
	// Wenn das gebotene Gehalt grφίer als das aktuelle ist
	if ($gehaltNeu>0){
		// Veraenderung der Zufriedenheit berechnen
		$zufrVeraender = neueZufr($id, $gehaltNeu, $grundwert);
		echo "Veraenderung Zufriedenheit: " . $zufrVeraender;
		// echo "GehNeu: " . $gehaltNeu . "  GehAkt: " . $gehaltAkt[0] . "  Faktor: " . $faktor  . "  Staerkeerh.: " . $zufrErhoehung;
		// Neue Zufriedenheit berechnen
		$zufriedenheitNeu = getNeueZufr($id, $zufrVeraender, $werteAN[1]);
		// Neuen Arbeitswert berechnen
		$arbeitswertNeu = berechneArbeitswert($id, $zufriedenheitNeu);
		// Neues Gehalt, Zufriedenheit und Arbeitswert eintragen
		$zufrArbWerteintragen = anfrage("UPDATE arbeitnehmer SET gehalt='$gehaltNeu', zufriedenheit = '$zufriedenheitNeu', arbeitswert = '$arbeitswertNeu' WHERE id = '$id'");
		echo 'ok';
	}
	// Gebotene Gehalt ist kleiner als null
	else{
		echo 'gehaltZuKlein';
	}
}
// Gehalt ist keine Zahl
else{
	echo 'keineZahl';
}

// // aktuellen Arbeitgeber feststellen
// $aktAG = anfrage("SELECT arbeitgeber FROM arbeitnehmer WHERE id='$id'");
// // wenn bereits jemand diesen AG eingestellt hat
// if($aktAG[0]=='-1'){
// 	// Arbeitnehmer updaten
// 	$neuerAG = anfrage("UPDATE arbeitnehmer SET arbeitgeber='$firmenId[0]' WHERE id='$id'");
// 	// neuen Arbeitnehmer erstellen
// 	fuegeANHinzu(1, 1);
// 	echo 'ok';
// }
// else{
// 	echo 'schonEingestellt';
// }

?>