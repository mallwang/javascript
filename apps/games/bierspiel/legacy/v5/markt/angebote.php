<?php $title="NEUE"; include("../huelle/header.php");

$rangliste = "SELECT bezirk, anbieter_id, sorte, menge, einstelldatum FROM marktplatz ORDER BY sorte ASC";
$rangliste2 = mysql_query($rangliste);

?>
<div align="center">
<br>        
<table class="bordered bg-color-red fg-color-white">
            <thead class="bc-color-gray fg-color-white" width="50%">
                <h2>Markt&uuml;bersicht</h2>
                <tr class="fg-color-white">
                        <b>
                    <th class="center fg-color-white">Anbieter</th>
                    <th class="center fg-color-white">Sorte</th>
                    <th class="center fg-color-white">Menge</th>
                    <th class="center fg-color-white">Einstelldatum</th>
                    <th class="center fg-color-white">Bezirk</th>
                        </b>
                </tr>
            </thead>
            <tbody>
<?php
while($row = mysql_fetch_object($rangliste2))
{
$bezirk = $row->bezirk;
$anbieter_abfrage = mysql_query("SELECT firmenname from firmendaten WHERE id = '$row->anbieter_id'");
$anbieter = mysql_result($anbieter_abfrage, 0);
$sorte = $row->sorte;
$menge = $row->menge;
$einstelldatum = $row->einstelldatum;

echo "<tr><td>$anbieter</td>
    <td>$sorte</td>
    <td>$menge</td>
    <td>$einstelldatum</td>
    <td>$bezirk</td>
    </tr>";
}

echo "</tbody><tfoot></tfoot></table><br>";
echo "<a href='#' onclick='oeffneFenster(neuesAngebot.php); return false'>neues Angebot erstellen</a></div>";
?>
