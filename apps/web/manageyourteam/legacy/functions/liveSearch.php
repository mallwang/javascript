<?php
require_once '../inc/DB.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Variablen Deklarieren
$hint = "";
$suche = mysql_real_escape_string ($_GET["q"]);
// SQL Abfrage
$sql = "SELECT `clubName` FROM `clubtab` WHERE clubName LIKE '%" . $suche . "%' ORDER BY `clubName`";
// Anfrage erstellen
$alleVereine = mysql_query($sql);
// Wenn es keinen Eintrag gibt
if (mysql_num_rows($alleVereine ) == 0)
	$hint = "Kein Verein gefunden!";
// Wenn mindestens ein Eintrag vorhanden ist
else {
	// Array wird in einer Schleife gefüllt
	while ($row = mysql_fetch_array($alleVereine)){
		if ($hint == "")
			$hint = "<a class='fg-black' href='#' onclick='openNewWindow(" . '"' . $row ['clubName'] . '"' . ")'>" . $row ['clubName'] . "</a>";
		else
			$hint = $hint . "<br><a class='fg-black' href='#' onclick='openNewWindow(" . '"' . $row ['clubName'] . '"' . ")'>" . $row ['clubName'] . "</a>";
	}
}
echo $hint;
?>