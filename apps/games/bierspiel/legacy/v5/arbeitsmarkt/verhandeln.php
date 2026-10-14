<?php session_start(); include '../huelle/connect.php'; include '../funktionen/funktionen.php';


// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$firmenId = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");

$gehalt = $_POST["gehalt"];
$id = $_POST["id"];

// pruefen, ob integer und max 5 zeichen
if (is_numeric($gehalt)){
	// aktuelles Gehalt abfragen
	$aktuellesGehalt= anfrage("SELECT gehalt FROM arbeitnehmer WHERE id='$id'");
// 	echo "SELECT gehalt FROM arbeitnehmer WHERE id='$id'";
	// Wenn gebotenes Gehalt groesser als aktuelles Gehalt ist
	if($gehalt>$aktuellesGehalt[0]){
		// Eigenes momentanes Maximalgebot abfragen
		$aktuellesGebot = anfrage("SELECT MAX(gebot) FROM gebote WHERE arbeitgeber_id = '$firmenId[0]' AND arbeitnehmer_id = '$id'");
		if($gehalt <= $aktuellesGebot[0]){
			echo 'gebotGroesser';
		}
		else{
			// Datenbankeintrag des Gebots
			anfrage("INSERT INTO gebote (arbeitnehmer_id, arbeitgeber_id, gebot) VALUES ('$id', '$firmenId[0]', '$gehalt')");
			echo 'ok';
		}
	}
	else{
		echo 'gehaltZuKlein';
	}
}
else{
	echo 'nichtGueltig';
}
?>