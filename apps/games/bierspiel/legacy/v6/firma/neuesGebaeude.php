<?php $title="Neues Geb&auml;ude"; include '../sessionpruefung.php'; include '../geheim.php';
// aktuelle Firmen Id
$firma_id = $_SESSION['firmaId'];
// Finanzen abfragen
$finanzen = anfrage("SELECT konto FROM finanzen WHERE firma_id='$firma_id'");
?>
<script type="text/javascript">	
	// Globale variablen deklarieren
	var gebaeude = 0;
	var kosten = 0;
	var kosten1 = 0;
	var kosten2 = 0;
	var kosten3 = 0;
	var kosten4 = 0;
	var kosten5 = 0;
	var dauer = 0;
	var dauer1 = 0;
	var dauer2 = 0;
	var dauer3 = 0;
	var dauer4 = 0;
	var dauer5 = 0;
	var bauherr = 0;
	var artName = "";
	var finanzen = "<?php echo $finanzen[0];?>";
	function setGebaeude(){
		// Bauherr zurücksetzen
		bauherr = 0;
		// Baubutton ausblenden
		$('#btnBau').attr('disabled', 'disabled');
		// Ladezeichen verbergen
		$('#waitBau').hide();
		// Selektiertes Element holen
		var art = $('#inputArt').val();
		// Auswahl zurücksetzen
		$('#verkauf').hide();
		$('#produktion').hide();
		$('#forschung').hide();
		$('#landwirtschaft').hide();
		$('#transport').hide();
		$('#sonstige').hide();
		// Baumenü verstecken
		$('#bauanbieter').hide();
		// Wenn Art gewählt
		if (art == 1){
			$('#verkauf').show();
			artName = "v";
		}
		else if (art == 2){
			$('#produktion').show();
			artName = "p";
		}
		else if (art == 3){
			$('#forschung').show();
			artName = "f";
		}
		else if (art == 4){
			$('#landwirtschaft').show();
			artName = "l";
		}
		else if (art == 5){
			$('#transport').show();
			artName = "t";
		}
		else if (art == 6){
			$('#sonstige').show();
			artName = "s";
		}
		checkForm();
	}

	// Prüft ob alle Felder ausgefüllt sind
	function checkForm(){
		// Bauherr zurücksetzen
		bauherr = 0;
		// Baubutton ausblenden
		$('#btnBau').attr('disabled', 'disabled');
		// Ladezeichen verbergen
		$('#waitBau').hide();
		// Variablen initialisieren
		kosten = 0;
		gebaeude = 0;
		// Baumenü verstecken
		$('#bauanbieter').hide();
		// Kosten zurücksetzen
		$('#kosten').html("");
		// Art abfragen
		var art = $('#inputArt').val();
		// Wenn art gewählt
		if (art >= 1 && art <= 6){
			// Wenn verkauf gewählt
			if (art == 1){
				// Verkaufsgebäude holen
				var verkauf = $('#inputVerkauf').val();
				// Wenn verkauf gewählt
				if (verkauf >= 1 && verkauf <= 3){
					if (verkauf == 1){
						gebaeude = 11;
						$('#kosten').html("100.000,00 &euro;");
						kosten = 100000;
						dauer = 300;
					}
					else if (verkauf == 2){
						gebaeude = 12;
						$('#kosten').html("300.000,00 &euro;");
						kosten = 300000;
						dauer = 600;
					}
					else if (verkauf == 3){
						gebaeude = 13;
						$('#kosten').html("1.000.000,00 &euro;");
						kosten = 1000000;
						dauer = 1200;
					}
				}
			}
			// Wenn produktion gewählt
			else if (art == 2){
				// produktionsgebäude holen
				var produktion = $('#inputProduktion').val();
				// Wenn produktion gewählt
				if (produktion == 1){
					gebaeude = 21;
					$('#kosten').html("1.000.000,00 &euro;");
					kosten = 1000000;
					dauer = 1440;
				}
			}
			// Wenn forschung gewählt
			else if (art == 3){
				// forschungsgebäude holen
				var forschung = $('#inputForschung').val();
				// Wenn forschung gewählt
				if (forschung >= 1 && forschung <= 6){
					if (forschung == 1){
						gebaeude = 31;
						$('#kosten').html("50.000,00 &euro;");
						kosten = 50000;
						dauer = 300;
					}
					else if (forschung == 2){
						gebaeude = 32;
						$('#kosten').html("50.000,00 &euro;");
						kosten = 50000;
						dauer = 300;
					}
					else if (forschung == 3){
						gebaeude = 33;
						$('#kosten').html("50.000,00 &euro;");
						kosten = 50000;
						dauer = 300;
					}
					else if (forschung == 4){
						gebaeude = 34;
						$('#kosten').html("50.000,00 &euro;");
						kosten = 50000;
						dauer = 300;
					}
					else if (forschung == 5){
						gebaeude = 35;
						$('#kosten').html("50.000,00 &euro;");
						kosten = 50000;
						dauer = 300;
					}
					else if (forschung == 6){
						gebaeude = 36;
						$('#kosten').html("50.000,00 &euro;");
						kosten = 50000;
						dauer = 300;
					}
				}
			}
			// Wenn landwirtschaft gewählt
			else if (art == 4){
				// landwirtschaftsgebäude holen
				var landwirtschaft = $('#inputLandwirtschaft').val();
				// Wenn forschung gewählt
				if (landwirtschaft >= 1 && landwirtschaft <= 3){
					if (landwirtschaft == 1){
						gebaeude = 41;
						$('#kosten').html("100.000,00 &euro;");
						kosten = 100000;
						dauer = 600;
					}
					else if (landwirtschaft == 2){
						gebaeude = 42;
						$('#kosten').html("100.000,00 &euro;");
						kosten = 100000;
						dauer = 600;
					}
					else if (landwirtschaft == 3){
						gebaeude = 43;
						$('#kosten').html("100.000,00 &euro;");
						kosten = 100000;
						dauer = 600;
					}
				}
			}
			// Wenn transport gewählt
			else if (art == 5){
				// transportgebäude holen
				var transport = $('#inputTransport').val();
				// Wenn transport gewählt
				if (transport == 1){
					gebaeude = 51;
					$('#kosten').html("150.000,00 &euro;");
					kosten = 150000;
					dauer = 600;
				}
			}
			// Wenn sonstige gewählt
			else if (art == 6){
				// sonstige gebäude holen
				var sonstige = $('#inputSonstige').val();
				// Wenn sonstige gewählt
				if (sonstige >= 1 && sonstige <= 3){
					if (sonstige == 1){
						gebaeude = 61;
						$('#kosten').html("2.500.000,00 &euro;");
						kosten = 2500000;
						dauer = 1440;
					}
					else if (sonstige == 2){
						gebaeude = 62;
						$('#kosten').html("500.000,00 &euro;");
						kosten = 500000;
						dauer = 600;
					}
				}
			}
		}
		// Wenn gebäude ungleich null
		if (gebaeude != 0){
			// Alerts verbergen
			$('#alertFehler').hide();
			$('#alertErfolg').hide();
			$('#alertFehlerBau').hide();
			$('#alertErfolgBau').hide();
			// Wenn Kontostand größer kosten
			if (finanzen >= kosten){
				// Meldung
				$('#alertErfolg').html("Ihre Finanzen reichen aus!");
				$('#alertErfolg').show();
				// Wenn Standort ausgefüllt
				var standort = $('#inputStandort').val();
				if (standort != 0){
					// Wenn Viertel ausgefüllt
					var viertel = $('#inputViertel').val();
					if (viertel != 0){
						// Button aktivieren
						$('#btnAuswaehlen').removeAttr('disabled');
					}
					else{
						// Button deaktivieren
						$('#btnAuswaehlen').attr('disabled', 'disabled');
					}
				}
				else{
					// Button deaktivieren
					$('#btnAuswaehlen').attr('disabled', 'disabled');
				}
			}
			else{
				// Meldung
				$('#alertFehler').html("Ihre Finanzen reichen nicht aus!");
				$('#alertFehler').show();
				// Button deaktivieren
				$('#btnAuswaehlen').attr('disabled', 'disabled');
			}
		}
		else{
			// Alerts verbergen
			$('#alertFehler').hide();
			$('#alertErfolg').hide();
			$('#alertFehlerBau').hide();
			$('#alertErfolgBau').hide();
			// Button deaktivieren
			$('#btnAuswaehlen').attr('disabled', 'disabled');
		}
	}

	// Bauanbieter zeigen
	function bauanbieter(){
		// Bauanbieter Kosten und Dauer berechnen
		kosten1 = kosten;
		kosten2 = kosten * 1.2;
		kosten3 = kosten * 1.5;
		kosten4 = kosten * 2;
		kosten5 = kosten * 2.5;
		dauer1 = dauer;
		dauer2 = dauer * 0.9;
		dauer3 = dauer * 0.8;
		dauer4 = dauer * 0.6;
		dauer5 = dauer * 0.4;
		// Bauanbieter Kosten und Dauer füllen
		$('#kosten1').html(kosten1 + " &euro;");
		$('#kosten2').html(kosten2 + " &euro;");
		$('#kosten3').html(kosten3 + " &euro;");
		$('#kosten4').html(kosten4 + " &euro;");
		$('#kosten5').html(kosten5 + " &euro;");
		$('#dauer1').html(dauer1 + " Minuten");
		$('#dauer2').html(dauer2 + " Minuten");
		$('#dauer3').html(dauer3 + " Minuten");
		$('#dauer4').html(dauer4 + " Minuten");
		$('#dauer5').html(dauer5 + " Minuten");
		// Prüfen, ob kosten der Bauanbieter Finanzen übersteigen
		if (kosten1 > finanzen){
			// Anbieter nicht anzeigen
			$('#anbieter1').html("<img src='/img/bild.png' class='img-rounded' alt='100x100' style='width: 100px; height: 100px;'>");
		}
		else{
			// Anbieter anzeigen
			$('#anbieter1').html("<a onclick='bau(1)'><img src='/img/image-1.jpg' class='img-rounded pointer' alt='100x100' style='width: 100px; height: 100px;'></a>");
		}
		if (kosten2 > finanzen){
			// Anbieter nicht anzeigen
			$('#anbieter2').html("<img src='/img/bild.png' class='img-rounded' alt='100x100' style='width: 100px; height: 100px;'>");
		}
		else{
			// Anbieter anzeigen
			$('#anbieter2').html("<a onclick='bau(2)'><img src='/img/image-1.jpg' class='img-rounded pointer' alt='100x100' style='width: 100px; height: 100px;'></a>");
		}
		if (kosten3 > finanzen){
			// Anbieter nicht anzeigen
			$('#anbieter3').html("<img src='/img/bild.png' class='img-rounded' alt='100x100' style='width: 100px; height: 100px;'>");
		}
		else{
			// Anbieter anzeigen
			$('#anbieter3').html("<a onclick='bau(3)'><img src='/img/image-1.jpg' class='img-rounded pointer' alt='100x100' style='width: 100px; height: 100px;'></a>");
		}
		if (kosten4 > finanzen){
			// Anbieter nicht anzeigen
			$('#anbieter4').html("<img src='/img/bild.png' class='img-rounded' alt='100x100' style='width: 100px; height: 100px;'>");
		}
		else{
			// Anbieter anzeigen
			$('#anbieter4').html("<a onclick='bau(4)'><img src='/img/image-1.jpg' class='img-rounded pointer' alt='100x100' style='width: 100px; height: 100px;'></a>");
		}
		if (kosten5 > finanzen){
			// Anbieter nicht anzeigen
			$('#anbieter5').html("<img src='/img/bild.png' class='img-rounded' alt='100x100' style='width: 100px; height: 100px;'>");
		}
		else{
			// Anbieter anzeigen
			$('#anbieter5').html("<a onclick='bau(5)'><img src='/img/image-1.jpg' class='img-rounded pointer' alt='100x100' style='width: 100px; height: 100px;'></a>");
		}
		// Menü ausklappen
		$('#bauanbieter').slideDown();
	}
	
	// Bau in Auftrag geben
	function bau(bauanbieter){
		// Bauanbieter setzen
		if (bauanbieter >= 1 && bauanbieter <= 5){
			bauherr = bauanbieter;
			$('#btnBau').removeAttr('disabled');
		}
		// Wenn bauButton gedrückt wurde
		else if (bauanbieter == 0){
			// Ladezeichen anzeigen
			$('#waitBau').show();
			// Gebäudename initialisieren
			var gebaeudename = "";
			// Fallunterscheidung Gebäudename
			switch (gebaeude){
				case 11: gebaeudename = "Kneipe";				break;
				case 12: gebaeudename = "Gaststätte";			break;
				case 13: gebaeudename = "Handelszentrum";		break;
				case 21: gebaeudename = "Brauerei";				break;
				case 31: gebaeudename = "Inhalt & Geschmack";	break;
				case 32: gebaeudename = "Verpackung";			break;
				case 33: gebaeudename = "Technik";				break;
				case 34: gebaeudename = "Arbeitsabläufe";		break;
				case 35: gebaeudename = "Merchandise";			break;
				case 36: gebaeudename = "Marketing";			break;
				case 41: gebaeudename = "Farm";					break;
				case 42: gebaeudename = "Plantage";				break;
				case 43: gebaeudename = "Quelle";				break;
				case 51: gebaeudename = "LKW Verladestation";	break;
				case 61: gebaeudename = "Firmenhauptsitz";		break;
				case 62: gebaeudename = "Lagerhaus";			break;
			}
			// Variablen festlegen für Ajax aufruf
			var kategorie = artName;
			var standort = $('#inputStandort').val();
			var standortName = "";
			// Fallunterscheidung Standortname
			switch (standort){
				case "1": standortName = "Ansbach";		break;
				case "2": standortName = "Landshut";	break;
				case "3": standortName = "München";		break;
				case "4": standortName = "Bayreuth";	break;
				case "5": standortName = "Regensburg";	break;
				case "6": standortName = "Würzburg";	break;
				case "7": standortName = "Augsburg";	break;
			}
			var viertel = $('#inputViertel').val();
			var viertelName = "";
			// Fallunterscheidung Standortname
			switch (viertel){
				case "1": viertelName = "innenstadt";		break;
				case "2": viertelName = "wohngebiet";		break;
				case "3": viertelName = "gewerbegebiet";	break;
			}
			var groesse = 250;
			var baukosten = 0;
			// Fallunterscheidung Kosten
			switch (bauherr){
				case 1: baukosten = kosten1;				break;
				case 2: baukosten = kosten2;				break;
				case 3: baukosten = kosten3;				break;
				case 4: baukosten = kosten4;				break;
				case 5: baukosten = kosten5;				break;
			}
			var dauerMinuten = 0;
			// Fallunterscheidung Dauer
			switch (bauherr){
				case 1: dauerMinuten = dauer1;				break;
				case 2: dauerMinuten = dauer2;				break;
				case 3: dauerMinuten = dauer3;				break;
				case 4: dauerMinuten = dauer4;				break;
				case 5: dauerMinuten = dauer5;				break;
			}
			// Ajax aufruf
			$.ajax({
				type: "POST",
				url: "/firma/bau.php",
				cache: false,
				data: { gebaeude: gebaeudename, kategorie: kategorie, kosten: baukosten, dauer: dauerMinuten, standort: standortName, viertel: viertelName, groesse: groesse},
						success: function(data){
							// Wartezeichen verstecken
							$('#waitBau').hide();
							if(data=='ok'){
								// Art zurücksetzen
								document.getElementById("inputArt").selectedIndex = 0;
								// Auswahl zurücksetzen
								$('#verkauf').hide();
								$('#produktion').hide();
								$('#forschung').hide();
								$('#landwirtschaft').hide();
								$('#transport').hide();
								$('#sonstige').hide();
								// Standort zurücksetzen
								document.getElementById("inputStandort").selectedIndex = 0;
								checkForm();
								// Neuen Kontostand berechnen
								var kontostandNeu = finanzen - baukosten;
								finanzen = kontostandNeu;
								// Erfolgsmitteilung
								$("#alertErfolgBau").html("<b>Erfolg!</b> Du hast folgendes Geb&auml;ude in Auftrag gegeben:!<br>" +
 										"Geb&auml;ude: " + gebaeudename + "<br>" + 
 										"Standort: " + standortName + "<br>" + 
 										"Viertel: " + viertelName + "<br>" + 
 										"Baukosten: " + baukosten + "&euro; <br>" + 
										"Neuer Kontostand: " + kontostandNeu + " &euro; <br>" + 
										"Bauzeit: " + dauerMinuten + " Minuten");
								$("#alertErfolgBau").show();
								// Finanzen updaten
								$("#kontostand").html("Kontostand: <b>" + finanzen + " &euro;</b>");
							}
							else if(data=='maxGebaeude'){
								$("#alertFehlerBau").html("<b>Fehler!</b> Sie haben bereits die maximale Anzahl an Geb&auml;uden erreicht!");
								$("#alertFehlerBau").show();
							}
							else if(data=='kontostandZuKlein'){
								$("#alertFehlerBau").html("<b>Fehler!</b> Ihr aktueller Kontostand ist nicht ausreichend!");
								$("#alertFehlerBau").show();
							}
							else{
		               	 		$("#alertFehlerBau").html("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
		               	 		$("#alertFehlerBau").show();
							}
						}
			});
			return false;
		}
	}
</script>
<!-- Tabelle zum Auswählen -->
<div class="registrieren">
	<form class="form-horizontal">
		<!-- Art -->
		<div class="control-group">
			<label class="control-label" name="inputArt">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputArt" onchange="setGebaeude()">
					<option value="0">Bitte w&auml;hlen</option>
					<option id="Art1" value="1">Verkauf</option>
					<!-- <option id="Art2" value="2">Produktion</option>
					<option id="Art3" value="3">Forschung</option>
					<option id="Art4" value="4">Landwirtschaft</option>
					<option id="Art5" value="5">Transport</option>
					<option id="Art6" value="6">Sonstiges</option> -->
				</select>
			</div>
		</div>
		<!-- Verkauf -->
		<div id="verkauf" style="display: none;" class="control-group">
			<label class="control-label" name="inputVerkauf">Geb&auml;ude:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputVerkauf" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Kneipe</option>
					<option value="2">Gastst&auml;tte</option>
					<!-- <option value="3">Handelszentrum</option> -->
				</select>
			</div>
		</div>
		<!-- Produktion -->
		<div id="produktion" style="display: none;" class="control-group">
			<label class="control-label" name="inputProduktion">Geb&auml;ude:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputProduktion" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Brauerei</option>
				</select>
			</div>
		</div>
		<!-- Forschung -->
		<div id="forschung" style="display: none;" class="control-group">
			<label class="control-label" name="inputForschung">Geb&auml;ude:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputForschung" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Inhalt & Geschmack</option>
					<option value="2">Verpackung</option>
					<option value="3">Technik</option>
					<option value="4">Arbeitsabl&auml;fe</option>
					<option value="5">Merchandise</option>
					<option value="6">Marketing</option>
				</select>
			</div>
		</div>
		<!-- Landwirtschaft & Bergbau -->
		<div id="landwirtschaft" style="display: none;" class="control-group">
			<label class="control-label" name="inputLandwirtschaft">Geb&auml;ude:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputLandwirtschaft" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Farm</option>
					<option value="2">Plantage</option>
					<option value="3">Quelle</option>
				</select>
			</div>
		</div>
		<!-- Transport -->
		<div id="transport" style="display: none;" class="control-group">
			<label class="control-label" name="inputTransport">Geb&auml;ude:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputTransport" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">LKW Verladestation</option>
				</select>
			</div>
		</div>
		<!-- Sonstige -->
		<div id="sonstige" style="display: none;" class="control-group">
			<label class="control-label" name="inputSonstige">Geb&auml;ude:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputSonstige" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Firmenhauptsitz</option>
					<option value="2">Lagerhaus</option>
				</select>
			</div>
		</div>
		<!-- Standort -->
		<div class="control-group">
			<label class="control-label" name="inputStandort">Standort:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputStandort" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Ansbach</option>
					<option value="7">Augsburg</option>
					<option value="4">Bayreuth</option>
					<option value="2">Landshut</option>
					<!-- <option value="3">M&uuml;nchen</option> -->
					<option value="5">Regensburg</option>
					<!-- <option value="6">W&uuml;rzburg</option> -->
				</select>
			</div>
		</div>
		<!-- Viertel -->
		<div class="control-group">
			<label class="control-label" name="inputViertel">Viertel:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputViertel" onchange="checkForm()">
					<option value="0">Bitte w&auml;hlen</option>
					<option value="1">Innenstadt</option>
					<option value="2">Wohngebiet</option>
					<option value="3">Gewerbegebiet</option>
				</select>
			</div>
		</div>
		<!-- Kosten -->
	  	<div class="control-group">
	   		<label class="control-label" name="inputSonstige">Kosten:</label>
			<div class="controls">
				<label class="control-label" name="inputSonstige" id="kosten"></label>
			</div>
	  	</div>
	  	<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
	  	<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
		<!-- Auswählen-Button -->
	  	<div class="control-group">
	   		<div class="controls">
	   			<button disabled="disabled" id="btnAuswaehlen" type="button" class="btn btn-info" onclick="bauanbieter()">Ausw&auml;hlen</button>
	    	</div>
	  	</div>
	</form>
</div>
<br>
<!-- Baufirmen (erscheinen nach klick auf Button "Auswählen" -->
<div id="bauanbieter" style="display: none;" class="baufirmen" align="center">
	<table style="width: 700px;" class="table">
		<tbody  style="font-size: 12px; text-align: center;">
			<tr>
				<td>Bauanbieter</td>
				<td id="anbieter1"></td>
				<td id="anbieter2"></td>
				<td id="anbieter3"></td>
				<td id="anbieter4"></td>
				<td id="anbieter5"></td>
			</tr>
			<tr>
				<td>Kosten:</td>
				<td id="kosten1"></td>
				<td id="kosten2"></td>
				<td id="kosten3"></td>
				<td id="kosten4"></td>
				<td id="kosten5"></td>
			</tr>
			<tr>
				<td>Dauer</td>
				<td id="dauer1"></td>
				<td id="dauer2"></td>
				<td id="dauer3"></td>
				<td id="dauer4"></td>
				<td id="dauer5"></td>
			</tr>
		</tbody>
	</table>
	<!-- Bauen-Button -->
	<div class="control-group">
		<div class="controls">
			<button disabled="disabled" id="btnBau" type="button" class="btn btn-info" onclick="bau(0)">Bau in Auftrag geben!</button>
			<img style="display: none;" id="waitBau" src="/img/loading.gif"/>
		</div>
	</div>
</div>
<div align="center">
	<div style="width: 400px;">
		<div id="alertFehlerBau" style="display: none;" class="alert alert-error"></div>
		<div id="alertErfolgBau" style="display: none;" class="alert alert-success"></div>
	</div>
</div>
<!-- Baufirmen (erscheinen nach klick auf Button "Auswählen"
<div align="center">
	<img src="/img/image-1.jpg" class="img-rounded" alt="140x140" style="width: 140px; height: 140px;">
	<img src="/img/image-1.jpg" class="img-rounded" alt="140x140" style="width: 140px; height: 140px;">
	<img src="/img/image-1.jpg" class="img-rounded" alt="140x140" style="width: 140px; height: 140px;">
	<img src="/img/image-1.jpg" class="img-rounded" alt="140x140" style="width: 140px; height: 140px;">
	<img src="/img/image-1.jpg" class="img-rounded" alt="140x140" style="width: 140px; height: 140px;">
	<br>
	<div style="width: 140px;">
		<div>100.000,00 €</div>
		<div>100.000,00 €</div>
		<div>100.000,00 €</div>
		<div>100.000,00 €</div>
		<div>100.000,00 €</div>
	</div>
</div>
 -->
<?php include '../footer.php';?>