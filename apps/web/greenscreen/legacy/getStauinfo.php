<?php
// URL auslesen
$datastream = file_get_contents("http://www.freiefahrt.info/verkehr/bayern?search=N%C3%BCrnberg");
$anzahlMeldungen = strpos($datastream, "<br/><br/><br/><b>");
// Anzahl an Staumeldungen abfragen
$stelle1 = preg_replace( '/[^0-9]/', '', substr($datastream, $anzahlMeldungen, 25));
// Es gibt Staumeldungen
if ($stelle1 != 0){
    // Prüfen, wo Tabelle mit Staudaten anfängt und aufhört
    $pos1 = strpos($datastream, "<table class='ffList' id='ffList' cellpadding=0 cellspacing=0>");
    $pos2 = strpos($datastream, "</td></tr></table></p>");
    // Tabelle ausschneiden
    $table = substr($datastream, $pos1, ($pos2 - $pos1 + 18));
    // Rückgabe der Tabelle
    echo "<h4>" . $stelle1 . " Staumeldungen für Nürnberg gefunden.</h4>";
    echo $table;
}
// Es gibt keine Staumeldungen
else{
    echo "<h4>Keine Staumeldungen vorhanden.</h4>";
}
?>