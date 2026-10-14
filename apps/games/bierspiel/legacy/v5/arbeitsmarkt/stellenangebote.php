<?php $title=""; include '../huelle/header.php'; include '../funktionen/funktionen.php'; ?>

<table class="bordered bg-color-red fg-color-white">
            <thead class="bc-color-gray fg-color-white" width="50%">
                <h2>Ausbildungsmarkt</h2>
                <tr class="fg-color-white">
                        <b>
                    <th class="center fg-color-white">Name</th>
                    <th class="center fg-color-white">Alter</th>
                    <th class="center fg-color-white">Talent</th>
                    <th class="center fg-color-white">Arbeitswert</th>
                    <th class="center fg-color-white">Wert</th>
                        </b>
                </tr>
            </thead>
            <tbody>
<?php 

$anzahlDBEintraege = anfrage("SELECT COUNT(id) FROM arbeitsmarktausbildung");

for ($i=0; $i<$anzahlDBEintraege[0]; $i++){

// Tabelleninhalt abfragen
$result = anfrage("SELECT `Vorname`, `Nachname`, `Alter`, `Arbeitswert`, `Talent` FROM `arbeitsmarktausbildung` LIMIT $i, 1");

// Tabelleninhalt in einer Tabelle ausgeben
	echo "<tr>
		<td>$result[0] . $result[1]</td>
		<td>$result[2]</td>
		<td>$result[4]</td>
		<td>$result[3]</td>
		<td>Leer</td>
	</tr>";	
}

echo "</tbody><tfoot></tfoot></table>";

include '../huelle/footer.php';?>