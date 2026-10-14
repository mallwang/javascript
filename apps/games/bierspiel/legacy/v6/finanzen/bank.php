<?php $title="Bank"; include '../sessionpruefung.php'; include '../geheim.php';?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<script type="text/javascript">

	// Globale Variablen setzen
	var kontostand = parseInt("<?php echo $festgeld;?>");
	var kredit = parseInt("<?php echo $kredit;?>");
	var kreditrahmen = parseInt("<?php echo $kreditrahmen;?>");

	$(document).ready(function() {
		// Felder füllen
		$('#kontostand2').html(kontostand + " &euro;");
		$('#kredit').html(kredit + " &euro;");
		$('#kreditrahmen').html(kreditrahmen + " &euro;");
	});
	
	function leihen(){
		// Meldungen verstecken
		$('#alertFehler').hide();
		$('#alertErfolg').hide();
		// Betrag abfragen
		var betrag = $('#betrag').val();
		// Wenn Wert eingetragen
		if (betrag.length != 0){
			// Wenn Betrag integer
			if (betrag == parseInt(betrag)){
				// Noch offenen Kreditrahmen berechnen
				var verfuegbarerRahmen = (kreditrahmen*1 - kredit*1);
				// Wenn noch offener Rahmen
				if (verfuegbarerRahmen > 0){
					// Wenn Betrag kleiner/gleich verfügbarer Rahmen
					if (betrag*1 <= verfuegbarerRahmen){
						// Wartezeichen anzeigen
						$('#waitBank').show();
						// Neue Finanzdaten
						kontostand = (kontostand*1 + betrag*1);
						kredit = (kredit*1 + betrag*1);
						// Ajax aufruf
						art = "aufnahme";
				           $.ajax({
				        	   		type: "POST",
				        	   		url: "/finanzen/bankgeschaefte.php",
				        	   		cache: false,
				        	   		data: {kontostand: kontostand, kredit: kredit},
				        	   		success: function(data){
					        	   		// Wartezeichen verstecken
					        	   		$("#waitBank").hide();
					                    if(data=='ok'){
											// Angezeigte Finanzdaten ändern
											$('#kontostand2').html(kontostand + " &euro;");
											$('#kredit').html(kredit + " &euro;");
											$('#kontostand').html("Kontostand: <b>" + kontostand + "&euro;</b>");
						                    // Meldung
					                    	$("#alertErfolg").html("<b>Erfolg!</b> Du hast einen Kredit in H&ouml;he von " + betrag + " &euro; aufgenommen.");
					                     	$("#alertErfolg").show();
					                     	// Feld leeren
					                     	$('#betrag').html("");
					                     	// Fokus in Betragsfeld setzen
					                     	document.getElementById('betrag').focus();
					                    }
					                    else{
					                    	$("#alertFehler").html("<b>Fehler!</b> Es ist ein unerwarteter Fehler aufgetreten!<br>" +
					                    			"Bitte kontaktieren sie diesbez&uuml;glich den Support<br><br>Fehler: " + data);
					                    	$("#aleralertFehlertLogin").show();
					                    }
				                	}
				            });
				        return false;
					}
					// Wenn Betrag größer als verfügbarer Rahmen
					else{
						$('#alertFehler').html("<b>Fehler!</b> Ein Kredit in H&ouml;he von " + betrag + " &euro; &uuml;bersteigt ihren Kreditrahmen.<br>Sie k&ouml;nnen maximal noch " + verfuegbarerRahmen + " &euro; aufnehmen.");
						$('#alertFehler').show();
						// Betragsfeld selektieren
						document.getElementById("betrag").select();
					}
				}
				// Wenn Rahmen komplett ausgeschöpft
				else{
					$('#alertFehler').html("<b>Fehler!</b> Die Aufnahme eines Kredites ist nicht mehr m&ouml;glich.<br>Ihr Kreditrahmen ist bereits ausgesch&ouml;pft!");
					$('#alertFehler').show();
					// Betragsfeld selektieren
					document.getElementById("betrag").select();
				} 
			}
			// Wenn kein Integer
			else{
				$('#alertFehler').html("<b>Fehler!</b> Bitte geben Sie einen korrekten, ganzzahligen Betrag ein.");
				$('#alertFehler').show();
				// Betragsfeld selektieren
				document.getElementById("betrag").select();
			}
		}
		// Wenn kein Wert eingetragen
		else{
			$('#alertFehler').html("<b>Fehler!</b> Bitte geben Sie einen Betrag ein.");
			$('#alertFehler').show();
		}
	}

	function zurueck(){
		// Meldungen verstecken
		$('#alertFehler').hide();
		$('#alertErfolg').hide();
		// Betrag abfragen
		var betrag = $('#betrag').val();
		// Wenn Wert eingetragen
		if (betrag.length > 0){
			// Wenn Betrag Integer
			if (betrag == parseInt(betrag)){
				// Wenn Kontostand nicht ins Minus geht
				if ((kontostand-betrag) >= 0){
					// Wenn offener Kredit
					if (kredit > 0){
						// Wenn Rückzahlungsbetrag kleiner/gleich offner Kredit
						if (betrag <= kredit){
							// Wartezeichen anzeigen
							$('#waitBank').show();
							// Neue Finanzdaten
							kontostand = (kontostand*1 - betrag*1);
							kredit = (kredit*1 - betrag*1);
							// Ajax aufruf
							$.ajax({
			        	   		type: "POST",
			        	   		url: "/finanzen/bankgeschaefte.php",
			        	   		cache: false,
			        	   		data: {kontostand: kontostand, kredit: kredit},
			        	   		success: function(data){
				        	   		// Wartezeichen verstecken
				        	   		$("#waitBank").hide();
				                    if(data=='ok'){
										// Angezeigte Finanzdaten ändern
										$('#kontostand2').html(kontostand + " &euro;");
										$('#kredit').html(kredit + " &euro;");
										$('#kontostand').html("Kontostand: <b>" + kontostand + "&euro;</b>");
					                    // Meldung
				                    	$("#alertErfolg").html("<b>Erfolg!</b> Du hast einen Kredit in H&ouml;he von " + betrag + " &euro; getilgt.");
				                     	$("#alertErfolg").show();
				                     	// Feld leeren
				                     	$('#betrag').html("");
				                     	// Fokus in Betragsfeld setzen
				                     	document.getElementById('betrag').focus();
				                    }
				                    else{
				                    	$("#alertFehler").html("<b>Fehler!</b> Es ist ein unerwarteter Fehler aufgetreten!<br>" +
				                    			"Bitte kontaktieren sie diesbez&uuml;glich den Support<br><br>Fehler: " + data);
				                    	$("#aleralertFehlertLogin").show();
				                    }
			                	}
			            	});
			        		return false;
						}
						// Wenn Rückzahlungsbetrag größer als offner Kredit
						else{
							$('#alertFehler').html("<b>Fehler!</b> Sie k&ouml;nnen keine h&ouml;here Summe als ihren offenen Kredit zur&uuml;ckzahlen.");
							$('#alertFehler').show();
							// Betragsfeld selektieren
							document.getElementById("betrag").select();
						}
					}
					// Wenn kein offener Kredit
					else{
						$('#alertFehler').html("<b>Fehler!</b> Sie haben keinen offenen Kredit und können somit auch nichts tilgen.");
						$('#alertFehler').show();
						// Betragsfeld selektieren
						document.getElementById("betrag").select();
					}
				}
				// Wenn Kontostand ins Minus gehen würde
				else{
					$('#alertFehler').html("<b>Fehler!</b> Ihr Kontostand ist nicht ausreichend!");
					$('#alertFehler').show();
					// Betragsfeld selektieren
					document.getElementById("betrag").select();
				}
			}
			// Wenn Betrag kein Integer
			else{
				$('#alertFehler').html("<b>Fehler!</b> Bitte geben Sie einen korrekten, ganzzahligen Betrag ein.");
				$('#alertFehler').show();
				// Betragsfeld selektieren
				document.getElementById("betrag").select();
			}
		}
		// Wenn kein Wert eingetragen
		else{
			$('#alertFehler').html("<b>Fehler!</b> Bitte geben Sie einen korrekten Betrag ein.");
			$('#alertFehler').show();
		}
	}
</script>
<br><br>
<!-- Aktuelle Finanzen -->
<div class="registrieren">
	<table class="table">
		<tbody>
			<tr>
				<td>Kontostand:</td>
				<td id="kontostand2"></td>
			</tr>
			<tr>
				<td>Kredit:</td>
				<td id="kredit"></td>
			</tr>
			<tr>
				<td>Kreditrahmen:</td>
				<td id="kreditrahmen"></td>
			</tr>
		</tbody>
	</table>
	<br>
	<!-- Formular zum Geld aufnehmen und zurückzahlen -->
	<form>
		<div class="control-group">
	    	<div class="controls">
	      		<input type="text" id="betrag" placeholder="Betrag">
	    	</div>
	  	</div>
		<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
		<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
	  	<div class="control-group">
	   		<div class="controls">
	   			<button onclick="leihen()" id="btnLeihen" type="button" class="btn btn-info">Kredit aufnehmen</button>
	   			<button onclick="zurueck()" id="btnZurueck" type="button" class="btn btn-info">Kredit zur&uuml;ckzahlen</button>
	      		<img style="display: none;" id="waitBank" src="/img/loading.gif"/>
	      	</div>
	  	</div>
	</form>
</div>

<br><br><br>
<!-- INHALTSBESCHREIBUNG -->
<div class="inhalt" align="center">
	<div align="center">
		<img style="height: 80px;" src="/img/bauarbeiter.png">
		<h2>T&auml;tigen von Bankgesch&auml;ften</h2>
		<br>
	</div>
	<b>Folgender Inhalt:</b><br><br>
	> User kann auf Grundlage seines letztj&auml;hrigen Finanzabschlusses einen Kredit aufnehmen<br>
	> Geld kann &uuml;ber einen Zeitraum angelegt werden<br>
	> sp&auml;ter evtl. B&ouml;rsengang und Aktienhandel
</div>
<!-- Fokus auf Loginname setzen -->
<script type="text/javascript">
	document.getElementById('betrag').focus();
</script>
<?php include '../footer.php';?>