<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variable initialisieren
$id = $_POST["id"];
$alleGetraenke = $_POST["alleGetraenke"];
$allePreise = $_POST["allePreise"];
// Prüfen, ob Firma auch der Besitzer des Gebäudes ist
$besitzer = anfrage("SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId' AND id = $id");
// Wenn aktueller User auch Besitzer des Gebäudes ist
if ($besitzer[0] == 1){
	// Preisstring trennen
	$preise = explode("|", $allePreise);
	// Getränkestring trennen
	$getraenke = explode("|", $alleGetraenke);
	/*
	echo "UPDATE `getraenkeangebote` SET `preisPils`='$preise[0]', `preisHelles`='$preise[1]', `preisLager`='$preise[2]',
			`preisWeissbier`='$preise[3]', `preisSchwarzbier`='$preise[4]', `preisAltbier`='$preise[5]', `preisKorn`='$preise[6]',
			`preisWhiskey`='$preise[7]', `preisWodka`='$preise[8]', `preisObstbrand`='$preise[9]', `preisRum`='$preise[10]',
			`preisLikeur`='$preise[11]', `preisAlmradler`='$preise[12]', `preisRadler`='$preise[13]', `preisDiesel`='$preise[14]',
			`preisGoass`='$preise[15]', `preisColaweizen`='$preise[16]', `preisRuss`='$preise[17]', `preisSotb`='$preise[18]',
			`preisLiit`='$preise[19]', `preisZombie`='$preise[20]', `preisHurricane`='$preise[21]', `preisCaipirinha`='$preise[22]',
			`preisMojito`='$preise[23]', `preisMineralwasser`='$preise[24]', `preisCola`='$preise[25]', `preisZlimo`='$preise[26]',
			`preisOlimo`='$preise[27]', `preisApfelschorle`='$preise[28]', `preisColamix`='$preise[29]', `preisWeisswein`='$preise[30]',
			`preisRotwein`='$preise[31]', `preisSchaumwein`='$preise[32]', `preisLikeurwein`='$preise[33]', `preisRosewein`='$preise[34]',
			`preisAsaft`='$preise[35]', `preisOsaft`='$preise[36]', `preisKsaft`='$preise[37]', `preisBsaft`='$preise[38]',
			`preisJsaft`='$preise[39]', `preisPsaft`='$preise[40]',
			`pils`='$getraenke[0]', `helles`='$getraenke[1]', `lager`='$getraenke[2]',
			`weissbier`='$getraenke[3]', `schwarzbier`='$getraenke[4]', `altbier`='$getraenke[5]', `korn`='$getraenke[6]', `whiskey`='$getraenke[7]',
			`wodka`='$getraenke[8]', `obstbrand`='$getraenke[9]', `rum`='$getraenke[10]', `likeur`='$getraenke[11]', `almradler`='$getraenke[12]',
			`radler`='$getraenke[13]', `diesel`='$getraenke[14]', `goass`='$getraenke[15]', `colaweizen`='$getraenke[16]', `russ`='$getraenke[17]',
			`sotb`='$getraenke[18]', `liit`='$getraenke[19]', `zombie`='$getraenke[20]', `hurricane`='$getraenke[21]', `caipirinha`='$getraenke[22]',
			`mojito`='$getraenke[23]', `mineralwasser`='$getraenke[24]', `cola`='$getraenke[25]', `zlimo`='$getraenke[26]', `olimo`='$getraenke[27]',
			`apfelschorle`='$getraenke[28]', `colamix`='$getraenke[29]', `weisswein`='$getraenke[30]', `rotwein`='$getraenke[31]', `schaumwein`='$getraenke[32]',
			`likeurwein`='$getraenke[33]', `rosewein`='$getraenke[34]', `asaft`='$getraenke[35]', `osaft`='$getraenke[36]', `ksaft`='$getraenke[37]',
			`bsaft`='$getraenke[38]', `jsaft`='$getraenke[39]', `psaft`='$getraenke[40]' WHERE firma_id = '$firmaId' AND gebaeude_id = '$id'";
	*/
	// Neue Preise und Getränke eintragen
	$daten = anfrage("UPDATE `getraenkeangebote` SET `preisPils`='$preise[0]', `preisHelles`='$preise[1]', `preisLager`='$preise[2]',
			`preisWeissbier`='$preise[3]', `preisSchwarzbier`='$preise[4]', `preisAltbier`='$preise[5]', `preisKorn`='$preise[6]',
			`preisWhiskey`='$preise[7]', `preisWodka`='$preise[8]', `preisObstbrand`='$preise[9]', `preisRum`='$preise[10]',
			`preisLikeur`='$preise[11]', `preisAlmradler`='$preise[12]', `preisRadler`='$preise[13]', `preisDiesel`='$preise[14]',
			`preisGoass`='$preise[15]', `preisColaweizen`='$preise[16]', `preisRuss`='$preise[17]', `preisSotb`='$preise[18]',
			`preisLiit`='$preise[19]', `preisZombie`='$preise[20]', `preisHurricane`='$preise[21]', `preisCaipirinha`='$preise[22]',
			`preisMojito`='$preise[23]', `preisMineralwasser`='$preise[24]', `preisCola`='$preise[25]', `preisZlimo`='$preise[26]',
			`preisOlimo`='$preise[27]', `preisApfelschorle`='$preise[28]', `preisColamix`='$preise[29]', `preisWeisswein`='$preise[30]',
			`preisRotwein`='$preise[31]', `preisSchaumwein`='$preise[32]', `preisLikeurwein`='$preise[33]', `preisRosewein`='$preise[34]',
			`preisAsaft`='$preise[35]', `preisOsaft`='$preise[36]', `preisKsaft`='$preise[37]', `preisBsaft`='$preise[38]',
			`preisJsaft`='$preise[39]', `preisPsaft`='$preise[40]',
			`pils`='$getraenke[0]', `helles`='$getraenke[1]', `lager`='$getraenke[2]',
			`weissbier`='$getraenke[3]', `schwarzbier`='$getraenke[4]', `altbier`='$getraenke[5]', `korn`='$getraenke[6]', `whiskey`='$getraenke[7]',
			`wodka`='$getraenke[8]', `obstbrand`='$getraenke[9]', `rum`='$getraenke[10]', `likeur`='$getraenke[11]', `almradler`='$getraenke[12]',
			`radler`='$getraenke[13]', `diesel`='$getraenke[14]', `goass`='$getraenke[15]', `colaweizen`='$getraenke[16]', `russ`='$getraenke[17]',
			`sotb`='$getraenke[18]', `liit`='$getraenke[19]', `zombie`='$getraenke[20]', `hurricane`='$getraenke[21]', `caipirinha`='$getraenke[22]',
			`mojito`='$getraenke[23]', `mineralwasser`='$getraenke[24]', `cola`='$getraenke[25]', `zlimo`='$getraenke[26]', `olimo`='$getraenke[27]',
			`apfelschorle`='$getraenke[28]', `colamix`='$getraenke[29]', `weisswein`='$getraenke[30]', `rotwein`='$getraenke[31]', `schaumwein`='$getraenke[32]',
			`likeurwein`='$getraenke[33]', `rosewein`='$getraenke[34]', `asaft`='$getraenke[35]', `osaft`='$getraenke[36]', `ksaft`='$getraenke[37]',
			`bsaft`='$getraenke[38]', `jsaft`='$getraenke[39]', `psaft`='$getraenke[40]' WHERE firma_id = '$firmaId' AND gebaeude_id = '$id'");
	// Wenn Update erfolgreich war
	if ($daten){
		echo "ok";
	}
	// Wenn Update nicht erfolgreich war
	else{
		echo "fehlerDateneintrag";
	}
}
// Wenn aktueller User nicht Besitzer des Gebäudes ist
else{
	echo "nichtBesitzer";
}