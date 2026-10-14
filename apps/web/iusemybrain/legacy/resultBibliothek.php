<?php include 'funktionen.php';

// Datenbankwerte
$dbhost="DB_HOST";
$dbuser="DB_USER";
$dbpassword="DB_PASSWORD";
$dbdatenbank="DB_NAME";

// Datenbankverbindung oeffnen
if(!($verbindung = mysql_connect("$dbhost", "$dbuser", "$dbpassword"))){
	die("Konnte keine Verbindung zur Datenbank herstellen!");
}

// Datenbank auswaehlen
if(!(@mysql_select_db("$dbdatenbank", $verbindung))){
	die("Fehler " . mysql_errno() . ": " . mysql_error());
}

mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");

// Daten abfragen
$kategorie = mysql_real_escape_string($_POST['kategorie']);
$dateiformat = mysql_real_escape_string($_POST['dateiformat']);
$suche = mysql_real_escape_string($_POST['suche']);
$abc = "";

// echo "kat: " . $kategorie . " dat: " . $dateiformat . " suche: " . $suche;
if ($kategorie != "ALL" && $dateiformat != "ALL"){
	$abc = " WHERE KatID = (SELECT KatID FROM bib_kategorie WHERE Bez = '$kategorie') AND ArtID = (SELECT ArtID FROM bib_art WHERE Bez = '$dateiformat')";
}
else{
	if ($kategorie != "ALL")
		$abc = " WHERE KatID = (SELECT KatID FROM bib_kategorie WHERE Bez = '$kategorie')";
	else if ($dateiformat != "ALL")
		$abc = " WHERE ArtID = (SELECT ArtID FROM bib_art WHERE Bez = '$dateiformat')";
}

$result = mysql_query("SELECT $suche FROM bib_buch $abc");
$return = "";
// echo mysql_num_rows($result);
// Wenn ein Ergebnis existiert
if (mysql_num_rows($result) >= 0){
	$return = '	<thead>
		            <tr>
		                <th>Titel</th>
		                <th>Autor</th>
		                <th>Erscheinungsjahr</th>
		            </tr>
		        </thead>
				<tfoot>
					<tr>
						<th>Titel</th>
						<th>Autor</th>
						<th>Erscheinungsjahr</th>
					</tr>
				</tfoot><tbody>';
	while($row = mysql_fetch_array($result)){
		// Start der Tabelle
		$return .= "<tr><td>" . $row['Titel'] . "</td><td>" . $row['Autor'] . "</td><td>" . $row['Jahr'] . "</td></tr>";
	}
	$return .= "</tbody>";
}
else{
	$return = "Es wurde leider kein Ergebnis gefunden.<br>Bitte ver&auml;ndern Sie Ihre Suche.";
}
echo $return;
?>