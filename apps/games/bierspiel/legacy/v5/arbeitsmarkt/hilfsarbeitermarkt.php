<?php $title=""; include '../huelle/header.php';

// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$id = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");
// echo $id[0];

?>
<script type="text/javascript">
$(document).ready(function(){
	$('form.checkme').submit(function(){
		iElemA = $("input[name='id']", this).val();
		iElemB = $("input[name='name']", this).val();
		iElemC = $("input[name='gehalt']", this).val();
		var Eingabe = confirm(unescape("M%F6chten Sie " + iElemB + " f%FCr ein Gehalt in H%F6he von " + iElemC + " wirklich einstellen?"));
		if(Eingabe){
			//alert(iElemA);
			$.ajax({
				type:	'POST',
				url:	'einstellen.php',
				data:{id:iElemA},
				success: function(data){
					//alert("inAjax");
					//alert(data);
					if(data=='schonEingestellt'){
						alert(unescape("Ein anderer Arbeitgeber war schneller"));
						document.location='hilfsarbeitermarkt.php';
					}
					else if(data=='ok'){
						alert(unescape("Dieser Arbeitnehmer wurde erfogreich eingestellt"));
						document.location='hilfsarbeitermarkt.php';
	                }
	                //alert(data);
				}
			});
		}
		else{
		}
		return false;
	});
});
</script>
<table class="bordered bg-color-red fg-color-white">
            <thead class="bc-color-gray fg-color-white" width="50%">
                <h2>Hilfskr&auml;ftemarkt</h2>
                <tr class="fg-color-white">
                    <b>
                    <th class="center fg-color-white">Name</th>
                    <th class="center fg-color-white">Alter</th>
                    <th class="center fg-color-white">Talent</th>
                    <th class="center fg-color-white">Grundwert</th>
                    <th class="center fg-color-white">Gehalt</th>
                    <th class="center fg-color-white">Aktion</th>
                    </b>
                </tr>
            </thead>
            <tbody>
<?php 

$anzahlDBEintraege = anfrage("SELECT COUNT(id) FROM arbeitnehmer WHERE `art`='1' AND `arbeitgeber` = '-1' ");

for ($i=0; $i<$anzahlDBEintraege[0]; $i++){

// Tabelleninhalt abfragen
$result = anfrage("SELECT `id`, `vorname`, `nachname`, `alter`, `grundwert`, `talent`, `gehalt`
FROM `arbeitnehmer` WHERE `art`='1' AND `arbeitgeber` = '-1' ORDER BY `grundwert` DESC LIMIT $i, 1");

// Tabelleninhalt in einer Tabelle ausgeben
	echo "<tr>
		<td>$result[1] $result[2]</td>
		<td>$result[3]</td>
		<td>$result[5]</td>
		<td>$result[4]</td>
		<td>$result[6]&euro;</td>
		<td>
			<form method='post' class='checkme' action=''>
				<input style='display: none;' name='id' value='$result[0]'>
				<input style='display: none;' name='name' value='$result[1] $result[2]'>
				<input style='display: none;' name='gehalt' value='$result[6]&euro;'>
	            <button class='bg-color-red fg-color-blue' type='submit'><h4 class='fg-color-blue'>Arbeitnehmer einstellen</h4></button><br>
			</form>
		</td>
	</tr>";	
}

echo "</tbody><tfoot></tfoot></table>";

include '../huelle/footer.php';?>