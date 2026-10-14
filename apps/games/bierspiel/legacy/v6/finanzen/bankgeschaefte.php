<?php session_start(); include '../connect.php'; include '../funktionen.php';

// Firmen Id
$firmenId = $_SESSION['firmaId'];
// Übergabewerte auslesen
$kontostandNeu = $_POST["kontostand"];
$kreditNeu = $_POST["kredit"];
// Tabelle Finanzen updaten
$finanzenEintragen = mysql_query("UPDATE finanzen SET konto = '$kontostandNeu', kredit = '$kreditNeu' WHERE firma_id = '$firmenId'");
// Wenn Eintrag erfolgreich war
if ($finanzenEintragen){
	echo 'ok';
}
// Wenn Eintrag nicht erfolgreich war
else{
	echo 'fehlerEintrag';
}