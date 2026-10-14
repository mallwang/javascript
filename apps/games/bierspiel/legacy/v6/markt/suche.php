<?php include '../connect.php';
	
	// String für die abzufragenden alkoholischen Getränke
	$alkGetr = "";
	$bier = "";
	$pils = "";
	
	
	// Wenn alkoholisches Getränk ausgewählt, dem String hinzufügen
	if ($_POST["a1"] == "true"){
		$alkGetr = $alkGetr . "art = 'bier'";
	}
	if ($_POST["a2"] == "true"){
		if ($_POST["a1"] == "true"){
			$alkGetr = $alkGetr . "OR art = 'pils'";
		}
		else{
			$alkGetr = $alkGetr . "art = 'pils'";
		}
	}
	// Wenn $alkGetr ausgewählt wurden
	if (strlen($alkGetr) == 0){
		$alkGetr = "1";
	}
	
	// Mysql Abfrage wird gespeichert mit den Notwendigen Parameter
	$sql = "SELECT * FROM alkGetr WHERE " . $alkGetr;
	
	//echo $sql;
	
	// Mysql Abfrage wird durchgeführt
	$result = mysql_query($sql);
	
	echo "	<table class='table'>
				<thead>
					<tr>
						<th>Art</th>
						<th>AnbieterID</th>
						<th>Menge</th>
						<th>Preis</th>
						<th>Kaufen</th>
					</tr>
				</thead>
				<tbody>";
	
	// Ergebnis wird ausgegeben mit Zeilenumbruch
	while($row = mysql_fetch_object($result)){
		echo "	<tr>
					<td>$row->art</td>
					<td>$row->anbieter_id</td>
					<td>$row->menge</td>
					<td>$row->preis</td>
					<td>
						<div class='input-append'>
							<input onkeyup='pruefeMenge(this.id, this.value, $row->menge)' placeholder='Menge' style='height: 20px;' class='marktInput' maxlength='7' id='$row->id' type='text'>
							<button disabled='disabled' onclick='kaufen(this.id, $row->preis, \"bier\", $row->menge)' id='btn$row->id' class='btn' type='button' title='Kaufen'><i class='icon-shopping-cart'></i></button>
							<img style='display: none;' id='wait$row->id' src='/img/loading.gif'/>
						</div>
					</td>
				</tr>";
	}
	
	echo "</tbody></table>";
?>