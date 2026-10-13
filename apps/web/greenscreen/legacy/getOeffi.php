<?php

// Aktuelle Abfahrtszeiten abfragen
$data = file_get_contents("http://www.vgn.de/komfortauskunft/haltestelle/ergebnis/?type_dm=any&nameInfo_dm=3001351&stateless_dm=1&dmLineSelectionAll=1&mode=direct&limit=20&sessionID=0");
$startTabelle = strpos($data, "</colgroup>") + 11;
$endTabelle = strpos($data, '<div class="EFA_Eingabebereich">');
// Tabelle extrahieren
$table = "<table>" . substr($data, $startTabelle, ($endTabelle - $startTabelle - 8));
$table = str_replace(' class="Liste letzte"', '', $table);
$table = str_replace(' class="Liste alt"', '', $table);
$table = str_replace(' class="Liste"', '', $table);
$teile = explode("<tr>", $table);
$start = "<tr>" . $teile[1];
$a = "<tr><td>" . getTextBetweenTags($teile[2], "td")[0] . "</td><td>" . '<img src="http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/Bus.png"></td><td>' . getTextBetweenTags($teile[2], "td")[1] . "</td><td>" . getTextBetweenTags($teile[2], "td")[2] . "</td></tr>";
$b = "<tr><td>" . getTextBetweenTags($teile[3], "td")[0] . "</td><td>" . '<img src="http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/Bus.png"></td><td>' . getTextBetweenTags($teile[3], "td")[1] . "</td><td>" . getTextBetweenTags($teile[3], "td")[2] . "</td></tr>";
$c = "<tr><td>" . getTextBetweenTags($teile[4], "td")[0] . "</td><td>" . '<img src="http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/Bus.png"></td><td>' . getTextBetweenTags($teile[4], "td")[1] . "</td><td>" . getTextBetweenTags($teile[4], "td")[2] . "</td></tr>";
$d = "<tr><td>" . getTextBetweenTags($teile[5], "td")[0] . "</td><td>" . '<img src="http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/Bus.png"></td><td>' . getTextBetweenTags($teile[5], "td")[1] . "</td><td>" . getTextBetweenTags($teile[5], "td")[2] . "</td></tr>";
$e = "<tr><td>" . getTextBetweenTags($teile[6], "td")[0] . "</td><td>" . '<img src="http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/Bus.png"></td><td>' . getTextBetweenTags($teile[6], "td")[1] . "</td><td>" . getTextBetweenTags($teile[6], "td")[2] . "</td></tr>";
$return = "<h4>Ernst-Heinkel-Weg</h4><table>" . $start . $a . $b . $c . $d . $e . "</table>";

// Aktuelle Abfahrtszeiten abfragen
$data2 = file_get_contents("http://www.vgn.de/komfortauskunft/haltestelle/?type_dm=any&nameInfo_dm=3001320&stateless_dm=1&dmLineSelectionAll=1&mode=direct&limit=20&sessionID=0");
$startTabelle2 = strpos($data2, "</colgroup>") + 11;
$endTabelle2 = strpos($data2, '<h2>Haltestellen in der Umgebung</h2>');
$table2 = "<table>" . substr($data2, $startTabelle2, ($endTabelle2 - $startTabelle2 - 8));
$table2 = str_replace("/ib/site/elements/efa/means/u_bahn.gif", "http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/UBahn.png", $table2);
$table2 = str_replace(' class="Liste letzte"', '', $table2);
$table2 = str_replace(' class="Liste alt"', '', $table2);
$table2 = str_replace(' class="Liste"', '', $table2);
$teile2 = explode("<tr>", $table2);
$return2 = "<h4>Ziegelstein</h4><table><tr>" . $teile2[1] . "</tr>";
for ($i = 0; $i < count($teile2); $i++) {
    // Wenn an zweiter Stelle ein "U2" steht
    if (count(getTextBetweenTags($teile2[$i], "td")) == 2 && getTextBetweenTags($teile2[$i], "td")[1] == "U2"){
       $aa = str_replace("(" . getTextBetweenChar($teile2[$i], "(", ")")[0] . ")", "", $teile2[$i]);
       $ziel = trim(substr($aa, strpos($aa, ' ', strpos($aa, 'Nürnberg')+1)));
       $return2 = $return2 . "<tr><td>" . getTextBetweenTags($teile2[$i], "td")[0] . "</td><td>" . '<img src="http://www.vgn.de/ib/site/libraries/OpenLayers-2.12/symbole_menue/UBahn.png"></td><td>' . getTextBetweenTags($teile2[$i], "td")[1] . "</td><td>Nürnberg " . $ziel . "</td></tr>";  
    }
}
echo $return . $return2;

function getTextBetweenTags($string, $tagname) {
    $pattern = "/<$tagname>(.*?)<\/$tagname>/";
    preg_match_all($pattern, $string, $matches);
    return $matches[1];
}

function getTextBetweenChar($string, $char1, $char2) {
    $pattern = "/\((.*)\)/";
    preg_match_all($pattern, $string, $matches);
    return $matches[1];
}