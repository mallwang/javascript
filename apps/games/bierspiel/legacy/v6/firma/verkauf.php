<?php $title="Verkaufsgeb&auml;ude"; include '../sessionpruefung.php'; include '../geheim.php';
// FirmenId abfragen
$firmaId = $_SESSION['firmaId'];
// Abfrage aller Gebeäude des aktuellen Users
$anzahlGebaeude = mysql_query("SELECT * FROM gebaeude g, getraenkeangebote ge, getraenkelager gl WHERE g.firma_id = '$firmaId' AND g.kategorie = 'v' AND g.id = ge.gebaeude_id AND g.firma_id = ge.firma_id AND g.firma_id = gl.firma_id AND g.id = gl.gebaeude_id");
// Abfrage aller Gebäude
$alleGebaeude = anfrage("SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId' AND kategorie = 'v'");
// Aktuelle Preise abfragen
$preisanfrage = mysql_query("SELECT * FROM preise");
$preis = mysql_fetch_array($preisanfrage, MYSQL_NUM);
?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-info"><b>Diese Seite befindet sich momentan im Aufbau!</b></div>
<!-- INHALTSBESCHREIBUNG
<div class="inhalt" align="center">
	<div align="center">
		<img style="height: 80px;" src="/img/bauarbeiter.png">
	</div>
    <h2>&Uuml;bersicht aller Verkaufsgeb&auml;ude</h2><br>
  	<b>Folgender Inhalt:</b><br>
  	> Kurze &Uuml;bersicht (z.B. Anzahl Gastst&auml;tten, Gesamtgr&ouml;&szlig;e in qm, ...)<br>
  	> Alle Verkaufsgeb&auml;ude anzeigen (bei mouseover f&auml;hrt Aktionsfeld aus)<br>
  	> Verkaufsprodukt w&auml;hlen<br>
  	> Preis einstellen<br>
  	> Werbung schalten<br>
  	> Angestellte verwalten (aus- bzw. fortbilden, einstellen, entlassen)<br>
  	> Geb&auml;ude ausbauen, verkaufen, abrei&szlig;en
</div> -->
<br><br>
<?php 
// Zählvariable deklarieren
$i = 1;
// Ersten Tab festlegen
$tabs = "	<div class='tabbable tabs-left'>
				<ul class='nav nav-tabs'>
					<li class='active'><a href='#tab0' data-toggle='tab'>Alle Geb&auml;ude</a></li>";
$tabInhalt = "	<div style='height: 780px;' class='tab-content'>
					<div class='tab-pane active' id='tab0'>
						<p>Bitte w&auml;hlen Sie ein Geb&auml;ude.</p>
					</div>";
// Schleifendurchlauf um Gebäude der richtigen Kategorie zuzuordnen
while($row = mysql_fetch_row($anzahlGebaeude)){
	// Wenn Gebäude fertiggestellt
	if(strtotime($row[6]) < strtotime(date('Y-m-d H:i:s'))){
		// Beginn des Tabs
		$tabs = $tabs . "<li class=' '><a href='#tab$i' data-toggle='tab'>";
		// Bezeichnung des Tabs
		$tabs = $tabs . $row[3];
		// Ende des Tabs
		$tabs = $tabs . "</a></li>";
		// Tab Inhalt beginn
		$tabInhalt = $tabInhalt . "<div class='tab-pane' id='tab$i'>";
		// Prüfen, welcher Stil selektiert wurde
		$stil0 = ""; $stil1 = ""; $stil2 = ""; $stil3 = "";
		switch ($row[11]){
			case "keinen":
				$stil0 = "selected='selected'";
				$stilAlt = 0;
				break;
			case "Modern":
				$stil1 = "selected='selected'";
				$stilAlt = 1;
				break;
			case "Rustikal":
				$stil2 = "selected='selected'";
				$stilAlt = 2;
				break;
			case "Edel":
				$stil3 = "selected='selected'";
				$stilAlt = 3;
				break;
		}
		
		// Prüfen, welche primäre Zielgruppe selektiert wurde
		$pzg0 = ""; $pzg1 = "";	$pzg2 = "";	$pzg3 = "";	$pzg4 = "";	$pzg5 = "";
		switch ($row[12]){
			case "keine":
				$pzg0 = "selected='selected'";
				break;
			case "Familie":
				$pzg1 = "selected='selected'";
				break;
			case "Teenager":
				$pzg2 = "selected='selected'";
				break;
			case "Junge Erwachsene":
				$pzg3 = "selected='selected'";
				break;
			case "Erwachsene":
				$pzg4 = "selected='selected'";
				break;
			case "Rentner":
				$pzg5 = "selected='selected'";
				break;
		}
		
		// Prüfen, welche sekundäre Zielgruppe selektiert wurde
		$szg0 = ""; $szg1 = "";	$szg2 = "";	$szg3 = "";	$szg4 = "";	$szg5 = "";
		switch ($row[13]){
			case "keine":
				$szg0 = "selected='selected'";
				break;
			case "Familie":
				$szg1 = "selected='selected'";
				break;
			case "Teenager":
				$szg2 = "selected='selected'";
				break;
			case "Junge Erwachsene":
				$szg3 = "selected='selected'";
				break;
			case "Erwachsene":
				$szg4 = "selected='selected'";
				break;
			case "Rentner":
				$szg5 = "selected='selected'";
				break;
		}
		
		// Prüfen, wieviele Sitzplätze selektiert wurde
			$sp0 = "";
			$sp1 = ""; $sp2 = ""; $sp3 = ""; $sp4 = ""; $sp5 = "";
			$sp6 = ""; $sp7 = ""; $sp8 = ""; $sp9 = ""; $sp10 = "";
			$sp11 = ""; $sp12 = ""; $sp13 = ""; $sp14 = ""; $sp15 = "";
			$sp16 = ""; $sp17 = ""; $sp18 = ""; $sp19 = ""; $sp20 = "";
			$sp21 = ""; $sp22 = ""; $sp23 = ""; $sp24 = ""; $sp25 = "";
		switch ($row[14]){
			case "0":
				$sp0 = "selected='selected'";
				 
				break;
			case "24":
				$sp1 = "selected='selected'";
				$sitzplatzAlt = 24;
				break;
			case "28":
				$sp2 = "selected='selected'";
				$sitzplatzAlt = 28;
				break;
			case "32":
				$sp3 = "selected='selected'";
				$sitzplatzAlt = 32;
				break;
			case "36":
				$sp4 = "selected='selected'";
				$sitzplatzAlt = 36;
				break;
			case "40":
				$sp5 = "selected='selected'";
				$sitzplatzAlt = 40;
				break;
			case "44":
				$sp6 = "selected='selected'";
				$sitzplatzAlt = 44;
				break;
			case "48":
				$sp7 = "selected='selected'";
				$sitzplatzAlt = 48;
				break;
			case "52":
				$sp8 = "selected='selected'";
				$sitzplatzAlt = 52;
				break;
			case "56":
				$sp9 = "selected='selected'";
				$sitzplatzAlt = 56;
				break;
			case "60":
				$sp10 = "selected='selected'";
				$sitzplatzAlt = 60;
				break;
			case "64":
				$sp11 = "selected='selected'";
				$sitzplatzAlt = 64;
				break;
			case "68":
				$sp12 = "selected='selected'";
				$sitzplatzAlt = 68;
				break;
			case "72":
				$sp13 = "selected='selected'";
				$sitzplatzAlt = 72;
				break;
			case "76":
				$sp14 = "selected='selected'";
				$sitzplatzAlt = 76;
				break;
			case "80":
				$sp15 = "selected='selected'";
				$sitzplatzAlt = 80;
				break;
			case "84":
				$sp16 = "selected='selected'";
				$sitzplatzAlt = 84;
				break;
			case "88":
				$sp17 = "selected='selected'";
				$sitzplatzAlt = 88;
				break;
			case "92":
				$sp18 = "selected='selected'";
				$sitzplatzAlt = 92;
				break;
			case "96":
				$sp19 = "selected='selected'";
				$sitzplatzAlt = 96;
				break;
			case "100":
				$sp20 = "selected='selected'";
				$sitzplatzAlt = 100;
				break;
			case "104":
				$sp21 = "selected='selected'";
				$sitzplatzAlt = 104;
				break;
			case "108":
				$sp22 = "selected='selected'";
				$sitzplatzAlt = 108;
				break;
			case "112":
				$sp23 = "selected='selected'";
				$sitzplatzAlt = 112;
				break;
			case "116":
				$sp24 = "selected='selected'";
				break;
			case "120":
				$sp25 = "selected='selected'";
				break;
		}
		
		// Prüfen, wieviele männliche Toiletten selektiert wurde
		$tm0 = ""; $tm1 = ""; $tm2 = ""; $tm3 = ""; $tm4 = ""; $tm5 = ""; $tm6 = "";
		switch ($row[15]){
			case "0":
				$tm0 = "selected='selected'";
				break;
			case "1":
				$tm1 = "selected='selected'";
				break;
			case "2":
				$tm2 = "selected='selected'";
				break;
			case "3":
				$tm3 = "selected='selected'";
				break;
			case "4":
				$tm4 = "selected='selected'";
				break;
			case "5":
				$tm5 = "selected='selected'";
				break;
			case "6":
				$tm6 = "selected='selected'";
				break;
		}
		
		// Prüfen, wieviele weibliche Toiletten selektiert wurde
		$tw0 = ""; $tw1 = ""; $tw2 = ""; $tw3 = ""; $tw4 = ""; $tw5 = ""; $tw6 = "";
		switch ($row[16]){
			case "0":
				$tw0 = "selected='selected'";
				break;
			case "1":
				$tw1 = "selected='selected'";
				break;
			case "2":
				$tw2 = "selected='selected'";
				break;
			case "3":
				$tw3 = "selected='selected'";
				break;
			case "4":
				$tw4 = "selected='selected'";
				break;
			case "5":
				$tw5 = "selected='selected'";
				break;
			case "6":
				$tw6 = "selected='selected'";
				break;
		}
		
		// Aktuelle Gecheckete Felder abprüfen
		if ($row[19] == '1')
			$chPils = "checked";
		else
			$chPils = "";
		if ($row[21] == '1')
			$chHelles = "checked";
		else
			$chHelles = "";
		if ($row[23] == '1')
			$chLager = "checked";
		else
			$chLager = "";
		if ($row[25] == '1')
			$chWeissbier = "checked";
		else
			$chWeissbier = "";
		if ($row[27] == '1')
			$chSchwarzbier = "checked";
		else
			$chSchwarzbier = "";
		if ($row[29] == '1')
			$chAltbier = "checked";
		else
			$chAltbier = "";
		
		if ($row[31] == '1')
			$chKorn = "checked";
		else
			$chKorn = "";
		if ($row[33] == '1')
			$chWhiskey = "checked";
		else
			$chWhiskey = "";
		if ($row[35] == '1')
			$chWodka = "checked";
		else
			$chWodka = "";
		if ($row[37] == '1')
			$chObstbrand = "checked";
		else
			$chObstbrand = "";
		if ($row[39] == '1')
			$chRum = "checked";
		else
			$chRum = "";
		if ($row[41] == '1')
			$chLikeur = "checked";
		else
			$chLikeur = "";
		
		if ($row[43] == '1')
			$chAlmradler = "checked";
		else
			$chAlmradler = "";
		if ($row[45] == '1')
			$chRadler = "checked";
		else
			$chRadler = "";
		if ($row[47] == '1')
			$chDiesel = "checked";
		else
			$chDiesel = "";
		if ($row[49] == '1')
			$chGoass = "checked";
		else
			$chGoass = "";
		if ($row[51] == '1')
			$chColaweizen = "checked";
		else
			$chColaweizen = "";
		if ($row[53] == '1')
			$chRuss = "checked";
		else
			$chRuss = "";
		
		if ($row[55] == '1')
			$chSotb = "checked";
		else
			$chSotb = "";
		if ($row[57] == '1')
			$chLiit = "checked";
		else
			$chLiit = "";
		if ($row[59] == '1')
			$chZombie = "checked";
		else
			$chZombie = "";
		if ($row[61] == '1')
			$chHurricane = "checked";
		else
			$chHurricane = "";
		if ($row[63] == '1')
			$chCaipirinha = "checked";
		else
			$chCaipirinha = "";
		if ($row[65] == '1')
			$chMojito = "checked";
		else
			$chMojito = "";
		
		if ($row[67] == '1')
			$chMineralwasser = "checked";
		else
			$chMineralwasser = "";
		if ($row[69] == '1')
			$chCola = "checked";
		else
			$chCola = "";
		if ($row[71] == '1')
			$chZlimo = "checked";
		else
			$chZlimo = "";
		if ($row[73] == '1')
			$chOlimo = "checked";
		else
			$chOlimo = "";
		if ($row[75] == '1')
			$chApfelschorle = "checked";
		else
			$chApfelschorle = "";
		if ($row[77] == '1')
			$chColamix = "checked";
		else
			$chColamix = "";
		
		if ($row[79] == '1')
			$chWeisswein = "checked";
		else
			$chWeisswein = "";
		if ($row[81] == '1')
			$chRotwein = "checked";
		else
			$chRotwein = "";
		if ($row[83] == '1')
			$chSchaumwein = "checked";
		else
			$chSchaumwein = "";
		if ($row[85] == '1')
			$chLikeurwein = "checked";
		else
			$chLikeurwein = "";
		if ($row[87] == '1')
			$chRosewein = "checked";
		else
			$chRosewein = "";
		
		if ($row[89] == '1')
			$chAsaft = "checked";
		else
			$chAsaft = "";
		if ($row[91] == '1')
			$chOsaft = "checked";
		else
			$chOsaft = "";
		if ($row[93] == '1')
			$chKsaft = "checked";
		else
			$chKsaft = "";
		if ($row[95] == '1')
			$chBsaft = "checked";
		else
			$chBsaft = "";
		if ($row[97] == '1')
			$chJsaft = "checked";
		else
			$chJsaft = "";
		if ($row[99] == '1')
			$chPsaft = "checked";
		else
			$chPsaft = "";
		
		// Tab Inhalt Aktionen und Ansicht
		$tabInhalt = $tabInhalt . " <div class='tabbable'>
										<ul class='nav nav-tabs'>
	                						<li class='active'><a href='#$row[0]A' data-toggle='tab'>&Uuml;bersicht</a></li>
	                						<li class=''><a href='#$row[0]B' data-toggle='tab'>Verkaufsbereich</a></li>
	                						<li class=''><a href='#$row[0]C' data-toggle='tab'>Lager</a></li>
	                						<li class=''><a href='#$row[0]D' data-toggle='tab'>Erweiterungen</a></li>
	                						<li class=''><a href='#$row[0]E' data-toggle='tab'>Werbung</a></li>
	                						<li class=''><a href='#$row[0]F' data-toggle='tab'>Mitarbeiter</a></li>
	              						</ul>
	              						<div class='tab-content'>
	                						<div class='tab-pane active' id='$row[0]A'>
	                							<div style='height: 500px;'>
													<div class='container-fluid'>
	  													<div class='row-fluid'>
					  										<div class='span4'>
					  											<div id='name$row[0]' align='center'><h5>$row[4] <a class='pointer' style='font-size: 8px;' onclick='feldNeuerName($row[0])' title='$row[3] umbenennen'>edit</a></h5></div>
					  											<div id='inputFeld$row[0]' style='display: none;' align='center'>Name: <input style='width: 100px; height: 15px;' type='text' id='kneipenname$row[0]' value='$row[4]' maxlength='30' placeholder='Kneipenname'><a class='pointer' style='font-size: 9px;' onclick='neuerName($row[0], \"$row[4]\")' title='Akzeptieren'>ok</a></div>
					  											<img style='width: 195px;' src='/img/Kneipe.jpg' class='img-rounded'>
					  											<div align='center'><h5>$row[9]</h5></div>
					  										</div>
					  										<div class='span8' style='width: 350px;'>
					  											<br><br>
					  											<form class='form-horizontal'>
					  												<!-- Stil -->
					  												<div class='control-group'>
					  													<label class='control-label' name='inputArt'><a class='tooltip-info' title='Der Stil der Einrichtung hat einen geringen Einfluss auf die Verteilung der Zielgruppen'>Stil der Einrichtung:</a></label>
					  													<div class='controls'>
																			<select disabled style='width: 120px; float: right;' id='inputStil$row[0]' onchange='neuerStil($row[0], $stilAlt)'>
																				<option $stil0 value='0'>Bitte w&auml;hlen</option>
																				<option $stil1 id='Stil1_$row[0]' value='1'>Modern</option>
																				<option $stil2 id='Stil2_$row[0]' value='2'>Rusikal</option>
																				<option $stil3 id='Stil3_$row[0]' value='3'>Edel</option>
																				<!-- <option $stil4 id='Stil4_$row[0]' value='4'>Option4</option> -->
																				<!-- <option $stil5 id='Stil5_$row[0]' value='5'>Option5</option> -->
																				<!-- <option $stil6 id='Stil6_$row[0]' value='6'>Option6</option> -->
																			</select>
																		</div>
					  												</div>
					  												<!-- Zielgruppen -->
					  												<div class='control-group'>
					  													<label class='control-label' name='inputArt'><a class='tooltip-info' title='Die Prim&auml;re Zielgruppe beeinflusst die nachgefragten Getr&auml;nke wesentlich'>Prim&auml;re Zielgruppe:</a></label>
					  													<div class='controls'>
																			<select disabled style='width: 160px; float: right;' id='inputPZG$row[0]' onchange='function()'>
																				<option $pzg0 value='0'>Bitte w&auml;hlen</option>
																				<option $pzg1 id='PZG1_$row[0]' value='1'>Familie</option>
																				<option $pzg2 id='PZG2_$row[0]' value='2'>Teenager</option>
																				<option $pzg3 id='PZG3_$row[0]' value='3'>Junge Erwachsene</option>
																				<option $pzg4 id='PZG4_$row[0]' value='4'>Erwachsene</option>
																				<option $pzg5 id='PZG5_$row[0]' value='5'>Rentner</option>
																			</select>
																		</div>
					  												</div>
					  												<div class='control-group'>
					  													<label class='control-label' name='inputArt'><a class='tooltip-info' title='Die Sekund&auml;re Zielgruppe beeinflusst die nachgefragten Getr&auml;nke m&auml;&szlig;ig'>Sekund&auml;re Zielgruppe:</a></label>
					  													<div class='controls'>
																			<select disabled style='width: 160px; float: right;' id='inputSZG$row[0]' onchange='function()'>
																				<option value='0'>Bitte w&auml;hlen</option>
																				<option $szg1 id='SZG1_$row[0]' value='1'>Familie</option>
																				<option $szg2 id='SZG2_$row[0]' value='2'>Teenager</option>
																				<option $szg3 id='SZG3_$row[0]' value='3'>Junge Erwachsene</option>
																				<option $szg4 id='SZG4_$row[0]' value='4'>Erwachsene</option>
																				<option $szg5 id='SZG5_$row[0]' value='5'>Rentner</option>
																			</select>
																		</div>
					  												</div>
					  												<!-- Sitzpl&auml;tze -->
					  												<div class='control-group'>
					  													<label class='control-label' name='inputArt'><a class='tooltip-info' title='Die vorhandenen Sitzpl&auml;tze sind ausschlaggebend f&uuml;r die Besucherzahlen'>Sitzpl&auml;tze:</a></label>
					  													<div class='controls'>
																			<select disabled style='width: 120px; float: right;' id='inputSP$row[0]' onchange='neueSitzplaetze($row[0],  $row[14])'>
																				<option $sp0 value='0'>Bitte w&auml;hlen</option>
																				<option $sp1 id='SP1_$row[0]' value='1'>24</option>
																				<option $sp2 id='SP2_$row[0]' value='2'>28</option>
																				<option $sp3 id='SP3_$row[0]' value='3'>32</option>
																				<option $sp4 id='SP4_$row[0]' value='4'>36</option>
																				<option $sp5 id='SP5_$row[0]' value='5'>40</option>
																				<option $sp6 id='SP5_$row[0]' value='6'>44</option>
																				<option $sp7 id='SP6_$row[0]' value='7'>48</option>
																				<option $sp8 id='SP7_$row[0]' value='8'>52</option>
																				<option $sp9 id='SP8_$row[0]' value='9'>56</option>
																				<option $sp10 id='SP9_$row[0]' value='10'>60</option>
																				<option $sp11 id='SP10_$row[0]' value='11'>64</option>
																				<option $sp12 id='SP11_$row[0]' value='12'>68</option>
																				<option $sp13 id='SP12_$row[0]' value='13'>72</option>
																				<option $sp14 id='SP13_$row[0]' value='14'>76</option>
																				<option $sp15 id='SP14_$row[0]' value='15'>80</option>
																				<option $sp16 id='SP15_$row[0]' value='16'>84</option>
																				<option $sp17 id='SP16_$row[0]' value='17'>88</option>
																				<option $sp18 id='SP17_$row[0]' value='18'>92</option>
																				<option $sp19 id='SP18_$row[0]' value='19'>96</option>
																				<option $sp20 id='SP19_$row[0]' value='20'>100</option>
																				<option $sp21 id='SP20_$row[0]' value='21'>104</option>
																				<option $sp22 id='SP21_$row[0]' value='22'>108</option>
																				<option $sp23 id='SP22_$row[0]' value='23'>112</option>
																				<option $sp24 id='SP23_$row[0]' value='24'>116</option>
																				<option $sp25 id='SP24_$row[0]' value='25'>120</option>
																			</select>
																		</div>
					  												</div>
					  												<!-- Toilettenanzahl -->
					  												<div class='control-group'>
					  													<label class='control-label' name='inputArt'><a class='tooltip-info' title='Die Anzahl an vorhandenen Toiletten hat Auswirkungen auf die Beliebtheit des speziellen Gasthauses sowie auf die Nachbestellungsrate der G&auml;ste'>Anzahl Toiletten:</a></label>
					  													<div class='controls'>
																			<select disabled style='width: 95px; float: right;' id='inputTM$row[0]' onchange='neueMToiletten($row[0],  $row[15])'>
																				<option $tm0 value='0'>M&auml;nnlich</option>
																				<option $tm1 id='TM1_$row[0]' value='1'>1</option>
																				<option $tm2 id='TM2_$row[0]' value='2'>2</option>
																				<option $tm3 id='TM3_$row[0]' value='3'>3</option>
																				<option $tm4 id='TM4_$row[0]' value='4'>4</option>
																				<option $tm5 id='TM5_$row[0]' value='5'>5</option>
																				<option $tm6 id='TM6_$row[0]' value='6'>6</option>
																			</select>
																			<select disabled style='width: 95px; float: right; ' id='inputTW$row[0]' onchange='neueWToiletten($row[0],  $row[16])'>
																				<option $tw0 value='0'>Weiblich</option>
																				<option $tw1 id='TW1_$row[0]' value='1'>1</option>
																				<option $tw2 id='TW2_$row[0]' value='2'>2</option>
																				<option $tw3 id='TW3_$row[0]' value='3'>3</option>
																				<option $tw4 id='TW4_$row[0]' value='4'>4</option>
																				<option $tw5 id='TW5_$row[0]' value='5'>5</option>
																				<option $tw6 id='TW6_$row[0]' value='6'>6</option>
																			</select>
																		</div>
					  												</div>
																	<div style='width: 350px;'>
																		<div id='alertHinweis$row[0]' style='display: none;' class='alert alert-info'></div>
																	</div>
					  												<!-- Ändern Button -->
					  												<div class='control-group'>
					  													<button onclick='aendern($row[0])' style='width: 100px; float: right;' id='aendern$row[0]' title='&Auml;ndern' class='btn' type='button'>&Auml;ndern</button>
					  													<button onclick='speichern($row[0])' style='display: none; width: 100px; float: right;' id='speichern$row[0]' title='Speichern' class='btn' type='button'>Speichern</button>
					  													<button onclick='abbruch($row[0])' style='display: none; width: 100px; float: right;' id='abbruch$row[0]' title='Abbrechen' class='btn' type='button'>Abbrechen</button>
					  													<img style='display: none;' id='wait$row[0]' src='/img/loading.gif'/>
					  												</div>
					  											</form>
					  										</div>
					  									</div>
					  									<!-- Hinweise -->
														<div class='support'>
															<div id='alertFehler$row[0]' style='display: none;' class='alert alert-error'></div>
															<div id='alertErfolg$row[0]' style='display: none;' class='alert alert-success'></div>
														</div>
	  												</div>
	  											</div>
	  										</div>
	  										<div class='tab-pane' id='$row[0]B'>
	                  							<div style='height: 720px;'>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Biere (je 0,5l)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='b1_$row[0]' $chPils disabled='disabled' type='checkbox'>Pils
															</label>
															<label class='checkbox'>
	  															<input id='b2_$row[0]' $chHelles disabled='disabled' type='checkbox'>Helles
															</label>
															<label class='checkbox'>
	  															<input id='b3_$row[0]' $chLager disabled='disabled' type='checkbox'>Lager
															</label>
															<label class='checkbox'>
	  															<input id='b4_$row[0]' $chWeissbier disabled='disabled' type='checkbox'>Wei&szlig;bier
															</label>
															<label class='checkbox'>
	  															<input id='b5_$row[0]' $chSchwarzbier disabled='disabled' type='checkbox'>Schwarzbier
															</label>
															<label class='checkbox'>
	  															<input id='b6_$row[0]' $chAltbier disabled='disabled' type='checkbox'>Altbier
															</label>
														</div>
														<div class='marktCB'>
															<div id='pilsPreis$row[0]' align='center'>$row[20] &euro;</div>
					  										<div id='inputFeldPilsPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[20]' style='width: 32px; height: 10px;' type='text' id='inputPilsPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='hellesPreis$row[0]' align='center'>$row[22] &euro;</div>
					  										<div id='inputFeldHellesPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[22]' style='width: 32px; height: 10px;' type='text' id='inputHellesPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='lagerPreis$row[0]' align='center'>$row[24] &euro;</div>
					  										<div id='inputFeldLagerPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[24]' style='width: 32px; height: 10px;' type='text' id='inputLagerPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='weissbierPreis$row[0]' align='center'>$row[26] &euro;</div>
					  										<div id='inputFeldWeissbierPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[26]' style='width: 32px; height: 10px;' type='text' id='inputWeissbierPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='schwarzbierPreis$row[0]' align='center'>$row[28] &euro;</div>
					  										<div id='inputFeldSchwarzbierPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[28]' style='width: 32px; height: 10px;' type='text' id='inputSchwarzbierPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='altbierPreis$row[0]' align='center'>$row[30] &euro;</div>
					  										<div id='inputFeldAltbierPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[30]' style='width: 32px; height: 10px;' type='text' id='inputAltbierPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
													<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Spirituosen (je 2cl)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='sp1_$row[0]' $chKorn disabled='disabled'  type='checkbox'>Korn
															</label>
															<label class='checkbox'>
	  															<input id='sp2_$row[0]' $chWhiskey disabled='disabled'  type='checkbox'>Whiskey
															</label>
															<label class='checkbox'>
	  															<input id='sp3_$row[0]' $chWodka disabled='disabled'  type='checkbox'>Wodka
															</label>
															<label class='checkbox'>
	  															<input id='sp4_$row[0]' $chObstbrand disabled='disabled'  type='checkbox'>Obstbrand
															</label>
															<label class='checkbox'>
	  															<input id='sp5_$row[0]' $chRum disabled='disabled'  type='checkbox'>Rum
															</label>
															<label class='checkbox'>
	  															<input id='sp6_$row[0]' $chLikeur disabled='disabled'  type='checkbox'>Lik&ouml;r
															</label>
														</div>
														<div class='marktCB'>
															<div id='kornPreis$row[0]' align='center'>$row[32] &euro;</div>
					  										<div id='inputFeldKornPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[32]' style='width: 32px; height: 10px;' type='text' id='inputKornPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='whiskeyPreis$row[0]' align='center'>$row[34] &euro;</div>
					  										<div id='inputFeldWhiskeyPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[34]' style='width: 32px; height: 10px;' type='text' id='inputWhiskeyPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='wodkaPreis$row[0]' align='center'>$row[36] &euro;</div>
					  										<div id='inputFeldWodkaPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[36]' style='width: 32px; height: 10px;' type='text' id='inputWodkaPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='obstbrandPreis$row[0]' align='center'>$row[38] &euro;</div>
					  										<div id='inputFeldObstbrandPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[38]' style='width: 32px; height: 10px;' type='text' id='inputObstbrandPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='rumPreis$row[0]' align='center'>$row[40] &euro;</div>
					  										<div id='inputFeldRumPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[40]' style='width: 32px; height: 10px;' type='text' id='inputRumPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='likeurPreis$row[0]' align='center'>$row[42] &euro;</div>
					  										<div id='inputFeldLikeurPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[42]' style='width: 32px; height: 10px;' type='text' id='inputLikeurPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
	                  								<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Biermischgetr&auml;nke (je 0,5l)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='bm1_$row[0]' $chAlmradler disabled='disabled' type='checkbox'>Almradler
															</label>
															<label class='checkbox'>
	  															<input id='bm2_$row[0]' $chRadler disabled='disabled' type='checkbox'>Radler
															</label>
															<label class='checkbox'>
	  															<input id='bm3_$row[0]' $chDiesel disabled='disabled' type='checkbox'>Diesel
															</label>
															<label class='checkbox'>
	  															<input id='bm4_$row[0]' $chGoass disabled='disabled' type='checkbox'>Goa&szlig;
															</label>
															<label class='checkbox'>
	  															<input id='bm5_$row[0]' $chColaweizen disabled='disabled' type='checkbox'>Colaweizen
															</label>
															<label class='checkbox'>
	  															<input id='bm6_$row[0]' $chRuss disabled='disabled' type='checkbox'>Russ
															</label>
														</div>
														<div class='marktCB'>
															<div id='almradlerPreis$row[0]' align='center'>$row[44] &euro;</div>
					  										<div id='inputFeldAlmradlerPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[44]' style='width: 32px; height: 10px;' type='text' id='inputAlmradlerPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='radlerPreis$row[0]' align='center'>$row[46] &euro;</div>
					  										<div id='inputFeldRadlerPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[46]' style='width: 32px; height: 10px;' type='text' id='inputRadlerPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='dieselPreis$row[0]' align='center'>$row[48] &euro;</div>
					  										<div id='inputFeldDieselPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[48]' style='width: 32px; height: 10px;' type='text' id='inputDieselPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='goassPreis$row[0]' align='center'>$row[50] &euro;</div>
					  										<div id='inputFeldGoassPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[50]' style='width: 32px; height: 10px;' type='text' id='inputGoassPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='colaweizenPreis$row[0]' align='center'>$row[52] &euro;</div>
					  										<div id='inputFeldColaweizenPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[52]' style='width: 32px; height: 10px;' type='text' id='inputColaweizenPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='russPreis$row[0]' align='center'>$row[54] &euro;</div>
					  										<div id='inputFeldRussPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[54]' style='width: 32px; height: 10px;' type='text' id='inputRussPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
													<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Cocktails (je 0,4l)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='c1_$row[0]' $chSotb disabled='disabled'  type='checkbox'>Sex on the Beach
															</label>
															<label class='checkbox'>
	  															<input id='c2_$row[0]' $chLiit disabled='disabled'  type='checkbox'>Long Island
															</label>
															<label class='checkbox'>
	  															<input id='c3_$row[0]' $chZombie disabled='disabled'  type='checkbox'>Zombie
															</label>
															<label class='checkbox'>
	  															<input id='c4_$row[0]' $chHurricane disabled='disabled'  type='checkbox'>Hurricane
															</label>
															<label class='checkbox'>
	  															<input id='c5_$row[0]' $chCaipirinha disabled='disabled'  type='checkbox'>Caipirinha
															</label>
															<label class='checkbox'>
	  															<input id='c6_$row[0]' $chMojito disabled='disabled'  type='checkbox'>Mojito
															</label>
														</div>
														<div class='marktCB'>
															<div id='sotbPreis$row[0]' align='center'>$row[56] &euro;</div>
					  										<div id='inputFeldSotbPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[56]' style='width: 32px; height: 10px;' type='text' id='inputSotbPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='liitPreis$row[0]' align='center'>$row[58] &euro;</div>
					  										<div id='inputFeldLiitPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[58]' style='width: 32px; height: 10px;' type='text' id='inputLiitPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='zombiePreis$row[0]' align='center'>$row[60] &euro;</div>
					  										<div id='inputFeldZombiePreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[60]' style='width: 32px; height: 10px;' type='text' id='inputZombiePreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='hurricanePreis$row[0]' align='center'>$row[62] &euro;</div>
					  										<div id='inputFeldHurricanePreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[62]' style='width: 32px; height: 10px;' type='text' id='inputHurricanePreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='caipirinhaPreis$row[0]' align='center'>$row[64] &euro;</div>
					  										<div id='inputFeldCaipirinhaPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[64]' style='width: 32px; height: 10px;' type='text' id='inputCaipirinhaPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='mojitoPreis$row[0]' align='center'>$row[66] &euro;</div>
					  										<div id='inputFeldMojitoPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[66]' style='width: 32px; height: 10px;' type='text' id='inputMojitoPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
													<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Alkoholfreie Getr&auml;nke (je 0,5l)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='af1_$row[0]' $chMineralwasser disabled='disabled' type='checkbox'>Mineralwasser
															</label>
															<label class='checkbox'>
	  															<input id='af2_$row[0]' $chCola disabled='disabled'  type='checkbox'>Cola
															</label>
															<label class='checkbox'>
	  															<input id='af3_$row[0]' $chZlimo disabled='disabled'  type='checkbox'>Zitronenlimonade
															</label>
															<label class='checkbox'>
	  															<input id='af4_$row[0]' $chOlimo disabled='disabled'  type='checkbox'>Orangenlimonade
															</label>
															<label class='checkbox'>
	  															<input id='af5_$row[0]' $chApfelschorle disabled='disabled' type='checkbox'>Apfelschorle
															</label>
															<label class='checkbox'>
	  															<input id='af6_$row[0]' $chColamix disabled='disabled'  type='checkbox'>Cola-Mix
															</label>
														</div>
														<div class='marktCB'>
															<div id='mineralwasserPreis$row[0]' align='center'>$row[68] &euro;</div>
					  										<div id='inputFeldMineralwasserPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[68]' style='width: 32px; height: 10px;' type='text' id='inputMineralwasserPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='colaPreis$row[0]' align='center'>$row[70] &euro;</div>
					  										<div id='inputFeldColaPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[70]' style='width: 32px; height: 10px;' type='text' id='inputColaPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='zlimoPreis$row[0]' align='center'>$row[72] &euro;</div>
					  										<div id='inputFeldZlimoPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[72]' style='width: 32px; height: 10px;' type='text' id='inputZlimoPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='olimoPreis$row[0]' align='center'>$row[74] &euro;</div>
					  										<div id='inputFeldOlimoPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[74]' style='width: 32px; height: 10px;' type='text' id='inputOlimoPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='apfelschorlePreis$row[0]' align='center'>$row[76] &euro;</div>
					  										<div id='inputFeldApfelschorlePreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[76]' style='width: 32px; height: 10px;' type='text' id='inputApfelschorlePreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='colamixPreis$row[0]' align='center'>$row[78] &euro;</div>
					  										<div id='inputFeldColamixPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[78]' style='width: 32px; height: 10px;' type='text' id='inputColamixPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
													<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Weine (je 0,1l)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='w1_$row[0]' $chWeisswein disabled='disabled'  type='checkbox'>Wei&szlig;wein
															</label>
															<label class='checkbox'>
	  															<input id='w2_$row[0]' $chRotwein disabled='disabled'  type='checkbox'>Rotwein
															</label>
															<label class='checkbox'>
	  															<input id='w3_$row[0]' $chSchaumwein disabled='disabled'  type='checkbox'>Schaumwein
															</label>
															<label class='checkbox'>
	  															<input id='w4_$row[0]' $chLikeurwein disabled='disabled'  type='checkbox'>Lik&ouml;rwein
															</label>
															<label class='checkbox'>
	  															<input id='w5_$row[0]' $chRosewein disabled='disabled'  type='checkbox'>Rosewein
															</label>
															<label>
	  															<br>
															</label>
														</div>
														<div class='marktCB'>
															<div id='weissweinPreis$row[0]' align='center'>$row[80] &euro;</div>
					  										<div id='inputFeldWeissweinPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[80]' style='width: 32px; height: 10px;' type='text' id='inputWeissweinPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='rotweinPreis$row[0]' align='center'>$row[82] &euro;</div>
					  										<div id='inputFeldRotweinPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[82]' style='width: 32px; height: 10px;' type='text' id='inputRotweinPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='schaumweinPreis$row[0]' align='center'>$row[84] &euro;</div>
					  										<div id='inputFeldSchaumweinPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[84]' style='width: 32px; height: 10px;' type='text' id='inputSchaumweinPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='likeurweinPreis$row[0]' align='center'>$row[86] &euro;</div>
					  										<div id='inputFeldLikeurweinPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[86]' style='width: 32px; height: 10px;' type='text' id='inputLikeurweinPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='roseweinPreis$row[0]' align='center'>$row[88] &euro;</div>
					  										<div id='inputFeldRoseweinPreis$row[0]' style='heigth: 18px; display: none;' align='center'>
					  											<input value='$row[88]' style='width: 32px; height: 10px;' type='text' id='inputRoseweinPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
													<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>S&auml;fte (je 0,4l)</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>
	  															<input id='s1_$row[0]' $chAsaft disabled='disabled'  type='checkbox'>Apfelsaft
															</label>
															<label class='checkbox'>
	  															<input id='s2_$row[0]' $chOsaft disabled='disabled'  type='checkbox'>Orangensaft
															</label>
															<label class='checkbox'>
	  															<input id='s3_$row[0]' $chKsaft disabled='disabled'  type='checkbox'>Kirschsaft
															</label>
															<label class='checkbox'>
	  															<input id='s4_$row[0]' $chBsaft disabled='disabled'  type='checkbox'>Bananensaft
															</label>
															<label class='checkbox'>
	  															<input id='s5_$row[0]' $chJsaft disabled='disabled'  type='checkbox'>Johannisbeersaft
															</label>
															<label class='checkbox'>
	  															<input id='s6_$row[0]' $chPsaft disabled='disabled'  type='checkbox'>Pfirsichsaft
															</label>
														</div>
														<div class='marktCB'>
															<div id='asaftPreis$row[0]' align='center'>$row[90] &euro;</div>
					  										<div id='inputFeldAsaftPreis$row[0]' style='heigth: 18px; margin-top: 2px; display: none;' align='center'>
					  											<input value='$row[90]' style='width: 32px; height: 10px;' type='text' id='inputAsaftPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='osaftPreis$row[0]' align='center'>$row[92] &euro;</div>
					  										<div id='inputFeldOsaftPreis$row[0]' style='heigth: 18px; margin-top: 2px; display: none;' align='center'>
					  											<input value='$row[92]' style='width: 32px; height: 10px;' type='text' id='inputOsaftPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='ksaftPreis$row[0]' align='center'>$row[94] &euro;</div>
					  										<div id='inputFeldKsaftPreis$row[0]' style='heigth: 18px; margin-top: 2px; display: none;' align='center'>
					  											<input value='$row[94]' style='width: 32px; height: 10px;' type='text' id='inputKsaftPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='bsaftPreis$row[0]' align='center'>$row[96] &euro;</div>
					  										<div id='inputFeldBsaftPreis$row[0]' style='heigth: 18px; margin-top: 2px; display: none;' align='center'>
					  											<input value='$row[96]' style='width: 32px; height: 10px;' type='text' id='inputBsaftPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='jsaftPreis$row[0]' align='center'>$row[98] &euro;</div>
					  										<div id='inputFeldJsaftPreis$row[0]' style='heigth: 18px; margin-top: 2px; display: none;' align='center'>
					  											<input value='$row[98]' style='width: 32px; height: 10px;' type='text' id='inputJsaftPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
					  										<div id='psaftPreis$row[0]' align='center'>$row[100] &euro;</div>
					  										<div id='inputFeldPsaftPreis$row[0]' style='heigth: 18px; margin-top: 2px; display: none;' align='center'>
					  											<input value='$row[100]' style='width: 32px; height: 10px;' type='text' id='inputPsaftPreis$row[0]' maxlength='4' placeholder='Preis'>&euro;
					  										</div>
														</div>
													</div>
													<div class='marktPart'>
														<img style='display: none;' id='wait2_$row[0]' src='/img/loading.gif'/>
														<br>
														<button onclick='karteBearbeiten($row[0])' style='width: 265px;' id='karteBearbeiten$row[0]' title='Karte bearbeiten' class='btn' type='button'>Karte bearbeiten</button>
														<button onclick='karteSpeichern($row[0])' style='width: 130px; display: none;' id='karteSpeichern$row[0]' title='Karte speichern' class='btn' type='button'>Karte speichern</button>
														<button onclick='karteAbbruch($row[0])' style='width: 130px; display: none;' id='karteAbbruch$row[0]' title='Abbrechen' class='btn' type='button'>Abbrechen</button>
														<br><br>
														<div style='width: 250px;'>
															<div id='alertFehler2$row[0]' style='display: none;' class='alert alert-error'></div>
															<div id='alertErfolg2$row[0]' style='display: none;' class='alert alert-success'></div>
														</div>
													</div>
												</div>
	                						</div>
	                						<div class='tab-pane' id='$row[0]C'>
	                							<div style='height: 720px;'>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Biere</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Pils</label>
															<label class='checkbox'>Helles</label>
															<label class='checkbox'>Lager</label>
															<label class='checkbox'>Wei&szlig;bier</label>
															<label class='checkbox'>Schwarzbier</label>
															<label class='checkbox'>Altbier</label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[103] l</div>
					  										<div align='right'>$row[104] l</div>
					  										<div align='right'>$row[105] l</div>
					  										<div align='right'>$row[106] l</div>
					  										<div align='right'>$row[107] l</div>
					  										<div align='right'>$row[108] l</div>
														</div>
													</div>
													<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Spirituosen</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Korn</label>
															<label class='checkbox'>Whiskey</label>
															<label class='checkbox'>Wodka</label>
															<label class='checkbox'>Obstbrand</label>
															<label class='checkbox'>Rum</label>
															<label class='checkbox'>Lik&ouml;r</label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[109] l</div>
					  										<div align='right'>$row[110] l</div>
					  										<div align='right'>$row[111] l</div>
					  										<div align='right'>$row[112] l</div>
					  										<div align='right'>$row[113] l</div>
					  										<div align='right'>$row[114] l</div>
														</div>
													</div>
	                  								<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Biermischgetr&auml;nke</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Almradler</label>
															<label class='checkbox'>Radler</label>
															<label class='checkbox'>Diesel</label>
															<label class='checkbox'>Goa&szlig;</label>
															<label class='checkbox'>Colaweizen</label>
															<label class='checkbox'>Russ</label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[115] l</div>
					  										<div align='right'>$row[116] l</div>
					  										<div align='right'>$row[117] l</div>
					  										<div align='right'>$row[118] l</div>
					  										<div align='right'>$row[119] l</div>
					  										<div align='right'>$row[120] l</div>
														</div>
													</div>
													<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Cocktails</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Sex on the Beach</label>
															<label class='checkbox'>Long Island</label>
															<label class='checkbox'>Zombie</label>
															<label class='checkbox'>Hurricane</label>
															<label class='checkbox'>Caipirinha</label>
															<label class='checkbox'>Mojito</label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[121] l</div>
					  										<div align='right'>$row[122] l</div>
					  										<div align='right'>$row[123] l</div>
					  										<div align='right'>$row[124] l</div>
					  										<div align='right'>$row[125] l</div>
					  										<div align='right'>$row[126] l</div>
														</div>
													</div>
													<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Alkoholfreie Getr&auml;nke</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Mineralwasser
															</label>
															<label class='checkbox'>Cola</label>
															<label class='checkbox'>Zitronenlimonade</label>
															<label class='checkbox'>Orangenlimonade</label>
															<label class='checkbox'>Apfelschorle</label>
															<label class='checkbox'>Cola-Mix</label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[127] l</div>
					  										<div align='right'>$row[128] l</div>
					  										<div align='right'>$row[129] l</div>
					  										<div align='right'>$row[130] l</div>
					  										<div align='right'>$row[131] l</div>
					  										<div align='right'>$row[132] l</div>
														</div>
													</div>
													<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>Weine</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Wei&szlig;wein</label>
															<label class='checkbox'>Rotwein</label>
															<label class='checkbox'>Schaumwein</label>
															<label class='checkbox'>Lik&ouml;rwein</label>
															<label class='checkbox'>Rosewein</label>
															<label><br></label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[133] l</div>
					  										<div align='right'>$row[134] l</div>
					  										<div align='right'>$row[135] l</div>
					  										<div align='right'>$row[136] l</div>
					  										<div align='right'>$row[137] l</div>
														</div>
													</div>
													<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'><h5>S&auml;fte</h5></div>
														<div class='marktCB'>
															<label class='checkbox'>Apfelsaft</label>
															<label class='checkbox'>Orangensaft</label>
															<label class='checkbox'>Kirschsaft</label>
															<label class='checkbox'>Bananensaft</label>
															<label class='checkbox'>Johannisbeersaft</label>
															<label class='checkbox'>Pfirsichsaft</label>
														</div>
														<div class='marktDB'>
															<div align='right'>$row[138] l</div>
					  										<div align='right'>$row[139] l</div>
					  										<div align='right'>$row[140] l</div>
					  										<div align='right'>$row[141] l</div>
					  										<div align='right'>$row[142] l</div>
					  										<div align='right'>$row[143] l</div>
														</div>
													</div>
													<br>
	                  								<div class='marktPart'>
														<div align='center' class='marktGetr'>
															<h5>Bestellung</h5>
															<!-- Gattung -->
															<div>
																<div class='controls'>
																	<select style='width: 200px;' id='inputArt$row[0]' onchange='setGetraenk($row[0])'>
																		<option value='0'>Gattung</option>
																		<option id='Art1' value='1'>Biere</option>
																		<option id='Art2' value='2'>Spirituosen</option>
																		<option id='Art3' value='3'>Biermischgetr&auml;nke</option>
																		<option id='Art4' value='4'>Alkoholfreie Getr&auml;nke</option>
																		<option id='Art5' value='5'>S&auml;fte</option>
																		<option id='Art6' value='6'>Cocktails</option>
																		<option id='Art7' value='7'>Weine</option>
																	</select>
																</div>
															</div>
															<!-- Bier -->
															<div id='bier$row[0]' style='display: none;' class='control-group'>
																<div>
																	<select style='width: 200px;' id='inputBier$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Pils</option>
																		<option value='2'>Helles</option>
																		<option value='3'>Lager</option>
																		<option value='4'>Wei&szlig;bier</option>
																		<option value='5'>Schwarzbier</option>
																		<option value='6'>Altbier</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Bier -->
															<div id='bierAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='bierAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisBier$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label inline' id='kostenBiere$row[0]'></label>
																	</div>
																</div>
														  	</div>
														  	
														  	
															<!-- Spirituosen -->
															<div id='spirituosen$row[0]' style='display: none;' class='control-group'>
																<div class='controls'>
																	<select style='width: 200px;' id='inputSpirituosen$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Korn</option>
																		<option value='2'>Whiskey</option>
																		<option value='3'>Wodka</option>
																		<option value='4'>Obstbrand</option>
																		<option value='5'>Rum</option>
																		<option value='6'>Lik&ouml;r</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Spirituosen -->
															<div id='spirituosenAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='spirituosenAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisSpirituosen$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label inline' id='kostenSpirituosen$row[0]'></label>
																	</div>
																</div>
														  	</div>
															
															
															<!-- Biermischgetränke -->
															<div id='biermisch$row[0]' style='display: none;' class='control-group'>
																<div class='controls'>
																	<select style='width: 200px;' id='inputBiermisch$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Almradler</option>
																		<option value='2'>Radler</option>
																		<option value='3'>Diesel</option>
																		<option value='4'>Goa&szlig;</option>
																		<option value='5'>Colaweizen</option>
																		<option value='6'>Russ</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Biermischgetränke -->
															<div id='biermischAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='biermischAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisBiermisch$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label inline' id='kostenBiermisch$row[0]'></label>
																	</div>
																</div>
														  	</div>
														  	
															
															<!-- Alkoholfreie Getränke -->
															<div id='alkoholfrei$row[0]' style='display: none;' class='control-group'>
																<div class='controls'>
																	<select style='width: 200px;' id='inputAlkoholfrei$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Mineralwasser</option>
																		<option value='2'>Cola</option>
																		<option value='3'>Zitronenlimonade</option>
																		<option value='4'>Orangenlimonade</option>
																		<option value='5'>Apfelschorle</option>
																		<option value='6'>Cola-Mix</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Alkoholfreie Getränke -->
															<div id='alkoholfreiAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='alkoholfreiAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisAlkoholfrei$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label inline' id='kostenAlkoholfrei$row[0]'></label>
																	</div>
																</div>
														  	</div>
															
															
															<!-- Säfte -->
															<div id='saefte$row[0]' style='display: none;' class='control-group'>
																<div class='controls'>
																	<select style='width: 200px;' id='inputSaefte$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Apfelsaft</option>
																		<option value='2'>Orangensaft</option>
																		<option value='3'>Kirschsaft</option>
																		<option value='4'>Bananenfruchtsaft</option>
																		<option value='5'>Johannisbeerfruchsaft</option>
																		<option value='6'>Pfirsichfruchtsaft</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Säfte -->
															<div id='saefteAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='saefteAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisSaefte$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label inline' id='kostenSaefte$row[0]'></label>
																	</div>
																</div>
														  	</div>
															
															
															<!-- Cocktails -->
															<div id='cocktails$row[0]' style='display: none;' class='control-group'>
																<div class='controls'>
																	<select style='width: 200px;' id='inputCocktails$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Sex on the Beach</option>
																		<option value='2'>Long Island Iced Tea</option>
																		<option value='3'>Zombie</option>
																		<option value='4'>Hurricane</option>
																		<option value='5'>Caipirinha</option>
																		<option value='6'>Mojito</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Cocktails -->
															<div id='cocktailsAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='cocktailsAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisCocktails$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label inline' id='kostenCocktails$row[0]'></label>
																	</div>
																</div>
														  	</div>
															
															
															<!-- Weine -->
															<div id='weine$row[0]' style='display: none;' class='control-group'>
																<div class='controls'>
																	<select style='width: 200px;' id='inputWeine$row[0]' onchange='checkForm($row[0])'>
																		<option value='0'>Bitte w&auml;hlen</option>
																		<option value='1'>Wei&szlig;wein</option>
																		<option value='2'>Rotwein</option>
																		<option value='3'>Schaumwein</option>
																		<option value='4'>Lik&ouml;rwein</option>
																		<option value='5'>Rose</option>
																	</select>
																</div>
															</div>
															<!-- Anzahl Weine -->
															<div id='weineAnzahlAuswahl$row[0]' style='display: none;' class='control-group'>
																<label class='control-label inline'>Anzahl Liter:</label>
																<div class='controls inline'>
																	<input onkeyup='berechnePreis(this.value, $row[0])' value='1' style='height: 20px; width: 30px;' maxlength='3' id='weineAnzahl$row[0]' type='text'>
																</div>
																<br>
																<div id='gesamtpreisWein$row[0]'>
															   		<label class='control-label inline'>Gesamtkosten:</label>
																	<div class='controls inline'>
																		<label class='control-label' id='kostenWeine$row[0]'></label>
																	</div>
																</div>
														  	</div>
														  	<!-- Ladeanimation -->
															<div align='center'>
																<img style='display: none;' id='warten$row[0]' src='/img/loading.gif'/>
																<!-- Meldungen -->
																<div id='alertFehler3$row[0]' style='display: none; width: 400px;' class='alert alert-error'></div>
																<div id='alertErfolg3$row[0]' style='display: none; width: 400px;' class='alert alert-success'></div>
															</div>
														  	<!-- Anzeigen-Button -->
														  	<div class='control-group'>
														   		<div class='controls'>
														   			<button disabled='disabled' id='btnKaufen$row[0]' type='button' class='btn btn-info' onclick='kaufen($row[0])'>Kaufen</button>
														    	</div>
														  	</div>
														</div>
													</div>
												</div>
	                						</div>
	                						<div class='tab-pane' id='$row[0]D'>
	                  							<div style='height: 500px;'>
	                  								Hier kann die $row[3] mit diversen Einrichtungsgegenst&auml;nden erweitert werden.<br>
	                  								Ein Ausbau ist ebenfalls m&ouml;glich.<br>
	                  								Erweiterungen:<br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t +++ / Attraktivit&auml;t o Erm&ouml;glicht eine effiziente Versorgung der G&auml;ste mit Bier'>Zapfanlage</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t ++ / Attraktivit&auml;t +++'>Stammtisch</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t ++ / Attraktivit&auml;t + Erm&ouml;glicht das Feiern von Festen'>Festzelt</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t ++ / Attraktivit&auml;t +++'>Bar</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t + / Attraktivit&auml;t ++ Erm&ouml;glicht das Einstellen der Temperatur'>Kaminofen</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t + / Attraktivit&auml;t +'>Happy Hour</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t +++ / Attraktivit&auml;t +++ Sorgt f&uuml;r erh&ouml;hte Besucherzahlen bei milden Temperaturen'>Biergarten</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t +++ / Attraktivit&auml;t +++ Erm&ouml;glicht das Anbieten von Speisen und erh&ouml;ht die Besucherzahlen'>K&uuml;che</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t + / Attraktivit&auml;t + Erleichtert die Auswahl f&uuml;r die Besucher'>Speisekarte</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t + / Attraktivit&auml;t ++ Erm&ouml;glicht ein saisonales Angebot an Speisen'>Tageskarte</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t ++ / Attraktivit&auml;t o Erm&ouml;glicht das effiziente Verteilen von Speisen'>Servierwagen</a><br>
	                  								> <a class='tooltip-info pointer' title='Produktivit&auml;t + / Attraktivit&auml;t +'>Menage</a><br>
	                  							</div>
	                						</div>
	                						<div class='tab-pane' id='$row[0]E'>
	                  							<div style='height: 500px;'>
	                  								Werbung.
	                  							</div>
	                						</div>
	                						<div class='tab-pane' id='$row[0]F'>
	                  							<div style='height: 500px;'>
	                  								Mitarbeiterverwaltung.
	                  							</div>
	                						</div>
	              						</div>
	            					</div>
	            					<script type='text/javascript'>
	            						var anzahl$row[0] = 0;
	            					</script>";
		// Tab Inhalt ende
		$tabInhalt = $tabInhalt . "</div>";
		// Variable erhöhen
		$i++;
	}
}
// Wenn es Mindestens ein Gebäude gibt
if (($i-1) > 0){
	// Nach Tabs
	$tabs = $tabs . "</ul>";
	// Nach TabInhalt
	$tabInhalt = $tabInhalt . "</div></div>";
	// Kurzer Hinweis
	echo "<div align='center'><h2 id='anzahlGebaeude'>Deine Verkaufsgeb&auml;ude: " . ($i-1) . "</h2></div>";
}
// Wenn es kein Gebäude gibt
else{
	// Kurzer Hinweis
	echo "<div align='center'><h2>Sie haben leider noch kein Geb&auml;ude dieser Kategorie gebaut</h2></div>";
}
?>
<!-- Hinweise / Hauptfehlermeldungen -->
<div class="support">
	<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
	<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
</div>
<br>
<!-- Aufklappbares Menü für alle Gebäude -->
<div style='display: none;'>$text</div>
<br>
<div class="inhalt">
	<?php
		echo $tabs;
		echo $tabInhalt;
	?>
</div>
<script type="text/javascript">
	// Globale Variablen für die gesetzen Werte
	var stil;
	var pzg;
	var szg;
	var sp;
	var tm;
	var tw;
	var umbaukostenStil = 0;
	var umbaukostenSitzplaetze = 0;
	var umbaukostenMToiletten = 0;
	var umbaukostenWToiletten = 0;
	var aenderungskosten = 0;
	var finanzen = "<?php echo $finanzen[0];?>";
	var gcPils; var gcHelles; var gcLager; var gcWeissbier; var gcSchwarzbier; var gcAltbier;
	var gcKorn; var gcWhiskey; var gcWodka; var gcObstbrand; var gcRum; var gcLikeur;
	var gcAlmradler; var gcRadler; var gcDiesel; var gcGoass; var gcColaweizen; var gcRuss;
	var gcSotb; var gcLiit; var gcZombie; var gcHurricane; var gcCaipirinha; var gcMojito;
	var gcMineralwasser; var gcCola; var gcZlimo; var gcOlimo; var gcApfelschorle; var gcColamix;
	var gcWeisswein; var gcRotwein; var gcSchaumwein; var gcLikeurwein; var gcRosewein;
	var gcAsaft; var gcOsaft; var gcKsaft; var gcBsaft; var gcJsaft; var Psaft;
	var gattung;
	var art;
	var einzelpreis;
	var gesamtpreis;
	var gesamtmenge;
	var finanzen = "<?php echo $finanzen[0];?>";
	var gaststaette = 0;
	// Preise setzen
	var pPils = "<?php echo $preis[0];?>";
	var pHelles = "<?php echo $preis[1];?>";
	var pLager = "<?php echo $preis[2];?>";
	var pWeissbier = "<?php echo $preis[3];?>";
	var pSchwarzbier = "<?php echo $preis[4];?>";
	var pAltbier = "<?php echo $preis[5];?>";
	var pKorn = "<?php echo $preis[6];?>";
	var pWhiskey = "<?php echo $preis[7];?>";
	var pWodka = "<?php echo $preis[8];?>";
	var pObstbrand = "<?php echo $preis[9];?>";
	var pRum = "<?php echo $preis[10];?>";
	var pLikeur = "<?php echo $preis[11];?>";
	var pAlmradler = "<?php echo $preis[12];?>";
	var pRadler = "<?php echo $preis[13];?>";
	var pDiesel = "<?php echo $preis[14];?>";
	var pGoass = "<?php echo $preis[15];?>";
	var pColaweizen = "<?php echo $preis[16];?>";
	var pRuss = "<?php echo $preis[17];?>";
	var pMineralwasser = "<?php echo $preis[18];?>";
	var pCola = "<?php echo $preis[19];?>";
	var pZlimo = "<?php echo $preis[20];?>";
	var pOlimo = "<?php echo $preis[21];?>";
	var pApfelschorle = "<?php echo $preis[22];?>";
	var pColamix = "<?php echo $preis[23];?>";
	var pAsaft = "<?php echo $preis[24];?>";
	var pOsaft = "<?php echo $preis[25];?>";
	var pKsaft = "<?php echo $preis[26];?>";
	var pBsaft = "<?php echo $preis[27];?>";
	var pJsaft = "<?php echo $preis[28];?>";
	var pPsaft = "<?php echo $preis[29];?>";	
	var pSotb = "<?php echo $preis[30];?>";
	var pLiit = "<?php echo $preis[31];?>";
	var pZombie = "<?php echo $preis[32];?>";
	var pHurricane = "<?php echo $preis[33];?>";
	var pCaipirinha = "<?php echo $preis[34];?>";
	var pMojito = "<?php echo $preis[35];?>";
	var pWeisswein = "<?php echo $preis[36];?>";
	var pRotwein = "<?php echo $preis[37];?>";
	var pSchaumwein = "<?php echo $preis[38];?>";
	var pLikeurwein = "<?php echo $preis[39];?>";
	var pRosewein = "<?php echo $preis[40];?>";
	
	// Funktion zum Ändern der Werte
	function aendern(elementId){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Zurücksetzen der umbaukosten
		umbaukostenStil = 0;
		umbaukostenSitzplaetze = 0;
		umbaukostenMToiletten = 0;
		umbaukostenWToiletten = 0;
		aenderungskosten = 0;
		// Id`s der Selectfelder zusammensetzen
		var stilId = "inputStil" + elementId;
		var pzgId = "inputPZG" + elementId;
		var szgId = "inputSZG" + elementId;
		var spId = "inputSP" + elementId;
		var tmId = "inputTM" + elementId;
		var twId = "inputTW" + elementId;
		// Selectfelder sichtbar machen
		document.getElementById(stilId).removeAttribute('disabled');
		document.getElementById(pzgId).removeAttribute('disabled');
		document.getElementById(szgId).removeAttribute('disabled');
		document.getElementById(spId).removeAttribute('disabled');
		document.getElementById(tmId).removeAttribute('disabled');
		document.getElementById(twId).removeAttribute('disabled');
		// Selektierter Index zwischenspeichern
		stil = document.getElementById(stilId).selectedIndex;
		pzg = document.getElementById(pzgId).selectedIndex;
		szg = document.getElementById(szgId).selectedIndex;
		sp = document.getElementById(spId).selectedIndex;
		tm = document.getElementById(tmId).selectedIndex;
		tw = document.getElementById(twId).selectedIndex;
		// Speichern und Abbruch Button anzeigen
		var speichernId = "speichern" + elementId;
		var abbrechenId = "abbruch" + elementId;
		document.getElementById(speichernId).setAttribute('style', 'width: 100px; float: right;');
		document.getElementById(abbrechenId).setAttribute('style', 'margin-right: 20px; width: 100px; float: right;');
		// Umbaukosten anzeigen
		zeigeUmbaukosten(elementId);
		// Anzeigen Button disablen
		var anzeigenId = "aendern" + elementId;
		document.getElementById(anzeigenId).setAttribute('style', 'display: none; width: 100px; float: right;');
	}

	// Funktion zum abbrechen
	function abbruch(elementId){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Id`s der Selectfelder zusammensetzen
		var stilId = "inputStil" + elementId;
		var pzgId = "inputPZG" + elementId;
		var szgId = "inputSZG" + elementId;
		var spId = "inputSP" + elementId;
		var tmId = "inputTM" + elementId;
		var twId = "inputTW" + elementId;
		// Selectfelder disablen
		document.getElementById(stilId).setAttribute('disabled');
		document.getElementById(pzgId).setAttribute('disabled');
		document.getElementById(szgId).setAttribute('disabled');
		document.getElementById(spId).setAttribute('disabled');
		document.getElementById(tmId).setAttribute('disabled');
		document.getElementById(twId).setAttribute('disabled');
		// Alte Indizes auswählen
		document.getElementById(stilId).selectedIndex = stil;
		document.getElementById(pzgId).selectedIndex = pzg;
		document.getElementById(szgId).selectedIndex = szg;
		document.getElementById(spId).selectedIndex = sp;
		document.getElementById(tmId).selectedIndex = tm;
		document.getElementById(twId).selectedIndex = tw;
		// Speichern und Abbruch Button disablen
		var speichernId = "speichern" + elementId;
		var abbrechenId = "abbruch" + elementId;
		document.getElementById(speichernId).setAttribute('style', 'display: none; width: 100px; float: right;');
		document.getElementById(abbrechenId).setAttribute('style', 'display: none; width: 100px; float: right;');
		// Umbaukosten entfernen
		document.getElementById("alertHinweis" + elementId).setAttribute('style', 'display: none;');
		// Anzeigen Button anzeigen
		var anzeigenId = "aendern" + elementId;
		document.getElementById(anzeigenId).setAttribute('style', 'width: 100px; float: right;');
	}

	// Funktion zum setzen eines neuen Stils
	function neuerStil(elementId, alterStil){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Selektierten Index abfragen
		var neuerStil = document.getElementById("inputStil" + elementId).selectedIndex;
		// Wenn selectierter Index = 0
		if (neuerStil == 0){
			umbaukostenStil = 0;
		}
		else{
			// Wenn gleicher Stil gewählt
			if (alterStil == neuerStil){
				umbaukostenStil = 0;
			}
			else{
				umbaukostenStil = 10000;
			}
		}
		zeigeUmbaukosten(elementId);
	}

	// Funktion zum ändern der Sitzplätze
	function neueSitzplaetze(elementId, alteSitzplaetze){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Selektierten Wert abfragen
		var neueSitzplaetze = 20 + (document.getElementById("inputSP" + elementId).selectedIndex * 4);
		// Wenn selectierter Index = 0
		if (neueSitzplaetze == 20){
			umbaukostenSitzplaetze = 0;
		}
		else{
			// Änderung der Sitzplätze berechnen
			var aenderung = neueSitzplaetze - alteSitzplaetze;
			// Wenn gleiche Sitzplatzanzahl
			if (alteSitzplaetze == neueSitzplaetze){
				umbaukostenSitzplaetze = 0;
			}
			else{
				if (aenderung > 0){
					umbaukostenSitzplaetze = aenderung * 50; 
				}
				else{
					umbaukostenSitzplaetze = aenderung * 20; 
				}
			}
		}
		zeigeUmbaukosten(elementId);
	}

	// Funktion zum ändern der männlichen Toiletten
	function neueMToiletten(elementId, alteMToiletten){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Selektierten Wert abfragen
		var neueMToiletten = document.getElementById("inputTM" + elementId).selectedIndex;
		// Wenn selectierter Index = 0
		if (neueMToiletten == 0){
			umbaukostenMToiletten = 0;
		}
		else{
			// Änderung der Toiletten berechnen
			var aenderung = neueMToiletten - alteMToiletten;
			// Wenn gleiche Toilettenanzahl
			if (alteMToiletten == neueMToiletten){
				umbaukostenMToiletten = 0;
			}
			else{
				if (aenderung > 0){
					umbaukostenMToiletten = aenderung * 500; 
				}
				else{
					umbaukostenMToiletten = aenderung * -200; 
				}
			}
		}
		zeigeUmbaukosten(elementId);
	}

	// Funktion zum ändern der weiblichen Toiletten
	function neueWToiletten(elementId, alteWToiletten){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Selektierten Wert abfragen
		var neueWToiletten = document.getElementById("inputTW" + elementId).selectedIndex;
		// Wenn selectierter Index = 0
		if (neueWToiletten == 0){
			umbaukostenWToiletten = 0;
		}
		else{
			// Änderung der Toiletten berechnen
			var aenderung = neueWToiletten - alteWToiletten;
			// Wenn gleiche Toilettenanzahl
			if (alteWToiletten == neueWToiletten){
				umbaukostenWToiletten = 0;
			}
			else{
				if (aenderung > 0){
					umbaukostenWToiletten = aenderung * 500; 
				}
				else{
					umbaukostenWToiletten = aenderung * -200; 
				}
			}
		}
		zeigeUmbaukosten(elementId);
	}

	// Funktion, die die Umbaukosten anzeigt
	function zeigeUmbaukosten(elementId){
		// Gesamtkosten berechnen
		aenderungskosten = umbaukostenStil + umbaukostenSitzplaetze + umbaukostenMToiletten + umbaukostenWToiletten;
		// Kosten anzeigen
		document.getElementById("alertHinweis" + elementId).innerHTML = "<b>Umbaukosten:</b> " + aenderungskosten + "&euro;";
		document.getElementById("alertHinweis" + elementId).removeAttribute('style');
	}
	
	// Funktion zum Speichern
	function speichern(elementId){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Id`s der Selectfelder zusammensetzen
		var stilId = "inputStil" + elementId;
		var pzgId = "inputPZG" + elementId;
		var szgId = "inputSZG" + elementId;
		var spId = "inputSP" + elementId;
		var tmId = "inputTM" + elementId;
		var twId = "inputTW" + elementId;
		// Neue Werte abfragen
		var stilNeu = document.getElementById(stilId).selectedIndex;
		var pzgNeu = document.getElementById(pzgId).selectedIndex;
		var szgNeu = document.getElementById(szgId).selectedIndex;
		var spNeu = document.getElementById(spId).selectedIndex;
		var tmNeu = document.getElementById(tmId).selectedIndex;
		var twNeu = document.getElementById(twId).selectedIndex;
		// Wenn sich kein Wert geändert hat
		if ((stil == stilNeu) && (pzg == pzgNeu) && (szg == szgNeu)
				&& (sp == spNeu) && (tm == tmNeu) && (tw == twNeu)){
			// Meldung anzeigen dass alles gespeichert wurde
			document.getElementById("alertErfolg" + elementId).innerHTML = "<b>Erfolg!</b> Alle Werte wurden erfolgreich gespeichert";
			document.getElementById("alertErfolg" + elementId).removeAttribute('style');
			// Selectfelder disablen
			document.getElementById(stilId).setAttribute('disabled');
			document.getElementById(pzgId).setAttribute('disabled');
			document.getElementById(szgId).setAttribute('disabled');
			document.getElementById(spId).setAttribute('disabled');
			document.getElementById(tmId).setAttribute('disabled');
			document.getElementById(twId).setAttribute('disabled');
			// Speichern und Abbruch Button disablen
			var speichernId = "speichern" + elementId;
			var abbrechenId = "abbruch" + elementId;
			document.getElementById(speichernId).setAttribute('style', 'display: none; width: 100px; float: right;');
			document.getElementById(abbrechenId).setAttribute('style', 'display: none; width: 100px; float: right;');
			// Umbaukosten entfernen
			document.getElementById("alertHinweis" + elementId).setAttribute('style', 'display: none;');
			// Anzeigen Button anzeigen
			var anzeigenId = "aendern" + elementId;
			document.getElementById(anzeigenId).setAttribute('style', 'width: 100px; float: right;');
		}
		// Wenn sich mindestens ein Wert geändert hat
		else{
			// Wenn Änderungskosten > 0
			if (aenderungskosten > 0){
				// Sicherheitsfrage
				var umbau = confirm(unescape("M%F6chtest du dieses Geb%E4ude wirklich f%FCr einen Gesamtpreis von " + aenderungskosten + "%u20AC umbauen?"));
			}
			else if (aenderungskosten < 0){
				// Sicherheitsfrage
				var umbau = confirm(unescape("M%F6chtest du dieses Geb%E4ude wirklich umbauen? Du erh%E4ltst eine R%FCckerstattung in H%F6he von " + (aenderungskosten * -1) + "%u20AC?"));
			}
			if (umbau || aenderungskosten == 0){
				// Wenn Finanzen ausreichen
				if (finanzen >= aenderungskosten){
					// Wartezeichen anzeigen
					document.getElementById("wait" + elementId).removeAttribute('style');
					// Ajax aufruf
					$.ajax({
						type: "POST",
						url: "/firma/speichern.php",
						cache: false,
						data: {id: elementId, stilIndex: stilNeu, pzgIndex: pzgNeu, szgIndex: szgNeu, spIndex: spNeu, tmIndex: tmNeu, twIndex: twNeu},
							success: function(data){
								// Wartezeichen ausblenden
								document.getElementById("wait" + elementId).setAttribute('style', 'display: none;');
								if(data=='ok' || data=='keinWertGeaendert'){
									// Meldung anzeigen dass alles gespeichert wurde
									document.getElementById("alertErfolg" + elementId).innerHTML = "<b>Erfolg!</b> Alle Werte wurden erfolgreich gespeichert";
									document.getElementById("alertErfolg" + elementId).removeAttribute('style');
									// Selectfelder disablen
									document.getElementById(stilId).setAttribute('disabled');
									document.getElementById(pzgId).setAttribute('disabled');
									document.getElementById(szgId).setAttribute('disabled');
									document.getElementById(spId).setAttribute('disabled');
									document.getElementById(tmId).setAttribute('disabled');
									document.getElementById(twId).setAttribute('disabled');
									// Speichern und Abbruch Button disablen
									var speichernId = "speichern" + elementId;
									var abbrechenId = "abbruch" + elementId;
									document.getElementById(speichernId).setAttribute('style', 'display: none; width: 100px; float: right;');
									document.getElementById(abbrechenId).setAttribute('style', 'display: none; width: 100px; float: right;');
									// Umbaukosten entfernen
									document.getElementById("alertHinweis" + elementId).setAttribute('style', 'display: none;');
									// Kontostand updaten
			                        var kontostandNeu = finanzen*1 - aenderungskosten*1;
									finanzen = kontostandNeu;
									$("#kontostand").html("Kontostand: <b>" + finanzen + " &euro;</b>");
									// Felder neu belegen
									document.getElementById("inputStil" + elementId).setAttribute('onchange', 'neuerStil(' + elementId + ', ' + stilNeu + ')');
									document.getElementById("inputSP" + elementId).setAttribute('onchange', 'neueSitzplaetze(' + elementId + ', ' + ( 20 + spNeu * 4) + ')');
									document.getElementById("inputTM" + elementId).setAttribute('onchange', 'neueMToiletten(' + elementId + ', ' + tmNeu + ')');
									document.getElementById("inputTW" + elementId).setAttribute('onchange', 'neueWToiletten(' + elementId + ', ' + twNeu + ')');
									// Anzeigen Button anzeigen
									var anzeigenId = "aendern" + elementId;
									document.getElementById(anzeigenId).setAttribute('style', 'width: 100px; float: right;');
								}
								else if(data=='nichtBesitzer'){
									document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
									document.getElementById("alertFehler" + elementId).removeAttribute('style');
								}
								else if(data=='finanzenNichtAusreichend'){
									document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Ihre Finanzen reichen f&uuml;r diesen Umbau nicht aus!");
									document.getElementById("alertFehler" + elementId).removeAttribute('style');
								}
								else if(data=='keinenIndex'){
									document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Bitte w&auml;hlen Sie einen Stil sowie die Anzahl der Sitzpl&auml;tze und Toiletten aus!");
									document.getElementById("alertFehler" + elementId).removeAttribute('style');
								}
								else if(data=='fehlerDateneintrag'){
									document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Das Geb&auml;ude konnte nicht entsprechend umgebaut werden!");
									document.getElementById("alertFehler" + elementId).removeAttribute('style');
								}
								else{
									document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
									document.getElementById("alertFehler" + elementId).removeAttribute('style');
								}
							}
					});
					return false;
				}
				// Wenn Finanzen nicht ausreichen
				else{
					document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Ihre Finanzen reichen f&uuml;r diesen Umbau nicht aus!");
					document.getElementById("alertFehler" + elementId).removeAttribute('style');
				}
			}
		}
	}
	
	// Funktion zum Anzeigen des Namenänderungsfeldes
	function feldNeuerName(elementId){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Altes Feld ausblenden
		document.getElementById("name" + elementId).setAttribute('style', 'display: none;');
		// Neues Feld anzeigen
		document.getElementById("inputFeld" + elementId).removeAttribute('style');
	}

	// Funktion zum ändern des Namens
	function neuerName(elementId, nameAlt){
		// Alle Meldungen entfernen
		document.getElementById("alertFehler" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("alertErfolg" + elementId).setAttribute('style', 'display: none;');
		// Wartezeichen anzeigen
		document.getElementById("wait" + elementId).removeAttribute('style');
		// Aktuellen Wert abfragen
		var nameNeu = document.getElementById("kneipenname" + elementId).value;
		// Wenn Feld leer
		if (nameNeu.length == 0){
			// Wartezeichen ausblenden
			document.getElementById("wait" + elementId).setAttribute('style', 'display: none;');
			// Hinweis
			document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Bitte geben Sie einen neuen Geb&auml;udenamen ein!");
			document.getElementById("alertFehler" + elementId).removeAttribute('style');
		}
		// Wenn neuer Gebäudename gesetzt
		else{
			// Prüfen, ob neuer Name gleich dem alten
			if (nameAlt == nameNeu){
				// Wartezeichen ausblenden
				document.getElementById("wait" + elementId).setAttribute('style', 'display: none;');
				// Meldung anzeigen dass alles gespeichert wurde
				document.getElementById("alertErfolg" + elementId).innerHTML = "<b>Erfolg!</b> Der neue Geb&auml;udename wurde erfolgreich gespeichert";
				document.getElementById("alertErfolg" + elementId).removeAttribute('style');
				// Neues Feld ausblenden
				document.getElementById("name" + elementId).removeAttribute('style');
				// Altes Feld anzeigen
				document.getElementById("inputFeld" + elementId).setAttribute('style', 'display: none;');
			}
			// Wenn neuer Name ungleich dem Alten
			else{
				// Ajax aufruf
				$.ajax({
					type: "POST",
					url: "/firma/neuerName.php",
					cache: false,
					data: {id: elementId, nameNeu: nameNeu},
						success: function(data){
							// Wartezeichen ausblenden
							document.getElementById("wait" + elementId).setAttribute('style', 'display: none;');
							if(data=='ok' || data=='keinWertGeaendert'){
								// Meldung anzeigen dass alles gespeichert wurde
								document.getElementById("alertErfolg" + elementId).innerHTML = "<b>Erfolg!</b> Der neue Geb&auml;udename wurde erfolgreich gespeichert";
								document.getElementById("alertErfolg" + elementId).removeAttribute('style');
								// Felder neu belegen
								document.getElementById("name" + elementId).innerHTML = "<h5>" + nameNeu + " <a class='pointer' style='font-size: 8px;' onclick='feldNeuerName(" + elementId + ")' title='Umbenennen'>edit</a></h5>";
								document.getElementById("inputFeld" + elementId).innerHTML = "Name: <input style='width: 100px; height: 15px;' type='text' id='kneipenname" + elementId + "' value='" + nameNeu + "' maxlength='30' placeholder='Kneipenname'><a class='pointer' style='font-size: 9px;' onclick='neuerName(" + elementId + ", \"" + nameNeu + "\")' title='Akzeptieren'>ok</a></h5>";
								// Neues Feld ausblenden
								document.getElementById("name" + elementId).removeAttribute('style');
								// Altes Feld anzeigen
								document.getElementById("inputFeld" + elementId).setAttribute('style', 'display: none;');
							}
							else if(data=='nichtBesitzer'){
								document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
								document.getElementById("alertFehler" + elementId).removeAttribute('style');
							}
							else if(data=='fehlerDateneintrag'){
								document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Der Geb&auml;udename konnte nicht ge&auml;ndert werden!");
								document.getElementById("alertFehler" + elementId).removeAttribute('style');
							}
							else{
								document.getElementById("alertFehler" + elementId).innerHTML = ("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
								document.getElementById("alertFehler" + elementId).removeAttribute('style');
							}
						}
				});
				return false;
			}
		}
	}
	
	// Funktion zum Bearbeiten der Getränkekarte
	function karteBearbeiten(elementId){
		// Id`s der Checkboxfelder zusammensetzen
		var pilsId = "b1_" + elementId;	var hellesId = "b2_" + elementId; var lagerId = "b3_" + elementId; var weissbierId = "b4_" + elementId; var schwarzbierId = "b5_" + elementId; var altbierId = "b6_" + elementId;
		var kornId = "sp1_" + elementId; var whiskeyId = "sp2_" + elementId; var wodkaId = "sp3_" + elementId; var obstbrandId = "sp4_" + elementId; var rumId = "sp5_" + elementId; var likeurId = "sp6_" + elementId;
		var almradlerId = "bm1_" + elementId; var radlerId = "bm2_" + elementId; var dieselId = "bm3_" + elementId; var goassId = "bm4_" + elementId; var colaweizenId = "bm5_" + elementId; var russId = "bm6_" + elementId;
		var sotbId = "c1_" + elementId; var liitId = "c2_" + elementId; var zombieId = "c3_" + elementId; var hurricaneId = "c4_" + elementId; var caipirinhaId = "c5_" + elementId; var mojitoId = "c6_" + elementId;
		var mineralwasserId = "af1_" + elementId; var colaId = "af2_" + elementId; var zlimoId = "af3_" + elementId; var olimoId = "af4_" + elementId; var apfelschorleId = "af5_" + elementId; var colamixId = "af6_" + elementId;
		var weissweinId = "w1_" + elementId; var rotweinId = "w2_" + elementId; var schaumweinId = "w3_" + elementId; var likeurweinId = "w4_" + elementId; var roseweinId = "w5_" + elementId;
		var asaftId = "s1_" + elementId; var osaftId = "s2_" + elementId; var ksaftId = "s3_" + elementId; var bsaftId = "s4_" + elementId; var jsaftId = "s5_" + elementId; var psaftId = "s6_" + elementId;
		// Alle Checkboxen aktivieren
		document.getElementById(pilsId).removeAttribute('disabled'); document.getElementById(hellesId).removeAttribute('disabled'); document.getElementById(lagerId).removeAttribute('disabled');
		document.getElementById(weissbierId).removeAttribute('disabled'); document.getElementById(schwarzbierId).removeAttribute('disabled'); document.getElementById(altbierId).removeAttribute('disabled');
		document.getElementById(kornId).removeAttribute('disabled'); document.getElementById(whiskeyId).removeAttribute('disabled'); document.getElementById(wodkaId).removeAttribute('disabled');
		document.getElementById(obstbrandId).removeAttribute('disabled'); document.getElementById(rumId).removeAttribute('disabled'); document.getElementById(likeurId).removeAttribute('disabled');
		document.getElementById(almradlerId).removeAttribute('disabled'); document.getElementById(radlerId).removeAttribute('disabled'); document.getElementById(dieselId).removeAttribute('disabled');
		document.getElementById(goassId).removeAttribute('disabled'); document.getElementById(colaweizenId).removeAttribute('disabled'); document.getElementById(russId).removeAttribute('disabled');
		document.getElementById(sotbId).removeAttribute('disabled'); document.getElementById(liitId).removeAttribute('disabled'); document.getElementById(zombieId).removeAttribute('disabled');
		document.getElementById(hurricaneId).removeAttribute('disabled'); document.getElementById(caipirinhaId).removeAttribute('disabled'); document.getElementById(mojitoId).removeAttribute('disabled');
		document.getElementById(mineralwasserId).removeAttribute('disabled'); document.getElementById(colaId).removeAttribute('disabled'); document.getElementById(zlimoId).removeAttribute('disabled');
		document.getElementById(olimoId).removeAttribute('disabled'); document.getElementById(apfelschorleId).removeAttribute('disabled'); document.getElementById(colamixId).removeAttribute('disabled');
		document.getElementById(weissweinId).removeAttribute('disabled'); document.getElementById(rotweinId).removeAttribute('disabled'); document.getElementById(schaumweinId).removeAttribute('disabled');
		document.getElementById(likeurweinId).removeAttribute('disabled'); document.getElementById(roseweinId).removeAttribute('disabled');
		document.getElementById(asaftId).removeAttribute('disabled'); document.getElementById(osaftId).removeAttribute('disabled'); document.getElementById(ksaftId).removeAttribute('disabled');
		document.getElementById(bsaftId).removeAttribute('disabled'); document.getElementById(jsaftId).removeAttribute('disabled'); document.getElementById(psaftId).removeAttribute('disabled');
		// Abfragen welcher Haken gesetzt
		var pils = document.getElementById(pilsId).checked;	var helles = document.getElementById(hellesId).checked;	var lager = document.getElementById(lagerId).checked;
		var weissbier = document.getElementById(weissbierId).checked; var schwarzbier = document.getElementById(schwarzbierId).checked;	var altbier = document.getElementById(altbierId).checked;
		var korn = document.getElementById(kornId).checked;	var whiskey = document.getElementById(whiskeyId).checked; var wodka = document.getElementById(wodkaId).checked;
		var obstbrand = document.getElementById(obstbrandId).checked; var rum = document.getElementById(rumId).checked;	var likeur = document.getElementById(likeurId).checked;
		var almradler = document.getElementById(almradlerId).checked;	var radler = document.getElementById(radlerId).checked; var diesel = document.getElementById(dieselId).checked;
		var goass = document.getElementById(goassId).checked; var colaweizen = document.getElementById(colaweizenId).checked;	var russ = document.getElementById(russId).checked;
		var sotb = document.getElementById(sotbId).checked;	var liit = document.getElementById(liitId).checked; var zombie = document.getElementById(zombieId).checked;
		var hurricane = document.getElementById(hurricaneId).checked; var caipirinha = document.getElementById(caipirinhaId).checked;	var mojito = document.getElementById(mojitoId).checked;
		var mineralwasser = document.getElementById(mineralwasserId).checked;	var cola = document.getElementById(colaId).checked; var zlimo = document.getElementById(zlimoId).checked;
		var olimo = document.getElementById(olimoId).checked; var apfelschorle = document.getElementById(apfelschorleId).checked;	var colamix = document.getElementById(colamixId).checked;
		var weisswein = document.getElementById(weissweinId).checked;	var rotwein = document.getElementById(rotweinId).checked; var schaumwein = document.getElementById(schaumweinId).checked;
		var likeurwein = document.getElementById(likeurweinId).checked; var rosewein = document.getElementById(roseweinId).checked;
		var asaft = document.getElementById(asaftId).checked;	var osaft = document.getElementById(osaftId).checked; var ksaft = document.getElementById(ksaftId).checked;
		var bsaft = document.getElementById(bsaftId).checked; var jsaft = document.getElementById(jsaftId).checked;	var psaft = document.getElementById(psaftId).checked;
		// Alle Inputfelder anzeigen und Preisfelder ausblenden
		document.getElementById("inputFeldPilsPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("pilsPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldHellesPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("hellesPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLagerPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("lagerPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWeissbierPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("weissbierPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldSchwarzbierPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("schwarzbierPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldAltbierPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("altbierPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldKornPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("kornPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWhiskeyPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("whiskeyPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWodkaPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("wodkaPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldObstbrandPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("obstbrandPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRumPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("rumPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLikeurPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("likeurPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldAlmradlerPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("almradlerPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRadlerPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("radlerPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldDieselPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("dieselPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldGoassPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("goassPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldColaweizenPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("colaweizenPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRussPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("russPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldSotbPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("sotbPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLiitPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("liitPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldZombiePreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("zombiePreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldHurricanePreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("hurricanePreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldCaipirinhaPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("caipirinhaPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldMojitoPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("mojitoPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldMineralwasserPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("mineralwasserPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldColaPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("colaPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldZlimoPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("zlimoPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldOlimoPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("olimoPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldApfelschorlePreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("apfelschorlePreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldColamixPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("colamixPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWeissweinPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("weissweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRotweinPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("rotweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldSchaumweinPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("schaumweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLikeurweinPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("likeurweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRoseweinPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("roseweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldAsaftPreis" + elementId).setAttribute('style', 'height: 18px;');
		document.getElementById("asaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldOsaftPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("osaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldKsaftPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("ksaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldBsaftPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("bsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldJsaftPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("jsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldPsaftPreis" + elementId).setAttribute('style', 'height: 18px; margin-top: 2px;');
		document.getElementById("psaftPreis" + elementId).setAttribute('style', 'display: none;');
		// Gecheckte Boxen zwischenspeichern
		gcPils = document.getElementById(pilsId).checked;	gcHelles = document.getElementById(hellesId).checked;	gcLager = document.getElementById(lagerId).checked;
		gcWeissbier = document.getElementById(weissbierId).checked; gcSchwarzbier = document.getElementById(schwarzbierId).checked;	gcAltbier = document.getElementById(altbierId).checked;
		gcKorn = document.getElementById(kornId).checked;	gcWhiskey = document.getElementById(whiskeyId).checked; gcWodka = document.getElementById(wodkaId).checked;
		gcObstbrand = document.getElementById(obstbrandId).checked; gcRum = document.getElementById(rumId).checked;	gcLikeur = document.getElementById(likeurId).checked;
		gcAlmradler = document.getElementById(almradlerId).checked;	gcRadler = document.getElementById(radlerId).checked; gcDiesel = document.getElementById(dieselId).checked;
		gcGoass = document.getElementById(goassId).checked; gcColaweizen = document.getElementById(colaweizenId).checked;	gcRuss = document.getElementById(russId).checked;
		gcSotb = document.getElementById(sotbId).checked;	gcLiit = document.getElementById(liitId).checked; gcZombie = document.getElementById(zombieId).checked;
		gcHurricane = document.getElementById(hurricaneId).checked; gcCaipirinha = document.getElementById(caipirinhaId).checked;	gcMojito = document.getElementById(mojitoId).checked;
		gcMineralwasser = document.getElementById(mineralwasserId).checked;	gcCola = document.getElementById(colaId).checked; gcZlimo = document.getElementById(zlimoId).checked;
		gcOlimo = document.getElementById(olimoId).checked; gcApfelschorle = document.getElementById(apfelschorleId).checked;	gcColamix = document.getElementById(colamixId).checked;
		gcWeisswein = document.getElementById(weissweinId).checked;	gcRotwein = document.getElementById(rotweinId).checked; gcSchaumwein = document.getElementById(schaumweinId).checked;
		gcLikeurwein = document.getElementById(likeurweinId).checked; gcRosewein = document.getElementById(roseweinId).checked;
		gcAsaft = document.getElementById(asaftId).checked;	gcOsaft = document.getElementById(osaftId).checked; gcKsaft = document.getElementById(ksaftId).checked;
		gcBsaft = document.getElementById(bsaftId).checked; gcJsaft = document.getElementById(jsaftId).checked;	gcPsaft = document.getElementById(psaftId).checked;
		// Speichernbutton, Abbruchbutton und zweiter Preisändernbutton erscheint
		document.getElementById("karteAbbruch" + elementId).setAttribute('style', 'width: 130px;');
		document.getElementById("karteSpeichern" + elementId).setAttribute('style', 'width: 130px;');
		// Kartebearbeitenbutton wird ausgeblendet
		document.getElementById("karteBearbeiten" + elementId).setAttribute('style', 'display: none;');
	}

	// Funktion zum Abbruch der Kartenänderung
	function karteAbbruch(elementId){
		// Id`s der Checkboxfelder zusammensetzen
		var pilsId = "b1_" + elementId;	var hellesId = "b2_" + elementId; var lagerId = "b3_" + elementId; var weissbierId = "b4_" + elementId; var schwarzbierId = "b5_" + elementId; var altbierId = "b6_" + elementId;
		var kornId = "sp1_" + elementId; var whiskeyId = "sp2_" + elementId; var wodkaId = "sp3_" + elementId; var obstbrandId = "sp4_" + elementId; var rumId = "sp5_" + elementId; var likeurId = "sp6_" + elementId;
		var almradlerId = "bm1_" + elementId; var radlerId = "bm2_" + elementId; var dieselId = "bm3_" + elementId; var goassId = "bm4_" + elementId; var colaweizenId = "bm5_" + elementId; var russId = "bm6_" + elementId;
		var sotbId = "c1_" + elementId; var liitId = "c2_" + elementId; var zombieId = "c3_" + elementId; var hurricaneId = "c4_" + elementId; var caipirinhaId = "c5_" + elementId; var mojitoId = "c6_" + elementId;
		var mineralwasserId = "af1_" + elementId; var colaId = "af2_" + elementId; var zlimoId = "af3_" + elementId; var olimoId = "af4_" + elementId; var apfelschorleId = "af5_" + elementId; var colamixId = "af6_" + elementId;
		var weissweinId = "w1_" + elementId; var rotweinId = "w2_" + elementId; var schaumweinId = "w3_" + elementId; var likeurweinId = "w4_" + elementId; var roseweinId = "w5_" + elementId;
		var asaftId = "s1_" + elementId; var osaftId = "s2_" + elementId; var ksaftId = "s3_" + elementId; var bsaftId = "s4_" + elementId; var jsaftId = "s5_" + elementId; var psaftId = "s6_" + elementId;
		// Alle Checkboxen deaktivieren
		document.getElementById(pilsId).setAttribute('disabled', 'disabled'); document.getElementById(hellesId).setAttribute('disabled', 'disabled'); document.getElementById(lagerId).setAttribute('disabled', 'disabled');
		document.getElementById(weissbierId).setAttribute('disabled', 'disabled'); document.getElementById(schwarzbierId).setAttribute('disabled', 'disabled'); document.getElementById(altbierId).setAttribute('disabled', 'disabled');
		document.getElementById(kornId).setAttribute('disabled', 'disabled'); document.getElementById(whiskeyId).setAttribute('disabled', 'disabled'); document.getElementById(wodkaId).setAttribute('disabled', 'disabled');
		document.getElementById(obstbrandId).setAttribute('disabled', 'disabled'); document.getElementById(rumId).setAttribute('disabled', 'disabled'); document.getElementById(likeurId).setAttribute('disabled', 'disabled');
		document.getElementById(almradlerId).setAttribute('disabled', 'disabled'); document.getElementById(radlerId).setAttribute('disabled', 'disabled'); document.getElementById(dieselId).setAttribute('disabled', 'disabled');
		document.getElementById(goassId).setAttribute('disabled', 'disabled'); document.getElementById(colaweizenId).setAttribute('disabled', 'disabled'); document.getElementById(russId).setAttribute('disabled', 'disabled');
		document.getElementById(sotbId).setAttribute('disabled', 'disabled'); document.getElementById(liitId).setAttribute('disabled', 'disabled'); document.getElementById(zombieId).setAttribute('disabled', 'disabled');
		document.getElementById(hurricaneId).setAttribute('disabled', 'disabled'); document.getElementById(caipirinhaId).setAttribute('disabled', 'disabled'); document.getElementById(mojitoId).setAttribute('disabled', 'disabled');
		document.getElementById(mineralwasserId).setAttribute('disabled', 'disabled'); document.getElementById(colaId).setAttribute('disabled', 'disabled'); document.getElementById(zlimoId).setAttribute('disabled', 'disabled');
		document.getElementById(olimoId).setAttribute('disabled', 'disabled'); document.getElementById(apfelschorleId).setAttribute('disabled', 'disabled'); document.getElementById(colamixId).setAttribute('disabled', 'disabled');
		document.getElementById(weissweinId).setAttribute('disabled', 'disabled'); document.getElementById(rotweinId).setAttribute('disabled', 'disabled'); document.getElementById(schaumweinId).setAttribute('disabled', 'disabled');
		document.getElementById(likeurweinId).setAttribute('disabled', 'disabled'); document.getElementById(roseweinId).setAttribute('disabled', 'disabled');
		document.getElementById(asaftId).setAttribute('disabled', 'disabled'); document.getElementById(osaftId).setAttribute('disabled', 'disabled'); document.getElementById(ksaftId).setAttribute('disabled', 'disabled');
		document.getElementById(bsaftId).setAttribute('disabled', 'disabled'); document.getElementById(jsaftId).setAttribute('disabled', 'disabled'); document.getElementById(psaftId).setAttribute('disabled', 'disabled');
		// Alte Werte wiederherstellen
		document.getElementById(pilsId).checked = gcPils; document.getElementById(hellesId).checked = gcHelles; document.getElementById(lagerId).checked = gcLager;
		document.getElementById(weissbierId).checked = gcWeissbier; document.getElementById(schwarzbierId).checked = gcSchwarzbier; document.getElementById(altbierId).checked = gcAltbier;
		document.getElementById(kornId).checked = gcKorn; document.getElementById(whiskeyId).checked = gcWhiskey; document.getElementById(wodkaId).checked = gcWodka;
		document.getElementById(obstbrandId).checked = gcObstbrand; document.getElementById(rumId).checked = gcRum; document.getElementById(likeurId).checked = gcLikeur;
		document.getElementById(almradlerId).checked = gcAlmradler; document.getElementById(radlerId).checked = gcRadler; document.getElementById(dieselId).checked = gcDiesel;
		document.getElementById(goassId).checked = gcGoass; document.getElementById(colaweizenId).checked = gcColaweizen; document.getElementById(russId).checked = gcRuss;
		document.getElementById(sotbId).checked = gcSotb; document.getElementById(liitId).checked = gcLiit; document.getElementById(zombieId).checked = gcZombie;
		document.getElementById(hurricaneId).checked = gcHurricane; document.getElementById(caipirinhaId).checked = gcCaipirinha; document.getElementById(mojitoId).checked = gcMojito;
		document.getElementById(mineralwasserId).checked = gcMineralwasser; document.getElementById(colaId).checked = gcCola; document.getElementById(zlimoId).checked = gcZlimo;
		document.getElementById(olimoId).checked = gcOlimo; document.getElementById(apfelschorleId).checked = gcApfelschorle; document.getElementById(colamixId).checked = gcColamix;
		document.getElementById(weissweinId).checked = gcWeisswein; document.getElementById(rotweinId).checked = gcRotwein; document.getElementById(schaumweinId).checked = gcSchaumwein;
		document.getElementById(likeurweinId).checked = gcLikeurwein; document.getElementById(roseweinId).checked = gcRosewein;
		document.getElementById(asaftId).checked = gcAsaft; document.getElementById(osaftId).checked = gcOsaft; document.getElementById(ksaftId).checked = gcKsaft;
		document.getElementById(bsaftId).checked = gcBsaft; document.getElementById(jsaftId).checked = gcJsaft; document.getElementById(psaftId).checked = gcPsaft;
		// Alle InputFelder wieder ausblenden
		document.getElementById("inputFeldPilsPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldHellesPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLagerPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWeissbierPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldSchwarzbierPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldAltbierPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldKornPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWhiskeyPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWodkaPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldObstbrandPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRumPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLikeurPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldAlmradlerPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRadlerPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldDieselPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldGoassPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldColaweizenPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRussPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldSotbPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLiitPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldZombiePreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldHurricanePreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldCaipirinhaPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldMojitoPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldMineralwasserPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldColaPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldZlimoPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldOlimoPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldApfelschorlePreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldColamixPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldWeissweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRotweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldSchaumweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldLikeurweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldRoseweinPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldAsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldOsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldKsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldBsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldJsaftPreis" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("inputFeldPsaftPreis" + elementId).setAttribute('style', 'display: none;');
		
		// Alle Preisfelder wieder einblenden
		document.getElementById("pilsPreis" + elementId).removeAttribute('style');
		document.getElementById("hellesPreis" + elementId).removeAttribute('style');
		document.getElementById("lagerPreis" + elementId).removeAttribute('style');
		document.getElementById("weissbierPreis" + elementId).removeAttribute('style');
		document.getElementById("schwarzbierPreis" + elementId).removeAttribute('style');
		document.getElementById("altbierPreis" + elementId).removeAttribute('style');
		document.getElementById("kornPreis" + elementId).removeAttribute('style');
		document.getElementById("whiskeyPreis" + elementId).removeAttribute('style');
		document.getElementById("wodkaPreis" + elementId).removeAttribute('style');
		document.getElementById("obstbrandPreis" + elementId).removeAttribute('style');
		document.getElementById("rumPreis" + elementId).removeAttribute('style');
		document.getElementById("likeurPreis" + elementId).removeAttribute('style');
		document.getElementById("almradlerPreis" + elementId).removeAttribute('style');
		document.getElementById("radlerPreis" + elementId).removeAttribute('style');
		document.getElementById("dieselPreis" + elementId).removeAttribute('style');
		document.getElementById("goassPreis" + elementId).removeAttribute('style');
		document.getElementById("colaweizenPreis" + elementId).removeAttribute('style');
		document.getElementById("russPreis" + elementId).removeAttribute('style');
		document.getElementById("sotbPreis" + elementId).removeAttribute('style');
		document.getElementById("liitPreis" + elementId).removeAttribute('style');
		document.getElementById("zombiePreis" + elementId).removeAttribute('style');
		document.getElementById("hurricanePreis" + elementId).removeAttribute('style');
		document.getElementById("caipirinhaPreis" + elementId).removeAttribute('style');
		document.getElementById("mojitoPreis" + elementId).removeAttribute('style');
		document.getElementById("mineralwasserPreis" + elementId).removeAttribute('style');
		document.getElementById("colaPreis" + elementId).removeAttribute('style');
		document.getElementById("zlimoPreis" + elementId).removeAttribute('style');
		document.getElementById("olimoPreis" + elementId).removeAttribute('style');
		document.getElementById("apfelschorlePreis" + elementId).removeAttribute('style');
		document.getElementById("colamixPreis" + elementId).removeAttribute('style');
		document.getElementById("weissweinPreis" + elementId).removeAttribute('style');
		document.getElementById("rotweinPreis" + elementId).removeAttribute('style');
		document.getElementById("schaumweinPreis" + elementId).removeAttribute('style');
		document.getElementById("likeurweinPreis" + elementId).removeAttribute('style');
		document.getElementById("roseweinPreis" + elementId).removeAttribute('style');
		document.getElementById("asaftPreis" + elementId).removeAttribute('style');
		document.getElementById("osaftPreis" + elementId).removeAttribute('style');
		document.getElementById("ksaftPreis" + elementId).removeAttribute('style');
		document.getElementById("bsaftPreis" + elementId).removeAttribute('style');
		document.getElementById("jsaftPreis" + elementId).removeAttribute('style');
		document.getElementById("psaftPreis" + elementId).removeAttribute('style');
		
		// Speichernbutton, Abbruchbutton und zweiter Preisändernbutton wird ausgeblendet
		document.getElementById("karteBearbeiten" + elementId).setAttribute('style', 'width: 265px;');
		
		// Kartebearbeitenbutton erscheint
		document.getElementById("karteAbbruch" + elementId).setAttribute('style', 'display: none;');
		document.getElementById("karteSpeichern" + elementId).setAttribute('style', 'display: none;');
	}

	// Funktion zum Speichern der neuen Karte
	function karteSpeichern(elementId){
		// Wartezeichen einblenden
		document.getElementById("wait2_" + elementId).removeAttribute('style');
		// Meldungen entfernen
		document.getElementById("alertFehler2" + elementId).setAttribute('style', 'display: none');
		document.getElementById("alertErfolg2" + elementId).setAttribute('style', 'display: none');
		// Zählvariable erstellen
		var anzahlGecheckt = 0;
		// String für elemente erstellen
		var alleGetraenke = "";
		// Id`s der Checkboxfelder zusammensetzen
		var pilsId = "b1_" + elementId;	var hellesId = "b2_" + elementId; var lagerId = "b3_" + elementId; var weissbierId = "b4_" + elementId; var schwarzbierId = "b5_" + elementId; var altbierId = "b6_" + elementId;
		var kornId = "sp1_" + elementId; var whiskeyId = "sp2_" + elementId; var wodkaId = "sp3_" + elementId; var obstbrandId = "sp4_" + elementId; var rumId = "sp5_" + elementId; var likeurId = "sp6_" + elementId;
		var almradlerId = "bm1_" + elementId; var radlerId = "bm2_" + elementId; var dieselId = "bm3_" + elementId; var goassId = "bm4_" + elementId; var colaweizenId = "bm5_" + elementId; var russId = "bm6_" + elementId;
		var sotbId = "c1_" + elementId; var liitId = "c2_" + elementId; var zombieId = "c3_" + elementId; var hurricaneId = "c4_" + elementId; var caipirinhaId = "c5_" + elementId; var mojitoId = "c6_" + elementId;
		var mineralwasserId = "af1_" + elementId; var colaId = "af2_" + elementId; var zlimoId = "af3_" + elementId; var olimoId = "af4_" + elementId; var apfelschorleId = "af5_" + elementId; var colamixId = "af6_" + elementId;
		var weissweinId = "w1_" + elementId; var rotweinId = "w2_" + elementId; var schaumweinId = "w3_" + elementId; var likeurweinId = "w4_" + elementId; var roseweinId = "w5_" + elementId;
		var asaftId = "s1_" + elementId; var osaftId = "s2_" + elementId; var ksaftId = "s3_" + elementId; var bsaftId = "s4_" + elementId; var jsaftId = "s5_" + elementId; var psaftId = "s6_" + elementId;
		// Abfragen welcher Haken gesetzt
		var pils = document.getElementById(pilsId).checked;	var helles = document.getElementById(hellesId).checked;	var lager = document.getElementById(lagerId).checked;
		var weissbier = document.getElementById(weissbierId).checked; var schwarzbier = document.getElementById(schwarzbierId).checked;	var altbier = document.getElementById(altbierId).checked;
		var korn = document.getElementById(kornId).checked;	var whiskey = document.getElementById(whiskeyId).checked; var wodka = document.getElementById(wodkaId).checked;
		var obstbrand = document.getElementById(obstbrandId).checked; var rum = document.getElementById(rumId).checked;	var likeur = document.getElementById(likeurId).checked;
		var almradler = document.getElementById(almradlerId).checked;	var radler = document.getElementById(radlerId).checked; var diesel = document.getElementById(dieselId).checked;
		var goass = document.getElementById(goassId).checked; var colaweizen = document.getElementById(colaweizenId).checked;	var russ = document.getElementById(russId).checked;
		var sotb = document.getElementById(sotbId).checked;	var liit = document.getElementById(liitId).checked; var zombie = document.getElementById(zombieId).checked;
		var hurricane = document.getElementById(hurricaneId).checked; var caipirinha = document.getElementById(caipirinhaId).checked;	var mojito = document.getElementById(mojitoId).checked;
		var mineralwasser = document.getElementById(mineralwasserId).checked;	var cola = document.getElementById(colaId).checked; var zlimo = document.getElementById(zlimoId).checked;
		var olimo = document.getElementById(olimoId).checked; var apfelschorle = document.getElementById(apfelschorleId).checked;	var colamix = document.getElementById(colamixId).checked;
		var weisswein = document.getElementById(weissweinId).checked;	var rotwein = document.getElementById(rotweinId).checked; var schaumwein = document.getElementById(schaumweinId).checked;
		var likeurwein = document.getElementById(likeurweinId).checked; var rosewein = document.getElementById(roseweinId).checked;
		var asaft = document.getElementById(asaftId).checked;	var osaft = document.getElementById(osaftId).checked; var ksaft = document.getElementById(ksaftId).checked;
		var bsaft = document.getElementById(bsaftId).checked; var jsaft = document.getElementById(jsaftId).checked;	var psaft = document.getElementById(psaftId).checked;
		if (pils){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (helles){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (lager){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (weissbier){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (schwarzbier){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (altbier){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (korn){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (whiskey){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (wodka){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (obstbrand){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (rum){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (likeur){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (almradler){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (radler){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (diesel){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (goass){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (colaweizen){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (russ){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (sotb){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (liit){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (zombie){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (hurricane){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (caipirinha){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (mojito){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (mineralwasser){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (cola){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (zlimo){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (olimo){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (apfelschorle){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (colamix){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (weisswein){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (rotwein){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (schaumwein){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (likeurwein){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (rosewein){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (asaft){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (osaft){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (ksaft){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (bsaft){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (jsaft){
			anzahlGecheckt++;
			alleGetraenke += "1|";
		}
		else{
			alleGetraenke += "0|";
		}
		if (psaft){
			anzahlGecheckt++;
			alleGetraenke += "1";
		}
		else{
			alleGetraenke += "0";
		}
		// Alle Preise abfragen
		var preisPils = document.getElementById("inputPilsPreis" + elementId).value;
		var preisHelles = document.getElementById("inputHellesPreis" + elementId).value;
		var preisLager = document.getElementById("inputLagerPreis" + elementId).value;
		var preisWeissbier = document.getElementById("inputWeissbierPreis" + elementId).value;
		var preisSchwarzbier = document.getElementById("inputSchwarzbierPreis" + elementId).value;
		var preisAltbier = document.getElementById("inputAltbierPreis" + elementId).value;
		var preisKorn = document.getElementById("inputKornPreis" + elementId).value;
		var preisWhiskey = document.getElementById("inputWhiskeyPreis" + elementId).value;
		var preisWodka = document.getElementById("inputWodkaPreis" + elementId).value;
		var preisObstbrand = document.getElementById("inputObstbrandPreis" + elementId).value;
		var preisRum = document.getElementById("inputRumPreis" + elementId).value;
		var preisLikeur = document.getElementById("inputLikeurPreis" + elementId).value;
		var preisAlmradler = document.getElementById("inputAlmradlerPreis" + elementId).value;
		var preisRadler = document.getElementById("inputRadlerPreis" + elementId).value;
		var preisDiesel = document.getElementById("inputDieselPreis" + elementId).value;
		var preisGoass = document.getElementById("inputGoassPreis" + elementId).value;
		var preisColaweizen = document.getElementById("inputColaweizenPreis" + elementId).value;
		var preisRuss = document.getElementById("inputRussPreis" + elementId).value;
		var preisSotb = document.getElementById("inputSotbPreis" + elementId).value;
		var preisLiit = document.getElementById("inputLiitPreis" + elementId).value;
		var preisZombie = document.getElementById("inputZombiePreis" + elementId).value;
		var preisHurricane = document.getElementById("inputHurricanePreis" + elementId).value;
		var preisCaipirinha = document.getElementById("inputCaipirinhaPreis" + elementId).value;
		var preisMojito = document.getElementById("inputMojitoPreis" + elementId).value;
		var preisMineralwasser = document.getElementById("inputMineralwasserPreis" + elementId).value;
		var preisCola = document.getElementById("inputColaPreis" + elementId).value;
		var preisZlimo = document.getElementById("inputZlimoPreis" + elementId).value;
		var preisOlimo = document.getElementById("inputOlimoPreis" + elementId).value;
		var preisApfelschorle = document.getElementById("inputApfelschorlePreis" + elementId).value;
		var preisColamix = document.getElementById("inputColamixPreis" + elementId).value;
		var preisWeisswein = document.getElementById("inputWeissweinPreis" + elementId).value;
		var preisRotwein = document.getElementById("inputRotweinPreis" + elementId).value;
		var preisSchaumwein = document.getElementById("inputSchaumweinPreis" + elementId).value;
		var preisLikeurwein = document.getElementById("inputLikeurweinPreis" + elementId).value;
		var preisRosewein = document.getElementById("inputRoseweinPreis" + elementId).value;
		var preisAsaft = document.getElementById("inputAsaftPreis" + elementId).value;
		var preisOsaft = document.getElementById("inputOsaftPreis" + elementId).value;
		var preisKsaft = document.getElementById("inputKsaftPreis" + elementId).value;
		var preisBsaft = document.getElementById("inputBsaftPreis" + elementId).value;
		var preisJsaft = document.getElementById("inputJsaftPreis" + elementId).value;
		var preisPsaft = document.getElementById("inputPsaftPreis" + elementId).value;
		
		// Wenn nicht alle Preise positiv sind
		if(!(isNumber(preisPils) > 0 && isNumber(preisHelles) > 0 && isNumber(preisLager) > 0 && isNumber(preisWeissbier) > 0 && isNumber(preisSchwarzbier) > 0 && isNumber(preisAltbier) > 0
		  && isNumber(preisKorn) > 0 && isNumber(preisWhiskey) > 0 && isNumber(preisWodka) > 0 && isNumber(preisObstbrand) > 0 && isNumber(preisRum) > 0 && isNumber(preisLikeur) > 0
		  && isNumber(preisAlmradler) > 0 && isNumber(preisRadler) > 0 && isNumber(preisDiesel) > 0 && isNumber(preisGoass) > 0 && isNumber(preisColaweizen) > 0 && isNumber(preisRuss) > 0
		  && isNumber(preisSotb) > 0 && isNumber(preisLiit) > 0 && isNumber(preisZombie) > 0 && isNumber(preisHurricane) > 0 && isNumber(preisCaipirinha) > 0 && isNumber(preisMojito) > 0
		  && isNumber(preisMineralwasser) > 0 && isNumber(preisCola) > 0 && isNumber(preisZlimo) > 0 && isNumber(preisOlimo) > 0 && isNumber(preisApfelschorle) > 0 && isNumber(preisColamix) > 0
		  && isNumber(preisWeisswein) > 0 && isNumber(preisRotwein) > 0 && isNumber(preisSchaumwein) > 0 && isNumber(preisLikeurwein) > 0 && isNumber(preisRosewein) > 0
		  && isNumber(preisAsaft) > 0 && isNumber(preisOsaft) > 0 && isNumber(preisKsaft) > 0 && isNumber(preisBsaft) > 0 && isNumber(preisJsaft) > 0 && isNumber(preisPsaft) > 0)){
			// Wartezeichen ausblenden
			document.getElementById("wait2_" + elementId).setAttribute('style', 'display: none;');
			// Meldung, dass alle Felder korrekt ausgefüllt sein müssen
			document.getElementById("alertFehler2" + elementId).innerHTML = "<b>Fehler!</b> Bitte f&uuml;llen Sie alle Preisfelder mit korrekten Werten aus!";
			document.getElementById("alertFehler2" + elementId).removeAttribute('style');
		}
		// Wenn alle Preise positv sind
		else{
			// Wenn mehr gecheckt als erforscht
			if (anzahlGecheckt == 0){
				// Wartezeichen ausblenden
				document.getElementById("wait2_" + elementId).setAttribute('style', 'display: none;');
				// Meldung, dass keine Getränke ausgewählt sind
				document.getElementById("alertFehler2" + elementId).innerHTML = "<b>Fehler!</b> Bitte w&auml;hlen Sie mindestens ein Getr&auml;nk aus!";
				document.getElementById("alertFehler2" + elementId).removeAttribute('style');
			}
			else if(anzahlGecheckt > 10){
				// Wartezeichen ausblenden
				document.getElementById("wait2_" + elementId).setAttribute('style', 'display: none;');
				// Meldung, dass zuviele Getränke ausgewählt sind
				document.getElementById("alertFehler2" + elementId).innerHTML = "<b>Fehler!</b> Sie mehr Getr&auml;nke ausgew&auml;hlt, als Ihnen Kartenpl&auml;tze zur Verf&uuml;gung stehen!";
				document.getElementById("alertFehler2" + elementId).removeAttribute('style');
			}
			else{
				// Alle Preise an einen String anfügen
				var allePreise = preisPils + "|" + preisHelles + "|" + preisLager + "|" + preisWeissbier + "|" + preisSchwarzbier + "|" + preisAltbier
				 + "|" + preisKorn + "|" + preisWhiskey + "|" + preisWodka + "|" + preisObstbrand + "|" + preisRum + "|" + preisLikeur
				 + "|" + preisAlmradler + "|" + preisRadler + "|" + preisDiesel + "|" + preisGoass + "|" + preisColaweizen + "|" + preisRuss
				 + "|" + preisSotb + "|" + preisLiit + "|" + preisZombie + "|" + preisHurricane + "|" + preisCaipirinha + "|" + preisMojito
				 + "|" + preisMineralwasser + "|" + preisCola + "|" + preisZlimo + "|" + preisOlimo + "|" + preisApfelschorle + "|" + preisColamix
				 + "|" + preisWeisswein + "|" + preisRotwein + "|" + preisSchaumwein + "|" + preisLikeurwein + "|" + preisRosewein
				 + "|" + preisAsaft + "|" + preisOsaft + "|" + preisKsaft + "|" + preisBsaft + "|" + preisJsaft + "|" + preisPsaft;
				// Ajax aufruf
				$.ajax({
					type: "POST",
					url: "/firma/neueKarte.php",
					cache: false,
					data: {id: elementId, alleGetraenke: alleGetraenke, allePreise: allePreise},
						success: function(data){
							// Wartezeichen ausblenden
							document.getElementById("wait2_" + elementId).setAttribute('style', 'display: none;');
							if(data=='ok'){
								// Meldung anzeigen dass alles gespeichert wurde
								document.getElementById("alertErfolg2" + elementId).innerHTML = "<b>Erfolg!</b> Alle Werte wurden erfolgreich gespeichert";
								document.getElementById("alertErfolg2" + elementId).removeAttribute('style');
								// Preisfelder neu belegen
								document.getElementById("pilsPreis" + elementId).innerHTML = preisPils + " &euro;";
								document.getElementById("hellesPreis" + elementId).innerHTML = preisHelles + " &euro;";
								document.getElementById("lagerPreis" + elementId).innerHTML = preisLager + " &euro;";
								document.getElementById("weissbierPreis" + elementId).innerHTML = preisWeissbier + " &euro;";
								document.getElementById("schwarzbierPreis" + elementId).innerHTML = preisSchwarzbier + " &euro;";
								document.getElementById("altbierPreis" + elementId).innerHTML = preisAltbier + " &euro;";
								document.getElementById("kornPreis" + elementId).innerHTML = preisKorn + " &euro;";
								document.getElementById("whiskeyPreis" + elementId).innerHTML = preisWhiskey + " &euro;";
								document.getElementById("wodkaPreis" + elementId).innerHTML = preisWodka + " &euro;";
								document.getElementById("obstbrandPreis" + elementId).innerHTML = preisObstbrand + " &euro;";
								document.getElementById("rumPreis" + elementId).innerHTML = preisRum + " &euro;";
								document.getElementById("likeurPreis" + elementId).innerHTML = preisLikeur + " &euro;";
								document.getElementById("almradlerPreis" + elementId).innerHTML = preisAlmradler + " &euro;";
								document.getElementById("radlerPreis" + elementId).innerHTML = preisRadler + " &euro;";
								document.getElementById("dieselPreis" + elementId).innerHTML = preisDiesel + " &euro;";
								document.getElementById("goassPreis" + elementId).innerHTML = preisGoass + " &euro;";
								document.getElementById("colaweizenPreis" + elementId).innerHTML = preisColaweizen + " &euro;";
								document.getElementById("russPreis" + elementId).innerHTML = preisRuss + " &euro;";
								document.getElementById("sotbPreis" + elementId).innerHTML = preisSotb + " &euro;";
								document.getElementById("liitPreis" + elementId).innerHTML = preisLiit + " &euro;";
								document.getElementById("zombiePreis" + elementId).innerHTML = preisZombie + " &euro;";
								document.getElementById("hurricanePreis" + elementId).innerHTML = preisHurricane + " &euro;";
								document.getElementById("caipirinhaPreis" + elementId).innerHTML = preisCaipirinha + " &euro;";
								document.getElementById("mojitoPreis" + elementId).innerHTML = preisMojito + " &euro;";
								document.getElementById("mineralwasserPreis" + elementId).innerHTML = preisMineralwasser + " &euro;";
								document.getElementById("colaPreis" + elementId).innerHTML = preisCola + " &euro;";
								document.getElementById("zlimoPreis" + elementId).innerHTML = preisZlimo + " &euro;";
								document.getElementById("olimoPreis" + elementId).innerHTML = preisOlimo + " &euro;";
								document.getElementById("apfelschorlePreis" + elementId).innerHTML = preisApfelschorle + " &euro;";
								document.getElementById("colamixPreis" + elementId).innerHTML = preisColamix + " &euro;";
								document.getElementById("weissweinPreis" + elementId).innerHTML = preisWeisswein + " &euro;";
								document.getElementById("rotweinPreis" + elementId).innerHTML = preisRotwein + " &euro;";
								document.getElementById("schaumweinPreis" + elementId).innerHTML = preisSchaumwein + " &euro;";
								document.getElementById("likeurweinPreis" + elementId).innerHTML = preisLikeurwein + " &euro;";
								document.getElementById("roseweinPreis" + elementId).innerHTML = preisRosewein + " &euro;";
								document.getElementById("asaftPreis" + elementId).innerHTML = preisAsaft + " &euro;";
								document.getElementById("osaftPreis" + elementId).innerHTML = preisOsaft + " &euro;";
								document.getElementById("ksaftPreis" + elementId).innerHTML = preisKsaft + " &euro;";
								document.getElementById("bsaftPreis" + elementId).innerHTML = preisBsaft + " &euro;";
								document.getElementById("jsaftPreis" + elementId).innerHTML = preisJsaft + " &euro;";
								document.getElementById("psaftPreis" + elementId).innerHTML = preisPsaft + " &euro;";
								// Inputfelder ausblenden
								document.getElementById("inputFeldPilsPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldHellesPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldLagerPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldWeissbierPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldSchwarzbierPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldAltbierPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldKornPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldWhiskeyPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldWodkaPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldObstbrandPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldRumPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldLikeurPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldAlmradlerPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldRadlerPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldDieselPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldGoassPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldColaweizenPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldRussPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldSotbPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldLiitPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldZombiePreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldHurricanePreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldCaipirinhaPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldMojitoPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldMineralwasserPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldColaPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldZlimoPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldOlimoPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldApfelschorlePreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldColamixPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldWeissweinPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldRotweinPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldSchaumweinPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldLikeurweinPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldRoseweinPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldAsaftPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldOsaftPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldKsaftPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldBsaftPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldJsaftPreis" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("inputFeldPsaftPreis" + elementId).setAttribute('style', 'display: none;');
								// Preisfelder einblenden
								document.getElementById("pilsPreis" + elementId).removeAttribute('style');
								document.getElementById("hellesPreis" + elementId).removeAttribute('style');
								document.getElementById("lagerPreis" + elementId).removeAttribute('style');
								document.getElementById("weissbierPreis" + elementId).removeAttribute('style');
								document.getElementById("schwarzbierPreis" + elementId).removeAttribute('style');
								document.getElementById("altbierPreis" + elementId).removeAttribute('style');
								document.getElementById("kornPreis" + elementId).removeAttribute('style');
								document.getElementById("whiskeyPreis" + elementId).removeAttribute('style');
								document.getElementById("wodkaPreis" + elementId).removeAttribute('style');
								document.getElementById("obstbrandPreis" + elementId).removeAttribute('style');
								document.getElementById("rumPreis" + elementId).removeAttribute('style');
								document.getElementById("likeurPreis" + elementId).removeAttribute('style');
								document.getElementById("almradlerPreis" + elementId).removeAttribute('style');
								document.getElementById("radlerPreis" + elementId).removeAttribute('style');
								document.getElementById("dieselPreis" + elementId).removeAttribute('style');
								document.getElementById("goassPreis" + elementId).removeAttribute('style');
								document.getElementById("colaweizenPreis" + elementId).removeAttribute('style');
								document.getElementById("russPreis" + elementId).removeAttribute('style');
								document.getElementById("sotbPreis" + elementId).removeAttribute('style');
								document.getElementById("liitPreis" + elementId).removeAttribute('style');
								document.getElementById("zombiePreis" + elementId).removeAttribute('style');
								document.getElementById("hurricanePreis" + elementId).removeAttribute('style');
								document.getElementById("caipirinhaPreis" + elementId).removeAttribute('style');
								document.getElementById("mojitoPreis" + elementId).removeAttribute('style');
								document.getElementById("mineralwasserPreis" + elementId).removeAttribute('style');
								document.getElementById("colaPreis" + elementId).removeAttribute('style');
								document.getElementById("zlimoPreis" + elementId).removeAttribute('style');
								document.getElementById("olimoPreis" + elementId).removeAttribute('style');
								document.getElementById("apfelschorlePreis" + elementId).removeAttribute('style');
								document.getElementById("colamixPreis" + elementId).removeAttribute('style');
								document.getElementById("weissweinPreis" + elementId).removeAttribute('style');
								document.getElementById("rotweinPreis" + elementId).removeAttribute('style');
								document.getElementById("schaumweinPreis" + elementId).removeAttribute('style');
								document.getElementById("likeurweinPreis" + elementId).removeAttribute('style');
								document.getElementById("roseweinPreis" + elementId).removeAttribute('style');
								document.getElementById("asaftPreis" + elementId).removeAttribute('style');
								document.getElementById("osaftPreis" + elementId).removeAttribute('style');
								document.getElementById("ksaftPreis" + elementId).removeAttribute('style');
								document.getElementById("bsaftPreis" + elementId).removeAttribute('style');
								document.getElementById("jsaftPreis" + elementId).removeAttribute('style');
								document.getElementById("psaftPreis" + elementId).removeAttribute('style');
								// Alle Felder disablen
								document.getElementById(pilsId).setAttribute('disabled', 'disabled'); document.getElementById(hellesId).setAttribute('disabled', 'disabled'); document.getElementById(lagerId).setAttribute('disabled', 'disabled');
								document.getElementById(weissbierId).setAttribute('disabled', 'disabled'); document.getElementById(schwarzbierId).setAttribute('disabled', 'disabled'); document.getElementById(altbierId).setAttribute('disabled', 'disabled');
								document.getElementById(kornId).setAttribute('disabled', 'disabled'); document.getElementById(whiskeyId).setAttribute('disabled', 'disabled'); document.getElementById(wodkaId).setAttribute('disabled', 'disabled');
								document.getElementById(obstbrandId).setAttribute('disabled', 'disabled'); document.getElementById(rumId).setAttribute('disabled', 'disabled'); document.getElementById(likeurId).setAttribute('disabled', 'disabled');
								document.getElementById(almradlerId).setAttribute('disabled', 'disabled'); document.getElementById(radlerId).setAttribute('disabled', 'disabled'); document.getElementById(dieselId).setAttribute('disabled', 'disabled');
								document.getElementById(goassId).setAttribute('disabled', 'disabled'); document.getElementById(colaweizenId).setAttribute('disabled', 'disabled'); document.getElementById(russId).setAttribute('disabled', 'disabled');
								document.getElementById(sotbId).setAttribute('disabled', 'disabled'); document.getElementById(liitId).setAttribute('disabled', 'disabled'); document.getElementById(zombieId).setAttribute('disabled', 'disabled');
								document.getElementById(hurricaneId).setAttribute('disabled', 'disabled'); document.getElementById(caipirinhaId).setAttribute('disabled', 'disabled'); document.getElementById(mojitoId).setAttribute('disabled', 'disabled');
								document.getElementById(mineralwasserId).setAttribute('disabled', 'disabled'); document.getElementById(colaId).setAttribute('disabled', 'disabled'); document.getElementById(zlimoId).setAttribute('disabled', 'disabled');
								document.getElementById(olimoId).setAttribute('disabled', 'disabled'); document.getElementById(apfelschorleId).setAttribute('disabled', 'disabled'); document.getElementById(colamixId).setAttribute('disabled', 'disabled');
								document.getElementById(weissweinId).setAttribute('disabled', 'disabled'); document.getElementById(rotweinId).setAttribute('disabled', 'disabled'); document.getElementById(schaumweinId).setAttribute('disabled', 'disabled');
								document.getElementById(likeurweinId).setAttribute('disabled', 'disabled'); document.getElementById(roseweinId).setAttribute('disabled', 'disabled');
								document.getElementById(asaftId).setAttribute('disabled', 'disabled'); document.getElementById(osaftId).setAttribute('disabled', 'disabled'); document.getElementById(ksaftId).setAttribute('disabled', 'disabled');
								document.getElementById(bsaftId).setAttribute('disabled', 'disabled'); document.getElementById(jsaftId).setAttribute('disabled', 'disabled'); document.getElementById(psaftId).setAttribute('disabled', 'disabled');
								// Speichernbutton, Abbruchbutton und zweiter Preisändernbutton wird ausgeblendet
								document.getElementById("karteBearbeiten" + elementId).setAttribute('style', 'width: 265px;');
								// Kartebearbeitenbutton erscheint
								document.getElementById("karteAbbruch" + elementId).setAttribute('style', 'display: none;');
								document.getElementById("karteSpeichern" + elementId).setAttribute('style', 'display: none;');
								/*
								// Felder neu belegen
								document.getElementById("name" + elementId).innerHTML = "<h5>" + nameNeu + " <a class='pointer' style='font-size: 8px;' onclick='feldNeuerName(" + elementId + ")' title='Umbenennen'>edit</a></h5>";
								document.getElementById("inputFeld" + elementId).innerHTML = "Name: <input style='width: 100px; height: 15px;' type='text' id='kneipenname" + elementId + "' value='" + nameNeu + "' maxlength='30' placeholder='Kneipenname'><a class='pointer' style='font-size: 9px;' onclick='neuerName(" + elementId + ", \"" + nameNeu + "\")' title='Akzeptieren'>ok</a></h5>";
								// Neues Feld ausblenden
								document.getElementById("name" + elementId).removeAttribute('style');
								// Altes Feld anzeigen
								document.getElementById("inputFeld" + elementId).setAttribute('style', 'display: none;');
								*/
							}
							else if(data=='nichtBesitzer'){
								document.getElementById("alertFehler2" + elementId).innerHTML = ("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
								document.getElementById("alertFehler2" + elementId).removeAttribute('style');
							}
							else if(data=='fehlerDateneintrag'){
								document.getElementById("alertFehler2" + elementId).innerHTML = ("<b>Fehler!</b> Die Getr&auml;nkekarte konnte nicht aktualisiert werden!");
								document.getElementById("alertFehler2" + elementId).removeAttribute('style');
							}
							else{
								document.getElementById("alertFehler2" + elementId).innerHTML = ("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
								document.getElementById("alertFehler2" + elementId).removeAttribute('style');
							}
						}
				});
				return false;
			}
		}
	}


	// Funktion zum Prüfen, ob die Zahl valide ist
	function isNumber(eingabe){
		// Prüfen, ob etwas eingegeben wurde
		if (eingabe.length != 0){
			var startnummer = /^\d/;
			// Wenn Eingabe mit einer Zahl beginnt
			if (startnummer.exec(eingabe)){
				var number = /^\d*(\.\d+)?$/;
				// Wenn die Nummer valide ist
				if (number.exec(eingabe)){
					// Wenn Preis kleiner als 10
					if (eingabe < 10){
						return true;
					}
					// Wenn Preis größer gleich 10
					else{
						return false;
					}
				}
				// Wenn die Nummer nich valide ist
				else{
					return false;
				}
			}
			// Wenn Eingabe mit etwas anderem beginnt
			else{
				return false;
			}
		}
		// Wenn nichts eingegeben wurde
		else{
			return false;
		}
	}

	jQuery(function ($) {
        $("a").tooltip()
    });

	// Funktion zum Setzen der Getränkeart
	function setGetraenk(elementId){
		// Meldungen verstecken
		var fehlerId = "alertFehler3" + elementId;
		var erfolgId = "alertErfolg3" + elementId;
		document.getElementById(fehlerId).setAttribute('style', 'display: none;');
		document.getElementById(erfolgId).setAttribute('style', 'display: none;');
		// Gaststätte festlegen
		gaststaette = elementId;
		// Selektiertes Element holen
		gattung = document.getElementById('inputArt' + elementId).selectedIndex;
		// Auswahl zurücksetzen
		document.getElementById('bier' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('spirituosen' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('biermisch' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('alkoholfrei' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('saefte' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('cocktails' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('weine' + elementId).setAttribute('style', 'display: none;');
		// AnzahlAuswahl zurücksetzen
		document.getElementById('bierAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('spirituosenAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('biermischAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('alkoholfreiAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('saefteAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('cocktailsAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		document.getElementById('weineAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
		// Wenn Art gewählt
		if (gattung == 1){
			document.getElementById('bier' + elementId).removeAttribute('style');
			artName = "v";
		}
		else if (gattung == 2){
			document.getElementById('spirituosen' + elementId).removeAttribute('style');
			artName = "p";
		}
		else if (gattung == 3){
			document.getElementById('biermisch' + elementId).removeAttribute('style');
			artName = "f";
		}
		else if (gattung == 4){
			document.getElementById('alkoholfrei' + elementId).removeAttribute('style');
			artName = "l";
		}
		else if (gattung == 5){
			document.getElementById('saefte' + elementId).removeAttribute('style');
			artName = "t";
		}
		else if (gattung == 6){
			document.getElementById('cocktails' + elementId).removeAttribute('style');
			artName = "s";
		}
		else if (gattung == 7){
			document.getElementById('weine' + elementId).removeAttribute('style');
			artName = "s";
		}
		checkForm(elementId);
	}
	// Prüft ob alle Felder ausgefüllt sind
	function checkForm(elementId){
		// Meldungen verstecken
		var fehlerId = "alertFehler3" + elementId;
		var erfolgId = "alertErfolg3" + elementId;
		document.getElementById(fehlerId).setAttribute('style', 'display: none;');
		document.getElementById(erfolgId).setAttribute('style', 'display: none;');
		// Variablen initialisieren
		art = 0;
		// Art abfragen
		gattung = document.getElementById('inputArt' + elementId).selectedIndex;
		// Wenn art gewählt
		if (gattung >= 1 && gattung <= 7){
			// Wenn bier gewählt
			if (gattung == 1){
				// Bierauswahl holen
				var bier = document.getElementById('inputBier' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (bier >= 1 && bier <= 6){
					// Bierauswahl zeigen
					document.getElementById('bierAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (bier == 1){
						art = 11;
						einzelpreis = pPils;
					}
					else if (bier == 2){
						art = 12;
						einzelpreis = pHelles;
					}
					else if (bier == 3){
						art = 13;
						einzelpreis = pLager;
					}
					else if (bier == 4){
						art = 14;
						einzelpreis = pWeissbier;
					}
					else if (bier == 5){
						art = 15;
						einzelpreis = pSchwarzbier;
					}
					else if (bier == 6){
						art = 16;
						einzelpreis = pHelles;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("bierAnzahl" + elementId).select();
					document.getElementById("kostenBiere" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// Bierauswahl verstecken
					document.getElementById('bierAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
			}
			// Wenn spirituosen gewählt
			else if (gattung == 2){
				// Spirituosenauswahl holen
				var spirituosen = document.getElementById('inputSpirituosen' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (spirituosen >= 1 && spirituosen <= 6){
					// Spirituosenauswahl zeigen
					document.getElementById('spirituosenAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (spirituosen == 1){
						art = 21;
						einzelpreis = pKorn;
					}
					else if (spirituosen == 2){
						art = 22;
						einzelpreis = pWhiskey;
					}
					else if (spirituosen == 3){
						art = 23;
						einzelpreis = pWodka;
					}
					else if (spirituosen == 4){
						art = 24;
						einzelpreis = pObstbrand;
					}
					else if (spirituosen == 5){
						art = 25;
						einzelpreis = pRum;
					}
					else if (spirituosen == 6){
						art = 26;
						einzelpreis = pLikeur;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("spirituosenAnzahl" + elementId).select();
					document.getElementById("kostenSpirituosen" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// Spirituosenauswahl verstecken
					document.getElementById('spirituosenAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
			}
			// Wenn biermischgetränke gewählt
			else if (gattung == 3){
				// biermischgetränkeauswahl holen
				var biermisch = document.getElementById('inputBiermisch' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (biermisch >= 1 && biermisch <= 6){
					// biermischgetränkeauswahl zeigen
					document.getElementById('biermischAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (biermisch == 1){
						art = 31;
						einzelpreis = pAlmradler;
					}
					else if (biermisch == 2){
						art = 32;
						einzelpreis = pRadler;
					}
					else if (biermisch == 3){
						art = 33;
						einzelpreis = pDiesel;
					}
					else if (biermisch == 4){
						art = 34;
						einzelpreis = pGoass;
					}
					else if (biermisch == 5){
						art = 35;
						einzelpreis = pColaweizen;
					}
					else if (biermisch == 6){
						art = 36;
						einzelpreis = pRuss;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("biermischAnzahl" + elementId).select();
					document.getElementById("kostenBiermisch" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// biermischgetränkeauswahl verstecken
					document.getElementById('biermischAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
			}
			// Wenn alkoholfreie Getränke gewählt
			else if (gattung == 4){
				// alkoholfreie Getränke holen
				var alkfrei = document.getElementById('inputAlkoholfrei' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (alkfrei >= 1 && alkfrei <= 6){
					// alkoholfreie Getränkeauswahl zeigen
					document.getElementById('alkoholfreiAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (alkfrei == 1){
						art = 41;
						einzelpreis = pMineralwasser;
					}
					else if (alkfrei == 2){
						art = 42;
						einzelpreis = pCola;
					}
					else if (alkfrei == 3){
						art = 43;
						einzelpreis = pZlimo;
					}
					else if (alkfrei == 4){
						art = 44;
						einzelpreis = pOlimo;
					}
					else if (alkfrei == 5){
						art = 45;
						einzelpreis = pApfelschorle;
					}
					else if (alkfrei == 6){
						art = 46;
						einzelpreis = pColamix;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("alkoholfreiAnzahl" + elementId).select();
					document.getElementById("kostenAlkoholfrei" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// alkoholfreie Getränkeauswahl verstecken
					document.getElementById('alkoholfreiAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
			}
			// Wenn Säfte gewählt
			else if (gattung == 5){
				// Säfte holen
				var saefte = document.getElementById('inputSaefte' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (saefte >= 1 && saefte <= 6){
					// Saftauswahl zeigen
					document.getElementById('saefteAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (saefte == 1){
						art = 51;
						einzelpreis = pAsaft;
					}
					else if (saefte == 2){
						art = 52;
						einzelpreis = pOsaft;
					}
					else if (saefte == 3){
						art = 53;
						einzelpreis = pKsaft;
					}
					else if (saefte == 4){
						art = 54;
						einzelpreis = pBsaft;
					}
					else if (saefte == 5){
						art = 55;
						einzelpreis = pJsaft;
					}
					else if (saefte == 6){
						art = 56;
						einzelpreis = pPsaft;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("saefteAnzahl" + elementId).select();
					document.getElementById("kostenSaefte" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// Saftauswahl verstecken
					document.getElementById('saefteAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
			}
			// Wenn Cocktails gewählt
			else if (gattung == 6){
				// Cocktailauswahl holen
				var cocktail = document.getElementById('inputCocktails' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (cocktail >= 1 && cocktail <= 6){
					// Cocktailauswahl zeigen
					document.getElementById('cocktailsAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (cocktail == 1){
						art = 61;
						einzelpreis = pSotb;
					}
					else if (cocktail == 2){
						art = 62;
						einzelpreis = pLiit;
					}
					else if (cocktail == 3){
						art = 63;
						einzelpreis = pZombie;
					}
					else if (cocktail == 4){
						art = 64;
						einzelpreis = pHurricane;
					}
					else if (cocktail == 5){
						art = 65;
						einzelpreis = pCaipirinha;
					}
					else if (cocktail == 6){
						art = 66;
						einzelpreis = pMojito;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("cocktailsAnzahl" + elementId).select();
					document.getElementById("kostenCocktails" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// Cocktailauswahl verstecken
					document.getElementById('cocktailsAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
			}
			// Wenn Weine gewählt
			else if (gattung == 7){
				// Weinauswahl holen
				var wein = document.getElementById('inputWeine' + elementId).selectedIndex;
				// Wenn etwas gewählt
				if (wein >= 1 && wein <= 6){
					// Weinauswahl zeigen
					document.getElementById('weineAnzahlAuswahl' + elementId).removeAttribute('style');
					// Art festlegen
					if (wein == 1){
						art = 71;
						einzelpreis = pWeisswein;
					}
					else if (wein == 2){
						art = 72;
						einzelpreis = pRotwein;
					}
					else if (wein == 3){
						art = 73;
						einzelpreis = pSchaumwein;
					}
					else if (wein == 4){
						art = 74;
						einzelpreis = pLikeurwein;
					}
					else if (wein == 5){
						art = 75;
						einzelpreis = pRosewein;
					}
					// Wert des Inputfeldes auf 1 setzen und selektieren
					document.getElementById("weineAnzahl" + elementId).select();
					document.getElementById("kostenWeine" + elementId).innerHTML = (einzelpreis + " &euro;");
					berechnePreis("1", elementId);
				}
				else{
					// Weinauswahl verstecken
					document.getElementById('weineAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
				}
				
			}
		}
		// Wenn art ungleich null
		if (art != 0){
			// Button aktivieren
			document.getElementById("btnKaufen" + elementId).removeAttribute('disabled');
		}
		else{
			// Button deaktivieren
			document.getElementById("btnKaufen" + elementId).setAttribute('disabled', 'disabled');
		}
	}

	// Funktion zum Preis berechnen
	function berechnePreis(menge, elementId){
		// Meldungen verstecken
		var fehlerId = "alertFehler3" + elementId;
		var erfolgId = "alertErfolg3" + elementId;
		document.getElementById(fehlerId).setAttribute('style', 'display: none;');
		document.getElementById(erfolgId).setAttribute('style', 'display: none;');
		// Gattungsname festellen
		if (gattung == 1){
			var gattungsname = "Bier";
		}
		else if (gattung == 2){
			var gattungsname = "Spirituosen";
		}
		else if (gattung == 3){
			var gattungsname = "Biermisch";
		}
		else if (gattung == 4){
			var gattungsname = "Alkoholfrei";
		}
		else if (gattung == 5){
			var gattungsname = "Saefte";
		}
		else if (gattung == 6){
			var gattungsname = "Cocktails";
		}
		else if (gattung == 7){
			var gattungsname = "Wein";
		}
		// Gesamtpreisfeld erstellen
		var gesamtpreisfeld = "gesamtpreis" + gattungsname + elementId;
		// Gesamtpreisfeld anzeigen
		document.getElementById(gesamtpreisfeld).removeAttribute('style');
		// Wenn Menge keine Zahl
		if(isNaN(menge) || menge <= 0){
			// Meldungen anzeigen
			document.getElementById(fehlerId).innerHTML = ("<b>Fehler!</b> Bitte geben Sie eine korrekte Zahl ein!");
			document.getElementById(fehlerId).removeAttribute('style');
			// Gesamtpreisfeld anzeigen
			document.getElementById(gesamtpreisfeld).setAttribute('style', 'display: none;');
			// Preisfeld mit einer 0 füllen
			switch (gattung){
				case "1":
				  	var preisfeld = "kostenBiere" + elementId;
				  	break;
				case "2":
					var preisfeld = "kostenSpirituosen" + elementId;
				  	break;
				case "3":
					var preisfeld = "kostenBiermisch" + elementId;
				  	break;
				case "4":
					var preisfeld = "kostenAlkoholfrei" + elementId;
				  	break;
				case "5":
					var preisfeld = "kostenSaefte" + elementId;
				 	break;
				case "6":
					var preisfeld = "kostenCocktails" + elementId;
				  	break;
				case "7":
					var preisfeld = "kostenWeine" + elementId;
				  	break;
			}
			document.getElementById(preisfeld).innerHTML = "0 &euro;";
			// Button deaktivieren
			document.getElementById("btnKaufen" + elementId).setAttribute('style', 'display: none;');
		}
		// Wenn Menge eine Zahl ist
		else{
			// Button aktivieren
			document.getElementById("btnKaufen" + elementId).removeAttribute('style');
			// Preisfeld füllen
			switch (gattung){
				case "1":
				  	var preisfeld = "kostenBiere" + elementId;
				  	gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "2":
					var preisfeld = "kostenSpirituosen" + elementId;
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "3":
					var preisfeld = "kostenBiermisch" + elementId;
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "4":
					var preisfeld = "kostenAlkoholfrei" + elementId;
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "5":
					var preisfeld = "kostenSaefte" + elementId;
					gesamtpreis = einzelpreis * (menge * 1);
				 	break;
				case "6":
					var preisfeld = "kostenCocktails" + elementId;
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "7":
					var preisfeld = "kostenWeine" + elementId;
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
			}
			gesamtmenge = (menge * 1);
			document.getElementById(preisfeld).innerHTML = Math.round(gesamtpreis*100)/100 + " &euro;";
		}
	}

	// Funktion zum Kaufen der ausgewählten Ware
	function kaufen(elementId){
		// Meldungen verstecken
		var fehlerId = "alertFehler3" + elementId;
		var erfolgId = "alertErfolg3" + elementId;
		document.getElementById(fehlerId).setAttribute('style', 'display: none;');
		document.getElementById(erfolgId).setAttribute('style', 'display: none;');
		// Wenn aktuelle Finanzen ausreichen
		if (finanzen >= gesamtpreis){
			// Gattungsname festellen
			if (gattung == 1){
				var gattungsname = "Bier";
			}
			else if (gattung == 2){
				var gattungsname = "Schnaps";
			}
			else if (gattung == 3){
				var gattungsname = "Biermischgetr%E4nke";
			}
			else if (gattung == 4){
				var gattungsname = "Softdrinks";
			}
			else if (gattung == 5){
				var gattungsname = "Saft";
			}
			else if (gattung == 6){
				var gattungsname = "Cocktails";
			}
			else if (gattung == 7){
				var gattungsname = "Wein";
			}
			// Sicherheitsfrage
			var kaufen = confirm(unescape("M%F6chtest du wirklich " + gesamtmenge + " Liter " + gattungsname + " f%FCr einen Gesamtpreis von " + Math.round(gesamtpreis*100)/100 + "%u20AC kaufen?"));
			if (kaufen){
				// Wartezeichen anzeigen
				$("#warten" + elementId).show();
				// Ajax aufruf
		        $.ajax({
					type: "POST",
					url: "markt/kauf.php",
					cache: false,
					data: { art: art, menge: gesamtmenge, gaststaette: gaststaette},
					success: function(data){
						// Ladeanimation verstecken
						$("#warten" + elementId).hide();
	                    if(data=='nichtBesitzer'){
	                    	// Meldungen anzeigen
	            			document.getElementById(fehlerId).innerHTML = ("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
	            			document.getElementById(fehlerId).removeAttribute('style');
	                    }
	                    else if(data=='finanzenNichtAusreichend'){
	                    	// Meldungen anzeigen
	            			document.getElementById(fehlerId).innerHTML = ("<b>Fehler!</b> Ihre Finanzen sind nicht ausreichend!");
	            			document.getElementById(fehlerId).removeAttribute('style');
	                    }
	                    else if(data=='ok'){
	                        // Alle Arten auf Ursprung zurücksetzen
	                        document.getElementById("inputBier" + elementId).selectedIndex = 0;
	                        document.getElementById("inputSpirituosen" + elementId).selectedIndex = 0;
	                        document.getElementById("inputBiermisch" + elementId).selectedIndex = 0;
	                        document.getElementById("inputAlkoholfrei" + elementId).selectedIndex = 0;
	                        document.getElementById("inputSaefte" + elementId).selectedIndex = 0;
	                        document.getElementById("inputCocktails" + elementId).selectedIndex = 0;
	                        document.getElementById("inputWeine" + elementId).selectedIndex = 0;
	                        document.getElementById('bierAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		document.getElementById('spirituosenAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		document.getElementById('biermischAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		document.getElementById('alkoholfreiAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		document.getElementById('saefteAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		document.getElementById('cocktailsAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		document.getElementById('weineAnzahlAuswahl' + elementId).setAttribute('style', 'display: none;');
	                		// Button deaktivieren
	                		document.getElementById("btnKaufen" + elementId).setAttribute('style', 'display: none;');
	                        // Kontostand updaten
	                        var kontostandNeu = finanzen*1 - gesamtpreis*1;
							finanzen = kontostandNeu;
							$("#kontostand").html("Kontostand: <b>" + finanzen + " &euro;</b>");
							// Meldungen anzeigen
							document.getElementById(erfolgId).innerHTML = ("<b>Erfolg!</b> Der Kauf war erfolgreich!");
							document.getElementById(erfolgId).removeAttribute('style');
	                    }
	                    else {
	                    	// Meldungen anzeigen
	            			document.getElementById(fehlerId).innerHTML = ("<b>Es ist ein unerwarteter Fehler aufgetreten!</b>\n\n" + 
	                    			"Fehler: " + data);
	            			document.getElementById(fehlerId).removeAttribute('style');
	                    }
					}
	            });
	            return false;
			}
		}
		// Wenn Finanzen nicht ausreichen
		else{
			// Meldungen anzeigen
			document.getElementById(fehlerId).innerHTML = ("<b>Fehler!</b> Ihre Finanzen sind nicht ausreichend!");
			document.getElementById(fehlerId).removeAttribute('style');
		}
	}
</script>
<script src="/js/bootstrap-tooltip.js"></script>
<?php include '../footer.php';?>