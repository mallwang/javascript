<?php include("../huelle/connect.php");
//Array mit Daten beladen
$keywords[] = "";
$a = mysql_query("SELECT firmenname FROM firmendaten");
while($row=mysql_fetch_object($a)){
    $keywords[] = $row->firmenname;
}
//Suchbegriff aus der URL per GET filtern
$q=$_GET["q"];
//String-Länge ermitteln und falls groesser 0 -> weiter
if (strlen($q) > 0){
    $b = true;
    $response="<div class='fg-color-green'><b>Dieser Firmenname ist OK!</b></div>";
    foreach($keywords as $keyword_aktuell) {
	//echo $q."<br>";
	//echo $keyword_aktuell."<br>";
	if (strtolower($q)==strtolower($keyword_aktuell)) {
	    $b = false;
	    $response="<div class='fg-color-red'><b>Dieser Firmenname existiert bereits!</b></div>";
	    break;
	}
    }
    echo $response;
}
?>