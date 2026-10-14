<?php $title="Neuer Dateneintrag"; include 'header.php';

// Wenn ein Benutzer angemeldet ist
if (isset($_SESSION['username'])){
	$begruessung = $_SESSION['username'] . ", hilf uns bei der Datenerfassung!";
}
// Wenn kein Benutzer angemeldet ist
else{
	$begruessung = "Hilf uns bei der Datenerfassung!";
}
?>
<!-- GOOGLE MAPS API -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCMd54nqYZHkTQ47TE1uon8b7pewggh7yA&sensor=true"></script>
<br>

<!-- BEGRÜßUNG UND KURZER HINWEIS -->
<div class="dateneintrag">
	<h2>
		<?php echo $begruessung;?>
	</h2>
	<p>
		Die genaue Erfassung aller Sehenswürdigkeiten, Parks und Museen für
		alle Orte Deutschlands ist sehr komplex und zeitaufwändig. <br>
		Deshalb sind wir auf deine Mithilfe angewiesen.<br><br><br>
		
		<!-- Button für das Anzeigen des Modals -->
		<a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">Wie funktioniert die Datenerfassung?</a>
		
		<br><br>
		<div class="alert alert-info" align="center">Das Ändern eines Datensatzes ist momentan deaktiviert!</div>
		
		<!-- Modal -->
		<div id="myModal" class="modal hide fade" aria-hidden="true">
		  	<div class="modal-header" style="padding-left: 40px;">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    	<h3 id="myModalLabel">Wie funktioniert die Datenerfassung?</h3>
		  	</div>
		  	<div class="modal-body ueber">
				<div id="bedienNeu" align="left" style="width: 490px; padding-left: 40px; float: left;">
					<br>
					<b><u>Neuen Eintrag erfassen</u></b><br>
					1. Bundesland, Ort und Art für deinen Eintrag festlegen<br>
					2. Namen und Beschreibung einfügen<br>
					3. Koordinaten durch einen Klick auf die Karte festlegen<br>
					4. Absenden<br>
					5. Fertig!<br><br>
				</div>
				<div id="bedienAenderung" align="left" style="padding-right: 40px;">
					<br>
					<b><u>Bestehenden Eintrag ändern</u></b><br>
					1. Bundesland, Ort und Art für deinen Eintrag festlegen<br>
					2. Einen vorhandenen Eintrag aus der Liste auswählen<br>
					3. Namen, Beschreibung oder Koordinaten ändern<br>
					4. Absenden<br>
					5. Fertig!<br><br>
				</div>
				<div align="left" style="padding-right: 40px; padding-left: 40px;">
					Nachdem du einen Eintrag neu angelegt bzw. geändert hast, kann es eine gewisse Zeit dauern, bis dieser 
					in den Suchergebnissen erscheint, da im Anschluss jeder Eintrag von unserem Team auf dessen Korrektheit geprüft wird.<br><br>
				</div>
		  	</div>
		  	<div align="center" class="modal-footer">
		    	<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">OK</button>
		  	</div>
		</div>
	</p>
</div>
<br>

<!-- ******************************************
 **** KOMPLETTE AUFTEILUNG IN ZWEI SPALTEN ****
 ****************************************** -->
<div align="center" class='ueber'>

	<!-- ******************************************
	 **	AUSWAHL DER MÖGLICHKEITEN (RADIOBUTTONS) **
	 ****************************************** -->
	<div align='center'>
		<form id="frmObjektdaten" class="form-horizontal">
		
			<!-- ÜBERSCHRIFT -->
			<h5>Objektdaten:</h5>
			
			<!-- FEHLERMELDUNG -->
			<div style="display: none;" class="alert alert-error" id="errorObjektdaten"></div>
			
			<!-- BEIDE RADIOBUTTONS -->
			<label style="width: 170px; display: inline-block;" class="radio">
				<input type="radio" name="optionsRadios" onclick="showNeueintrag()"	id="radioNeueintrag" value="option1" checked="checked"> Neuer Eintrag
			</label>
			<label style="width: 170px; display: inline-block;" class="radio">
				<input type="radio" name="optionsRadios"	onclick="showAenderung()" id="radioAenderung" value="option2"> Änderung (deaktiviert)
			</label>
		</form>
	</div>
	
	<!-- ******************************************
	 **	BEIDE FORMULARE (NEUEINTRAG & ÄNDERUNG) ***
	 ****************************************** -->
		<div class='teil1'>
		
		<!-- ******************************************
	 	************  FORMULAR NEUEINTRAG *************
	 	******************************************* -->
			<form id="neueintrag" class="bs-docs-example form-horizontal" style="display: ;">
			
			<!-- BUNDESLAND -->
			<div class="control-group">
				<label class="control-label">Bundesland*:</label>
				<div class="controls">
					<select id="selectBundesland" onchange="showCity('0', this.value, this.selectedIndex); setCenterOnBl(this.value);">
						<option value="">Bitte wählen</option>
						<option value="BW">Baden-Württemberg</option>
						<option value="BY">Bayern</option>
						<option value="BE">Berlin</option>
						<option value="BB">Brandenburg</option>
						<option value="HB">Bremen</option>
						<option value="HH">Hamburg</option>
						<option value="HE">Hessen</option>
						<option value="MV">Mecklenburg-Vorpommern</option>
						<option value="NI">Niedersachsen</option>
						<option value="NW">Nordrhein-Westfalen</option>
						<option value="RP">Rheinland-Pfalz</option>
						<option value="SL">Saarland</option>
						<option value="SN">Sachsen</option>
						<option value="ST">Sachsen-Anhalt</option>
						<option value="SH">Schleswig-Holstein</option>
						<option value="TH">Thürigen</option>
					</select>
				</div>
			</div>
			
			<!-- ORT -->
			<div class="control-group">
				<label class="control-label">Ort*:</label>
				<div class="controls">
				
					<!-- HIER ERSCHEINEN DIE GEFUNDENEN ORTE -->
					<div id="gefundeneOrte"></div>
					
					<!-- WARTEGRAFIK -->
					<div align="center" id="waitOrte" style="display: none;">
						<img src="img/loading.gif" />
					</div>
				</div>
			</div>
			
			<!-- ART -->
			<div class="control-group">
				<label class="control-label">Art*:</label>
				<div class="controls">
					<select id="selectArt" onchange="showPlaces(this.value), pruefeFormular('1')">
						<option value="">Bitte wählen</option>
						<option value="S">Sehenswürdigkeit</option>
						<option value="P">Park</option>
						<option value="M">Museum</option>
					</select>
				</div>
				
				<!-- HIER ERSCHEINEN DIE GEFUNDENEN PLÄTZE -->
				<div id="gefundenePlaetze" style="display: none;"></div>
			</div>
			
			<!-- NAME DES ORTES -->
			<div class="control-group">
				<label class="control-label">Name*:</label>
				<div class="controls">
					<input type="text" id="name" placeholder="Name" onkeyup="pruefeFormular('1')">
				</div>
			</div>
			
			<!-- BESCHREIBUNG DES ORTES -->
			<div class="control-group" style="margin-bottom: 5px;">
				<label class="control-label">Beschreibung*:</label>
			</div>
			<div class="control-group">
				<textarea style="width: 400px;" maxlength="1200" rows="3" id="beschreibung" onkeyup="pruefeFormular('1')"	placeholder="Bitte fügen Sie eine kurze Beschreibung ein."></textarea>
			</div>
			
			<!-- KOORDINATEN DES ORTES -->
			<div class="control-group">
				<label class="control-label">Koordinaten*:</label>
				<div class="controls">
					<input type="text" id="breitengrad" placeholder="Breitengrad" onkeyup="pruefeFormular('1')">
					<input type="text" id="laengengrad" placeholder="Längengrad" onkeyup="pruefeFormular('1')">
				</div>
			</div>
			
			<!-- HINWEIS PFLICHTFELDER -->
			<div class="control-group">
				<label class="control-label">* Pflichtfelder</label>
			</div>
			
			<!-- FEHLERMELDUNGEN & BUTTON -->
			<div class="control-group">
				<div class="controls">
					<form id="frmAbsenden1" class="form-horizontal">
					
						<!-- MELDUNGEN -->
						<div id="errorSenden1" style="display: none;" class="alert alert-error"></div>
						<div id="successSenden1" style="display: none;" class="alert alert-success"></div>
						
						<!-- BUTTON -->
						<button disabled="disabled" type="button" onclick="senden('1')" id="btnSenden1" class="btn btn-warning">Mithelfen!</button>
						
						<!-- WARTEGRAFIK -->
						<img style="display: none;" id="wait1" src="img/loading.gif" />
					</form>
				</div>
			</div>
		</form>
		
		<!-- ******************************************
	 	*************  FORMULAR ÄNDERUNG **************
	 	******************************************* -->
		<form id="aenderung" class="bs-docs-example form-horizontal" style="display: none;">
		
			<!-- BUNDESLAND AKTUELLE DATEN -->
			<div class="control-group">
				<label class="control-label">Bundesland:</label>
				<div class="controls">
					<select id="selectBundeslandAenderung"
						onchange="showCity('1', this.value, this.selectedIndex)">
						<option value="">Bitte wählen</option>
						<option value="BW">Baden-Württemberg</option>
						<option value="BY">Bayern</option>
						<option value="BE">Berlin</option>
						<option value="BB">Brandenburg</option>
						<option value="HB">Bremen</option>
						<option value="HH">Hamburg</option>
						<option value="HE">Hessen</option>
						<option value="MV">Mecklenburg-Vorpommern</option>
						<option value="NI">Niedersachsen</option>
						<option value="NW">Nordrhein-Westfalen</option>
						<option value="RP">Rheinland-Pfalz</option>
						<option value="SL">Saarland</option>
						<option value="SN">Sachsen</option>
						<option value="ST">Sachsen-Anhalt</option>
						<option value="SH">Schleswig-Holstein</option>
						<option value="TH">Thürigen</option>
					</select>
				</div>
			</div>
			
			<!-- ORT AKTUELLE DATEN -->
			<div class="control-group">
				<label class="control-label">Ort:</label>
				<div class="controls">
				
					<!-- HIER ERSCHEINEN DIE GEFUNDENEN ORTE -->
					<div id="gefundeneOrteAenderung"></div>
					
					<!-- WARTEGRAFIK -->
					<div align="center" id="waitOrteAenderung" style="display: none;">
						<img src="img/loading.gif" />
					</div>
				</div>
			</div>
			
			
			<!-- ART AKTUELLE DATEN -->
			<div id="artAenderung" class="control-group" style="display: none;">
				<label class="control-label">Art:</label>
				<div class="controls">
					<select id="selectArtAenderung" onchange="showPlaces(this.value), pruefeFormular('1')">
					<!-- <select id="selectArtAenderung" onchange="showDetails(this.selectedIndex)"> -->
						<option value="">Bitte wählen</option>
						<option value="S">Sehenswürdigkeit</option>
						<option value="P">Park</option>
						<option value="M">Museum</option>
					</select>
				</div>
			</div>
			
			<!-- ******************************************
	 		************ AKTUELLE DATEN INFOS *************
	 		******************************************* -->
			<div id="alteDaten" style="display: none;">
				<h4>Alte Daten</h4>
				
				<!-- NAME DES AKTUELLEN ORTES ZUR AUSWAHL -->
				<div class="control-group">
					<label class="control-label">Name:</label>
					<div class="controls">
					
						<!-- WARTEGRAFIK -->
						<div align="center" id="waitNameAlt" style="display: none;">
							<img src="img/loading.gif" />
						</div>
						<div id="nameAlt"></div>
					</div>
				</div>
				
				<!-- BESCHREIBUNG UND KOORDINATEN DES AKTUELLEN ORTES ERST NACH AUSWAHL DES ORTES -->
				<div id="alteDetails" style="display: none;">
				
					<!-- WARTEGRAFIK -->
					<div align="center" id="waitAlteDetails" style="display: none;">
						<img src="img/loading.gif" />
					</div>
					
					<!-- BESCHREIBUNG DER AKTUELLEN ART -->
					<div class="control-group">
						<label class="control-label">Beschreibung:</label>
						<div class="controls">
							<textarea rows="3" id="beschreibungAlt" disabled="disabled"></textarea>
						</div>
					</div>
					
					<!-- KOORDINATEN DER AKTUELLEN ART -->
					<div class="control-group">
						<label class="control-label">Koordinaten:</label>
						<div class="controls">
							<input type="text" id="breitengradAlt" disabled="disabled"> <input
								type="text" id="laengengradAlt" disabled="disabled">
						</div>
					</div>
					<div class="control-group">
						<div class="controls"></div>
					</div>
				</div>
			</div>
		</form>
	</div>
	
	<!-- ******************************************
	 **********  KARTENSUCHFELD UND KARTE *********
	 ****************************************** -->
	<div class='teil2'>
	
		<!-- KARTENSUCHFELD UND BUTTON -->
		<div id="panel" style="position: absolute; z-index: 5; padding: 5px; background-color: #fff; margin-top: 10px; margin-left: 190px;">
			<input style="height: 25px; width: 150px;" id="address" type="textbox" placeholder="Ort, Kreis, Bundesland">
			<input style="height: 25px;" type="button" value="Finden" onclick="showAddress()">
		</div>
		
		<!-- KARTE -->
		<div id="map_canvas" style="width: 550px; height: 400px;">
			<div style="margin-top: 180px;">
				<img src="img/loading.gif" />
			</div>
		</div>
		
		<div id="mapLoad" style="display: none; height: 400px; width: 550px; margin-left: 20px; margin-top: -400px; position: absolute; background: #000000; opacity:0.3;">
				<img style="margin-top: 180px;" src="img/loading.gif" />
		</div>
	</div>
</div>

<!-- ***********************************************
*** NEUE DATEN UNTER DER BEIDSEITIGEN AUFTEILUNG ***
************************************************ -->
<div id="neueDaten" align="center"
	class='ueber' style="display: none; margin-top: 480px;">
	
	<!-- ÜBERSCHRIFT -->
	<div align='center'>
		<h4>Neue Daten</h4>
	</div>
	
	<!-- NEUER NAME UND NEUE KOORDINATEN -->
	<div class='teil1'>
		<form class="form-horizontal">
			<div class="control-group">
			
				<!-- NEUER NAME -->
				<div class="control-group">
					<label class="control-label">Name*:</label>
					<div class="controls">
						<input type="text" id="nameNeu" onkeyup="pruefeFormular('2')">
					</div>
				</div>
				
				<!-- NEUE KOORDINATEN -->
				<div class="control-group">
					<label class="control-label">Koordinaten*:</label>
					<div class="controls">
						<form class="form-inline">
							<input type="text" id="breitengradNeu" onkeyup="pruefeFormular('2')">
							<input type="text" id="laengengradNeu" onkeyup="pruefeFormular('2')">
						</form>
					</div>
				</div>
			</div>
		</form>
	</div>
	
	<!-- NEUE BESCHREIBUNG-->
	<div class='teil2'>
		<form class="form-horizontal">
		
			<!-- BESCHREIBUNG -->
			<div class="control-group">
				<label class="control-label" style="margin-left: 30px;">Beschreibung*:</label>
				<div class="controls">
					<textarea style="width: 350px;" rows="3" id="beschreibungNeu" onkeyup="pruefeFormular('2')"></textarea>
				</div>
			</div>
			
			<!-- HINWEIS PFLICHTFELDER -->
			<div class="control-group">
				<label class="control-label" style="margin-left: 30px;">* Pflichtfelder</label>
			</div>
		</form>
	</div>
	
	<!-- MELDUNGEN UND HELFEN-BUTTON -->
	<div align='center'>
		<form class="form-horizontal">
			<!-- MELDUNGEN -->
			<div id="errorSenden2" style="display: none;" class="alert alert-error"></div>
			<div id="successSenden2" style="display: none;" class="alert alert-success"></div>
			
			<!-- BUTTON -->
			<button disabled="disabled" type="button" onclick="senden('2')"	id="btnSenden2" class="btn btn-warning">Mithelfen!</button>
			
			<!-- WARTEGRAFIK -->
			<img style="display: none;" id="wait2" src="img/loading.gif" />
		</form>
	</div>
</div>

<!-- JAVASCRIPT CODE -->
<script type="text/javascript">

	// GLOBALE VARIABLEN
	var selectBundeslandIndex = 0;
	var selectedBundeslandAenderung = 0;
	var selectedOrtIndex = 0;
	var selectedOrtAenderungIndex = 0;
	var selectedArtAenderung = 0;
	var selectedNameAlt = 0;
	var nameAlt = "";
	var breitengradAlt = "";
	var laengengradAlt = "";
	var beschreibungAlt = "";
	
	var longitude = 0;
	var latitude = 0;
	var erfolg = 0;
	var map;
	var geocoder;
	var markersArray = [];
	var markersPlacesArray = [];

	// FUNKTION ZUM ANZEIGEN DES FORMULARS ZUM NEUEINTRAG
	function showNeueintrag(){
		
		// Formular "aenderung" und "neueDaten" verstecken
		$('#aenderung').hide();
		$('#neueDaten').hide();
		$('#bedienAenderung').hide();
		
		// Formular "neueintrag" anzeigen
		$('#neueintrag').show();
		$('#bedienNeu').show();
		
		// Formular "neueintrag" komplett überprüfen
		pruefeFormular('1');
	}
	
	// FUNKTION ZUM ANZEIGEN DES FORMULARS ZUR ÄNDERUNG
	function showAenderung(){

		/*
		
		// Formular "neueintrag" verstecken
		$('#neueintrag').hide();
		$('#bedienNeu').hide();

		// Formular "aenderung" anzeigen
		$('#aenderung').show();
		$('#bedienAenderung').show();

		*/

		// Formular "aenderung" komplett prüfen
		// TODO: pruefeFormular('2');
	}

	// FUNKTION ZUM ABSENDEN BEIDER FORMULARE (Übergabewert: 1=Form Neueintrag / 2=Form Änderung)
	function senden(form){
		
		// Wenn das Neueintragsformular ausgefüllt wurde
		if (form == '1'){
			
			// Fehlermeldungen löschen
			$('#errorSenden1').hide();
			$('#successSenden1').hide();
			
			// Wartezeichen anzeigen
			$("#wait1").show();
			
			// Eingetragener User abfragen
			var userName = $('#userName1').val();
			var userEmail = $('#userEmail1').val();
			
			// Alle Dropdownfelder abfragen
			var bundesland = $('#selectBundesland').val(); 		// Wert des Selektierten Bundeslandes (z.B. "BY", "", "HH")
			var ort = $('#selectOrtsname :selected').text(); 	// Text des Selektierten Ortes (z.B. "München")
			var art = $('#selectArt').val(); 					// Wert der Selektierten Art (z.B. "P", "", "S", "M") 
			
			// Alle Inputfelder abfragen
			var name = $('#name').val();						// Wert des Namenfeldes (z.B. "Grüneburgpark)
			var beschreibung = $('#beschreibung').val();		// Wert des Beschreibungfeldes (z.B. "Der Grüneburgpark ist ein Park.")
			var breitengrad = $('#breitengrad').val();			// Wert des Breitengradfeldes (z.B. "50.435434324")
			var laengengrad = $('#laengengrad').val();			// Wert des Längengradfeldes (z.B. "9.029283833")
			
			// Komma in Koordinaten durch Punkt ersetzen (Falls Benutzer Koordinaten von Hand eingibt mit Komma)
			breitengrad = breitengrad.replace(",",".");
			laengengrad = laengengrad.replace(",",".");
			
			// Wenn alle Felder des Formulars ausgefüllt wurden
			if (bundesland != "" && ort != "" && art != "" && name != "" && beschreibung != "" && breitengrad != "" && laengengrad != ""){

				// Prüfen, ob Koordinatenfelder korrekt ausgefüllt sind
				var coordinateState = checkCoordinates();
				
				// Wenn Koordinaten Zahlen sind
				if(coordinateState){
					
					// Ajax-Aufruf
					$.ajax({
	        	   		type: "POST",
	        	   		url: "neueintrag.php",
	        	   		cache: false,
	        	   		data: { bundesland: bundesland, ort: ort, art: art, name: name, beschreibung: beschreibung, breitengrad: breitengrad, laengengrad: laengengrad, userName: userName, userEmail: userEmail},
	        	   		success: function(data){

		        	   		// Wartezeichen verstecken
		        	   		$("#wait1").hide();
		        	   		
		        	   		// Wenn die php-Datei erfolgreich abgelaufen ist
		                    if(data=='ok'){
			                    
		                        // Button disablen
		                    	$('#btnSenden1').attr('disabled', 'disabled');
		                    	
		                    	// Inputfelder leeren
		                    	$('#name').val("");
		                    	$('#beschreibung').val("");
		                    	$('#breitengrad').val("");
		                    	$('#laengengrad').val("");
		                    	
		                    	// Erfolgsmeldung anzeigen
		                    	$("#successSenden1").html("<b>Erfolg!</b> Deine Daten wurden erfolgreich übermittelt.<br>Vielen Dank für deine Mithilfe!");
		                     	$("#successSenden1").show();
		                    }
		                    
		                    else if(data=="nichtAusgefuellt"){
			                    
		                    	// Fehlermeldung anzeigen
		        				$('#errorSenden1').html("<b>Fehler!</b> Bitte füllen Sie alle Felder korrekt aus.");
		        				$('#errorSenden1').show();
		                    }
		                    
		                    else if(data=="keineArt"){
			                    
		                    	// Fehlermeldung anzeigen
		        				$('#errorSenden1').html("<b>Fehler!</b> Diese Art existiert nicht!");
		        				$('#errorSenden1').show();
		                    }
		                    
		                    else if(data=="keinBundesland"){
			                    
		                    	// Fehlermeldung anzeigen
		        				$('#errorSenden1').html("<b>Fehler!</b> Dieses Bundesland existiert nicht!");
		        				$('#errorSenden1').show();
		                    }

							else if(data.substring(0,9)=="Duplicate"){
			                    
		                    	// Fehlermeldung anzeigen
		        				$('#errorSenden1').html("<b>Fehler!</b> Es existiert bereits ein Neueintrag für diesen Ort, dieser befindet sich momentan in der Überprüfungsphase!");
		        				$('#errorSenden1').show();
		                    }
		                    
		                    else{
			                    
		                        // Fehlermeldung anzeigen
		                   	 	$("#errorSenden1").html("<b>Fehler!</b> Deine Daten konnten leider nicht übermittelt werden.<br><br>Folgender Fehler ist aufgetreten:<br>" + data);
		                 	 	$("#errorSenden1").show();
		                    }
		                }
	            	});
				}

				// Wenn die Koordinaten keine Zahlen sind
				else{
					
					// Wartezeichen verstecken
					$("#wait1").hide();
					
					// Fehlermeldung anzeigen
					$('#errorSenden1').html("<b>Fehler!</b> Bitte füllen Sie alle Felder korrekt aus.");
					$('#errorSenden1').show();
				}
			}

			// Wenn nicht alle Felder ausgefüllt sind
			else{
				
				// Wartezeichen verstecken
				$("#wait1").hide();
				
				// Fehlermeldung anzeigen
				$('#errorSenden1').html("<b>Fehler!</b> Bitte füllen Sie alle Felder korrekt aus.");
				$('#errorSenden1').show();
			}
		}
			
		// Wenn das Änderungsformular ausgefüllt wurde
		else if (form == '2') {
			// TODO: Absenden der Änderung;
		}
	}

	// FUNKTION ZUM PRÜFEN DES FORMULARS AUF KORREKTE EINGABEN ZUM ANZEIGEN DES SENDEN-BUTTONS
	function pruefeFormular(form){

		// Wenn das Neueintragsformular geprüft werden soll
		if (form == '1') {

			// Alle Dropdownfelder abfragen
			var bundesland = $('#selectBundesland').val(); 		// Wert des Selektierten Bundeslandes (z.B. "BY", "", "HH")
			var ort = $('#selectOrtsname :selected').text(); 	// Text des Selektierten Ortes (z.B. "Ensdorf (Amberg-Sulzbach)")
			var art = $('#selectArt').val(); 					// Wert der Selektierten Art (z.B. "P", "", "S", "M") 
			
			// Alle Inputfelder abfragen
			var name = $('#name').val();						// Wert des Namenfeldes (z.B. "Grüneburgpark)
			var beschreibung = $('#beschreibung').val();		// Wert des Beschreibungfeldes (z.B. "Der Grüneburgpark ist ein Park.")
			var breitengrad = $('#breitengrad').val();			// Wert des Breitengradfeldes (z.B. "50.435434324")
			var laengengrad = $('#laengengrad').val();			// Wert des Längengradfeldes (z.B. "9.029283833")

			// Wenn alle Felder des Formulars ausgefüllt wurden
			if (bundesland != "" && ort != "Bitte wählen" && art != "" && name != "" && beschreibung.length != "" && breitengrad != "" && laengengrad != ""){

				// Senden-Button anzeigen
				$('#btnSenden1').removeAttr('disabled');
			}

			// Wenn nicht alle Felder des Formulars ausgefüllt wurden
			else{

				// Senden-Button verstecken
				$('#btnSenden1').attr('disabled', 'disabled');
			}
		}

		// Wenn das Änderungsformular geprüft werden soll
		else if (form == '2') {

			// Alle Änderungsfelder abfragen
			var nameNeu = $('#name').val();
			var beschrNeu = $('#beschreibung').val();
			var longNeu = $('#laengengrad').val();
			var latNeu = $('#breitengrad').val();

			// Wenn etwas geändert wurde
			if ((nameNeu != nameAlt && nameAlt != "") || (beschrNeu != beschreibungAlt && beschreibungAlt != "") ||
					 (longNeu != laengengradAlt && laengengradAlt != "") || (latNeu != breitengradAlt && breitengradAlt != "")){

				// Senden-Button anzeigen
				$('#btnSenden2').removeAttr('disabled');
			}

			// Wenn nichts geändert wurde
			else{

				// Senden-Button verstecken
				$('#btnSenden2').attr('disabled', 'disabled');
			}
		}
	}

	// FUNKTION ZUM ANZEIGEN ALLER STÄDTE FÜR DAS GEWÄHLTE BUNDESLAND
	// (art: '0'=Neueintrag, '1'=Änderung / str: Wert des Selektierten Feldes (z.B. "BY") / index: index des Selektierten Feldes (z.B. "BW"=1, "BY"=2)
	function showCity(art, str, index){
		
		// Wenn im Neueintragsformular das Bundesland gewählt wurde
		if (art == '0'){
			
			// Wartezeichen anzeigen
			$('#waitOrte').show();
			
			// Alle Änderungsfelder abfragen
			var nameNeu = $('#name').val();
			var beschrNeu = $('#beschreibung').val();
			var longNeu = $('#laengengrad').val();
			var latNeu = $('#breitengrad').val();
			
			// Wenn schon Daten eingetragen worden sind
			if (nameNeu != "" || beschrNeu != "" || longNeu != "" || latNeu != ""){
				
				// Meldung anzeigen ob alles verwerfen werden soll
				var verwerfen = confirm(unescape("Das Formular enthält bereits Daten!\nSollen deine bisherigen Eingaben verworfen werden?"));
				
					// Wenn alles verwerfen werden soll
					if (verwerfen){
						
						// Alle Eingabefelder leeren
						$('#name').val("");
						$('#beschreibung').val("");
						$('#breitengrad').val("");
						$('#laengengrad').val("");

						// Selectfelder "gefundeneOrte" leeren und verstecken
						document.getElementById("gefundeneOrte").innerHTML="";
					  	$('#gefundeneOrte').hide();
					  	
						// Globale Variable wird auf den aktuellen Index gesetzt
						selectBundeslandIndex = index;
						
						// Wenn ein Bundesland selektiert wurde (z.B. "BY")
						if (str != ""){
							
							// Action für 'holeOrte.php' ('0' -> Abfrage nach Orten)
							var action = 0;
							
					  		// Daten aus DB holen
					  		getCitiesFromDB(art, str, action);
						}

						// Wenn "Bitte wählen" ausgewählt wurde
						else{
							
							// Wartezeichen verstecken
							$('#waitOrte').hide();
						}
					}

					// Wenn die gewählten Daten nicht verworfen werden sollen
					else{
						
						// Selectfeld zurücksetzen
						document.getElementById("selectBundesland").selectedIndex=selectBundeslandIndex;
						
						// Wartezeichen verstecken
						$('#waitOrte').hide();
					}
			}
			
			// Wenn noch kein Daten eingetragen worden sind
			else{
				
				// Alle Eingabefelder leeren
				$('#name').val("");
				$('#beschreibung').val("");
				$('#breitengrad').val("");
				$('#laengengrad').val("");

				// Selectfelder "gefundeneOrte" leeren und verstecken
				document.getElementById("gefundeneOrte").innerHTML="";
			  	$('#gefundeneOrte').hide();
			  	
				// Globale Variable wird auf den aktuellen Index gesetzt
				selectBundeslandIndex = index;
				
				// Wenn ein Bundesland selektiert wurde (z.B. "BY")
				if (str != ""){
					
					// Action für 'holeOrte.php' ('0' -> Abfrage nach Orten)
					var action = 0;
					
			  		// Daten aus DB holen
			  		getCitiesFromDB(art, str, action);
				}

				// Wenn "Bitte wählen" ausgewählt wurde
				else{
					
					// Wartezeichen verstecken
					$('#waitOrte').hide();
				}
			}
		}
		
		// Wenn im Änderungsformular das Bundesland gewählt wurde
		else if (art == '1'){
			
			// Wartezeichen anzeigen
			$('#waitOrteAenderung').show();

			// Alle Änderungsfelder abfragen
			var nameNeu = $('#nameNeu').val();
			var beschrNeu = $('#beschreibungNeu').val();
			var longNeu = $('#laengengradNeu').val();
			var latNeu = $('#breitengradNeu').val();
			
			// Wenn schon Daten eingetragen worden sind
			if (nameNeu != "" || beschrNeu != "" || longNeu != "" || latNeu != ""){
				
				// Meldung anzeigen ob alles verwerfen werden soll
				var verwerfen = confirm(unescape("Das Formular enthält bereits Daten!\nSollen deine bisherigen Eingaben verworfen werden?"));
				
				// Wenn alles verwerfen werden soll
				if (verwerfen){
					
					// Alle Felder leeren
					$('#nameNeu').val("");
					$('#beschreibungNeu').val("");
					$('#breitengradNeu').val("");
					$('#laengengradNeu').val("");
					
					// Selectfelder "gefundeneOrte" leeren und verstecken
					document.getElementById("gefundeneOrteAenderung").innerHTML="";
				  	$('#gefundeneOrteAenderung').hide();

				 	// Globale Variable wird auf den aktuellen Index gesetzt
					selectedBundeslandAenderung = index;
					
					// Wenn ein Bundesland selektiert wurde (z.B. "BY")
					if (str != ""){

						// Action für 'holeOrte.php' ('0' -> Abfrage nach Orten)
						var action = 0;
						
				  		// Daten aus DB holen
				  		getCitiesFromDB(art, str, action);
					}

					// Wenn "Bitte wählen" ausgewählt wurde
					else{

						// Wartezeichen verstecken
						$('#waitOrteAenderung').hide();
					}
				}

				// Wenn nicht verworfen werden soll
				else{
					
					// Selectfeld zurücksetzen
					document.getElementById("selectBundeslandAenderung").selectedIndex=selectedBundeslandAenderung;
					
					// Wartezeichen verstecken
					$('#waitOrteAenderung').hide();
				}
			}
			
			// Wenn noch keine Daten eingetragen worden sind
			else{
				
				// Alle Felder leeren
				$('#nameNeu').val("");
				$('#beschreibungNeu').val("");
				$('#breitengradNeu').val("");
				$('#laengengradNeu').val("");
				
				// Selectfelder "gefundeneOrte" leeren und verstecken
				document.getElementById("gefundeneOrteAenderung").innerHTML="";
			  	$('#gefundeneOrteAenderung').hide();

			 	// Globale Variable wird auf den aktuellen Index gesetzt
				selectedBundeslandAenderung = index;
				
				// Wenn ein Bundesland selektiert wurde (z.B. "BY")
				if (str != ""){

					// Action für 'holeOrte.php' ('0' -> Abfrage nach Orten)
					var action = 0;
					
			  		// Daten aus DB holen
			  		getCitiesFromDB(art, str, action);
				}

				// Wenn "Bitte wählen" ausgewählt wurde
				else{

					// Wartezeichen verstecken
					$('#waitOrteAenderung').hide();
				}
			}
		}
	}

	// FUNKTION ZUM ANZEIGEN ALLER STÄDTE IN DEM GEWÄHLTEN BUNDESLAND
	// (art: '0'=Neueintrag, '1'=Änderung / str: Wert des Selektierten Feldes (z.B. "BY") / action: '0'=Abfrage nach Orten, '1'=Abfrage nach Sehensw., Museen, Parks)
	function getCitiesFromDB(art, str, action){
		
		// xmlhttp Objekt erstellen für IE7+, Firefox, Chrome, Opera, Safari
		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}
		
		// xmlhttp Objekt erstellen für IE6, IE5
		else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		// Für das xmlhttp-Objekt eine Aktion definieren wenn es fertig ist
		xmlhttp.onreadystatechange=function(){
			
			// Wenn der Server mit "200" geantwortet hat und eine Antwort zurückkam
			if (xmlhttp.readyState==4 && xmlhttp.status==200){

				// Variable deklarieren und zuweisen
				var text = xmlhttp.responseText;
				
				// Wenn im Neueintragsformular die gefundenen Orte angezeigt werden sollen
				if (art == '0'){

					// Den Platzhalter für die Orte füllen und anzeigen
					document.getElementById("gefundeneOrte").innerHTML = text;
		    		$('#gefundeneOrte').show();

		    		// Wartezeichen verstecken
		    		$('#waitOrte').hide();
				}

				// Wenn im Änderungsformular die gefundenen Orte angezeigt werden sollen
				else if (art == '1'){

					// Den Platzhalter für die Orte füllen und anzeigen
					document.getElementById("gefundeneOrteAenderung").innerHTML = text;
		    		$('#gefundeneOrteAenderung').show();

		    		// Wartezeichen verstecken
		    		$('#waitOrteAenderung').hide();
				}
		    }
		};

		// Das xmlhttp-Objekt mit Parametern absenden 
		xmlhttp.open("GET","holeOrte.php?bundesland=" + str + "&action=" + action + "&art=" + art,true);
		xmlhttp.send();
	}

	// FUNKTION ZUM ÄNDERN DES ORTES
	function changeCity(form, value, index){

		// Wenn "Bitte wählen" gewählt wurde
		if (index == 0){

			// Werte abfragen
			var blValue;
			var blSelIndex;
			
			// Wenn Neueintragsformular
			if (form == 0){
				
				// Werte abfragen
				blValue = $('#selectBundesland').val();
				blSelIndex = document.getElementById("selectBundesland").selectedIndex;
			}

			// Wenn Änderungsformular
			else{

				// Werte abfragen
				blValue = $('#selectBundeslandAenderung').val();
				blSelIndex = document.getElementById("selectBundeslandAenderung").selectedIndex;
			}
			
			// Bundesland zentrieren
			setCenterOnBl(blValue);
		}

		// Wenn Ort gewählt wurde
		else{
			
			// Wenn im Neueintragsformular der Ort geändert wurde
			if (form == '0'){
				
				// Alle Änderungsfelder abfragen
				var nameNeu = $('#name').val();
				var beschrNeu = $('#beschreibung').val();
				var longNeu = $('#laengengrad').val();
				var latNeu = $('#breitengrad').val();
				var art = $('#selectArt').val();
				
				// Wenn schon Daten eingetragen worden sind
				if (nameNeu != "" || beschrNeu != "" || longNeu != "" || latNeu != ""){
					
					// Meldung anzeigen ob alles verwerfen werden soll
					var verwerfen = confirm(unescape("Das Formular enthält bereits Daten!\nSollen deine bisherigen Eingaben verworfen werden?"));
					
						// Wenn alles verwerfen werden soll
						if (verwerfen){
							
							// Alle Eingabefelder leeren
							$('#name').val("");
							$('#beschreibung').val("");
							$('#breitengrad').val("");
							$('#laengengrad').val("");
	
							// Globale Variable wird auf den aktuellen Index gesetzt
							selectOrtIndex = index;
	
							// Marker wird auf aktuelle Stadt gesetzt
							setMarkerOnCity(form, index);
	
							// Alle Plätze der gewählten Art anzeigen
							if (art != ""){
								showPlaces(art);
							}
						}
	
						// Wenn die gewählten Daten nicht verworfen werden sollen
						else{
							
							// Selectfeld zurücksetzen
							document.getElementById("selectOrtsname").selectedIndex=selectOrtIndex;
						}
				}
				
				// Wenn noch kein Daten eingetragen worden sind
				else{
	
					// Globale Variable wird auf den aktuellen Index gesetzt
					selectOrtIndex = index;
					
					// Alle Eingabefelder leeren
					$('#name').val("");
					$('#beschreibung').val("");
					$('#breitengrad').val("");
					$('#laengengrad').val("");
	
				 	// Marker wird auf aktuelle Stadt gesetzt
					setMarkerOnCity(form, index);
	
					// Alle Plätze der gewählten Art anzeigen
					if (art != ""){
						showPlaces(art);
					}
				}
	
			}
			
			// Wenn im Änderungsformular der Ort geändert wurde
			else if (form == '1'){
				
				// Alle Usereingaben abfragen
				var nameNeu = $('#nameNeu').val();
				var beschrNeu = $('#beschreibungNeu').val();
				var longNeu = $('#laengengradNeu').val();
				var latNeu = $('#breitengradNeu').val();
				
				// Wenn schon Daten ausgefüllt sind
				if (nameNeu != "" || beschrNeu != "" || longNeu != "" || latNeu != ""){
					
					// Meldung anzeigen ob alles verwerfen werden soll
					var verwerfen = confirm(unescape("Sollen deine bisherigen Eingaben verworfen werden?"));
					
					// Wenn alles verwerfen werden soll
					if (verwerfen){
	
						// Alle Felder leeren
						$('#nameNeu').val("");
						$('#beschreibungNeu').val("");
						$('#breitengradNeu').val("");
						$('#laengengradNeu').val("");
											
						// Wenn "Bitte wählen" gewählt wurde
						if (value == ""){
							
							// Alte Daten, neue Daten und alte Details verstecken
							$('#alteDaten').hide();
							$('#neueDaten').hide();
							$('#alteDetails').hide();
	
							// "Art" zurücksetzen
							document.getElementById("selectArtAenderung").selectedIndex = 0;
						}
	
						// Wenn ein Ort gewählt wurde
						else if (value != ""){
												
							// Neue Daten und alte Details verstecken
							$('#neueDaten').hide();
							$('#alteDaten').hide();
							$('#alteDetails').hide();
	
							// "Art" zurücksetzen
							document.getElementById("selectArtAenderung").selectedIndex = 0;
						}
						
						// Globale Variable wird auf den aktuellen Index gesetzt
						selectOrtAenderungIndex = index;
	
						// Marker wird auf aktuelle Stadt gesetzt
						setMarkerOnCity(form, index);
	
						// Feld "artAenderung" anzeigen
						$('#artAenderung').show();
					}
					
					// Wenn die gewählten Daten nicht verworfen werden sollen
					else{
						
						// Selectfeld zurücksetzen
						document.getElementById("selectOrtsnameAenderung").selectedIndex=selectedOrtAenderungIndex;
					}
				}
				// Wenn noch keine Daten ausgefüllt sind
				else{
					
					// Alle Felder leeren
					$('#nameNeu').val("");
					$('#beschreibungNeu').val("");
					$('#breitengradNeu').val("");
					$('#laengengradNeu').val("");
	
					// Wenn "Bitte wählen" gewählt wurde
					if (value == ""){
						
						// Alte Daten, neue Daten und alte Details verstecken
						$('#alteDaten').hide();
						$('#neueDaten').hide();
						$('#alteDetails').hide();
	
						// "Art" zurücksetzen
						document.getElementById("selectArtAenderung").selectedIndex = 0;
					}
	
					// Wenn ein Ort gewählt wurde
					else if (value != ""){
	
						// Neue Daten und alte Details verstecken
						$('#neueDaten').hide();
						$('#alteDaten').hide();
						$('#alteDetails').hide();
	
						// "Art" zurücksetzen
						document.getElementById("selectArtAenderung").selectedIndex = 0;
					}
	
					// Globale Variable wird auf den aktuellen Index gesetzt
					selectedOrtAenderungIndex = index;
					
					// Marker wird auf aktuelle Stadt gesetzt
					setMarkerOnCity(form, index);
	
					// Feld "artAenderung" anzeigen
					$('#artAenderung').show();
				}
			}
		}
	}

	// FUNKTION ZUM SETZEN EINES MARKERS AUF EINE STADT
	function setMarkerOnCity(form, index){
		
		// Variablen initialisieren
		var name = "";
		var indexBundesland = 0;
		var bundesland = "";
		var address = "";
		
		// Wenn im Neueintragsformular der Ort geändert wurde
		if (form == '0'){

			// Variablen abfragen und erstellen
		    name = document.getElementById("selectOrtsname")[index].text;
			indexBundesland = document.getElementById("selectBundesland").selectedIndex;
		    bundesland = document.getElementById("selectBundesland")[indexBundesland].text;
		    address = name + ", " + bundesland + ", Deutschland";
		}

		// Wenn im Änderungsformular der Ort geändert wurde
		else if (form == '1'){

			// Variablen abfragen und erstellen
			name = document.getElementById("selectOrtsnameAenderung")[index].text;
			indexBundesland = document.getElementById("selectBundeslandAenderung").selectedIndex;
		    bundesland = document.getElementById("selectBundeslandAenderung")[indexBundesland].text;
		    address = name + ", " + bundesland + ", Deutschland";
		}

		geocoder.geocode( { 'address': address}, function(results, status) {

		    // Wenn ein gültiger Wert gefunden wurde
		    if (status == google.maps.GeocoderStatus.OK) {
			    
		    	// Alle Marker löschen
				clearMarkers();
				
				// Da vorher alle Marker gelöscht wurden, neuen HomeMarker setzen
				if (erfolg){
					setHomeMarker();
				}

				// Karte zentrieren und zoomen
				map.setCenter(results[0].geometry.location);
		      	map.setZoom(11);

		     	// Neuen Marker erstellen
		      	var marker4 = new google.maps.Marker({
					position : results[0].geometry.location,
					map : map,
					draggable : false,
					title: name
				});

		     	// Marker dem Markerarray hinzufügen
				markersArray.push(marker4);

				// Eigenes Marker Bild definieren
				var iconFile = 'img/city.png';
		    	marker4.setIcon(iconFile);

		    	// Dem Marker einen Klick-Listener hinzufügen
				google.maps.event.addListener(marker4, "click", function(event) {
					
					// Auswahlfelder abfragen
					var radioNeueintrag = document.getElementById("radioNeueintrag").checked;
					var radioAendern =  document.getElementById("radioAenderung").checked;
					
					// Wenn Neueintrag gewählt ist
					if (radioNeueintrag){

						// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
						document.getElementById('breitengrad').value   = event.latLng.lat();
		            	document.getElementById('laengengrad').value  = event.latLng.lng();

		            	// Prüfe, ob das Formular vollständig ausgefüllt ist
		            	pruefeFormular('1');
					}
					
					else if (radioAendern){

						// Trage die aktuellen Koordinaten in die Felder des Änderungsformulars ein
						document.getElementById('breitengradNeu').value   = event.latLng.lat();
		            	document.getElementById('laengengradNeu').value  = event.latLng.lng();
					}
				});
		    }
		    
			// Wenn kein Ergebnis gefunden wurde
			else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
				alert("Es wurde kein Ergebnis gefunden!");
		    }

		    // Wenn ein anderer Fehler aufgetreten ist
		    else {
				alert("Es ist folgender Fehler aufgetreten:\n" + status);
			}
	    });
	}

	// FUNKTION ZUM PRÜFEN DER KOORDINATEN AUF DEREN KORREKTHEIT
	function checkCoordinates(){
		
		// Farben der Koordinatenfelder wieder auf Normal setzen
		$('#breitengrad').attr('style', 'border-color: rgb(204,204,204); background-color: white;');
		$('#laengengrad').attr('style', 'border-color: rgb(204,204,204); background-color: white;');
		
		// Abfragen der Koordinaten
		var breitengrad = $('#breitengrad').val();		// Wert des Breitengradfeldes (z.B. "50.4454646464")
		var laengengrad = $('#laengengrad').val();		// Wert des Längengradfeldes (z.B. "9.029283833")
		
		// Komma in Koordinaten durch Punkt ersetzen (Falls Benutzer Koordinaten von Hand eingibt mit Komma)
		breitengrad = breitengrad.replace(",",".");
		laengengrad = laengengrad.replace(",",".");
		
		// Wenn Breitengrad keine Zahl ist
		if (isNaN(breitengrad)) {
			
			// Feld rot markieren
			$('#breitengrad').css("border-color","red");
			$('#breitengrad').css("background-color","salmon");
			
			// Wenn Längengrad keine Zahl ist
			if (isNaN(laengengrad)) {
				
				// Feld rot markieren
				$('#laengengrad').css("border-color","red");
				$('#laengengrad').css("background-color","salmon");
			}

			return false;
		}
		
		// Wenn Breitengrad eine Zahl ist
		else{
			
			// Wenn Längengrad keine Zahl ist
			if (isNaN(laengengrad)) {
				
				// Feld rot markieren
				$('#laengengrad').css("border-color","red");
				$('#laengengrad').css("background-color","salmon");
				
				return false;
			}

			// Wenn Längengrad eine Zahl ist
			else{
				return true;
			}
		}
	}

	// FUNKTION ZUR ANZEIGE DER AKTUELLEN DATEN
	// (value: Wert des selektierten Feldes z.B. "M", "S", "P", "" / index: 0='Bitte wählen', 1='Sehenswürdigkeiten', ...)
	function showDetails(index){
		
		// Wenn "Bitte wählen" ausgewählt wird
		if (index == 0){
			
			// Alle Usereingaben abfragen
			var nameNeu = $('#nameNeu').val();
			var beschrNeu = $('#beschreibungNeu').val();
			var longNeu = $('#laengengradNeu').val();
			var latNeu = $('#breitengradNeu').val();
			
			// Wenn schon Daten ausgefüllt sind
			if (nameNeu != "" || beschrNeu != "" || longNeu != "" || latNeu != ""){
				
				// Meldung anzeigen ob alles verwerfen werden soll
				var verwerfen = confirm(unescape("Sollen deine bisherigen Eingaben verworfen werden?"));
				
				// Wenn alles verwerfen werden soll
				if (verwerfen){
					
					// Alle Felder leeren
					$('#nameNeu').val("");
					$('#beschreibungNeu').val("");
					$('#breitengradNeu').val("");
					$('#laengengradNeu').val("");

					// Neue Daten und alte Daten verstecken
					$('#alteDaten').hide();
					$('#neueDaten').hide();
					$('#alteDetails').hide();

				}

				// Wenn nichts verworfen werden soll
				else{
					
					// Selectfeld zurücksetzen
					document.getElementById("selectArtAenderung").selectedIndex=selectedArtAenderung;
					
					// Wartezeichen verstecken
					//$('#waitOrteAenderung').hide();
				}
			}
			
			// Wenn noch keine Daten ausgefüllt sind
			else{
				
				// Beide Formulare verstecken
				$('#neueDaten').hide();
				$('#alteDaten').hide();
				$('#alteDetails').hide();
			}
		}
		
		// Wenn eine Art ausgewählt ist
		else if (index != 0){

			// Wartezeichen anzeigen
		    $('#waitNameAlt').show();

			// Alle Variablen initialisieren
			var tabelle = "";
			var bundesland = "";
			var name = "";
			var kreis = "";
			
			// Action für 'holeOrte.php' ('1' -> Abfrage nach Sehensw., Museen, Parks)
			var action = 1;
			
			// Alle Usereingaben abfragen
			var nameNeu = $('#nameNeu').val();
			var beschrNeu = $('#beschreibungNeu').val();
			var longNeu = $('#laengengradNeu').val();
			var latNeu = $('#breitengradNeu').val();
			
			// Wenn schon Daten ausgefüllt sind
			if (nameNeu != "" || beschrNeu != "" || longNeu != "" || latNeu != ""){
				
				// Meldung anzeigen ob alles verwerfen werden soll
				var verwerfen = confirm(unescape("Sollen deine bisherigen Eingaben verworfen werden?"));
				
				// Wenn alles verwerfen werden soll
				if (verwerfen){

					// Platzhalter mit Städten leeren und verstecken
					$('#nameAlt').hide();
					
					// Alle Felder leeren, zurücksetzen und verstecken
					$('#nameNeu').val("");
					$('#beschreibungNeu').val("");
					$('#breitengradNeu').val("");
					$('#laengengradNeu').val("");

					// Beide Formulare verstecken
					$('#neueDaten').hide();
					$('#alteDaten').hide();
					$('#alteDetails').hide();
					
					// Variabeln deklarieren
					var ort = $('#selectOrtsnameAenderung :selected').text();
					var nameundkreis = ort.split("(");
					name = nameundkreis[0].substr(0, nameundkreis[0].length-1);
					kreis = nameundkreis[1].substr(0, nameundkreis[1].length-1);
					bundesland = $('#selectBundeslandAenderung').val();
					
					// Fallunterscheidung anhand der art
					switch (index) {
						case 1: tabelle = "sehensw"; break;
						case 2: tabelle = "parks"; break;
						case 3: tabelle = "museen"; break;
						default: document.getElementById("nameAlt").innerHTML=""; $('#nameAlt').hide(); break;
					}
					
					// Globale Variable wird auf den aktuellen Index gesetzt
					selectedArtAenderung = index;
					
					// Daten aus DB holen
			  		getPlacesFromDB(tabelle, name, bundesland, kreis, action);

			  		// Formular mit alten Daten anzeigen
					$('#alteDaten').show();
				}

				// Wenn nicht verwerfen werden soll
				else{
					
					// Selectfeld zurücksetzen
					document.getElementById("selectArtAenderung").selectedIndex=selectedArtAenderung;
					
					// Wartezeichen verstecken
					//$('#waitOrteAenderung').hide();
				}
			}
			
			// Wenn noch keine Daten ausgefüllt sind
			else{

				// Platzhalter mit Städten leeren und verstecken
				$('#nameAlt').hide();

				// Beide Formulare verstecken
				$('#neueDaten').hide();
				$('#alteDaten').hide();
				$('#alteDetails').hide();
				
				// Variabeln deklarieren
				var ort = $('#selectOrtsnameAenderung :selected').text();
				var nameundkreis = ort.split("(");
				name = nameundkreis[0].substr(0, nameundkreis[0].length-1);
				kreis = nameundkreis[1].substr(0, nameundkreis[1].length-1);
				bundesland = $('#selectBundeslandAenderung').val();
				
				// Fallunterscheidung anhand der art
				switch (index) {
					case 1: tabelle = "sehensw"; break;
					case 2: tabelle = "parks"; break;
					case 3: tabelle = "museen"; break;
					default: document.getElementById("nameAlt").innerHTML=""; $('#nameAlt').hide(); break;
				}
				
				// Globale Variable wird auf den aktuellen Index gesetzt
				selectedArtAenderung = index;
				
				// Daten aus DB holen
		  		getPlacesFromDB(tabelle, name, bundesland, kreis, action);

		  		// Formular mit alten Daten anzeigen
				$('#alteDaten').show();
			}
		}
	}
	
	// FUNKTION ZUM ANZEIGEN ALLER SEHENSW., PARKS, MUSEEN DER GEWÄHLTEN STADT
	// (tabelle: Tabelle in DB / name: Stadtname / bundesland: Abkürzung des Bundeslandes / kreis: Kreisnam / action: '0'=Abfrage nach Orten, '1'=Abfrage nach Sehensw., Museen, Parks)
	function getPlacesFromDB(tabelle, name, bundesland, kreis, action){
		
		// xmlhttp Objekt erstellen für IE7+, Firefox, Chrome, Opera, Safari
		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}
		
		// xmlhttp Objekt erstellen für IE6, IE5
		else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		// Für das xmlhttp-Objekt eine Aktion definieren wenn es fertig ist
		xmlhttp.onreadystatechange=function(){

			// Wenn der Server mit "200" geantwortet hat und eine Antwort zurückkam
			if (xmlhttp.readyState==4 && xmlhttp.status==200){

				// Variable deklarieren und zuweisen
				var text = xmlhttp.responseText;

				// Den Platzhalter für die Orte füllen und anzeigen
	    		document.getElementById("nameAlt").innerHTML = text;
	    		$('#nameAlt').show();

	    		// Wartezeichen verstecken
	    		$('#waitNameAlt').hide();
		    }
		};

		// Das xmlhttp-Objekt mit Parametern absenden 
		xmlhttp.open("GET","holeOrte.php?tabelle=" + tabelle + "&name=" + name + "&bundesland=" + bundesland + "&action=" + action,true);
		xmlhttp.send();
	}

	// FUNKTION ZUM ANZEIGEN DER BESCHREIBUNG UND DER KOORDINATEN DES GEWÄHLTEN ORTES
	// (value: Wert des selektierten Feldes z.B. "M", "S", "P", "")
	function showObjectDetails(value){
		
		// Wartezeichen anzeigen
		$('#waitAlteDetails').show();
		
		// Wenn "Bitte wählen" ausgewählt ist
		if (value == ""){
			
			// Alle Usereingaben abfragen
			var nameNeu = $('#nameNeu').val();
			var beschrNeu = $('#beschreibungNeu').val();
			var longNeu = $('#laengengradNeu').val();
			var latNeu = $('#breitengradNeu').val();
			
			// Wenn etwas geändert wurde
			if ((nameNeu != nameAlt && nameAlt != "") || (beschrNeu != beschreibungAlt && beschreibungAlt != "") ||
					 (longNeu != laengengradAlt && laengengradAlt != "") || (latNeu != breitengradAlt && breitengradAlt != "")){
				
				// Meldung anzeigen ob alles verwerfen werden soll
				var verwerfen = confirm(unescape("Sollen deine bisherigen Eingaben verworfen werden?"));
				
				// Wenn alles verwerfen werden soll
				if (verwerfen){
					
					// Alle Felder leeren
					$('#nameNeu').val("");
					$('#beschreibungNeu').val("");
					$('#breitengradNeu').val("");
					$('#laengengradNeu').val("");

					// Globale Variablen zurücksetzen
					nameAlt = "";
					breitengradAlt = "";
					laengengradAlt = "";
					beschreibungAlt = "";

					// Neue Daten und alte Details verstecken
					$('#neueDaten').hide();
					$('#alteDetails').hide();
				}

				// Wenn nichts verworfen werden soll
				else{
					
					// Selectfeld zurücksetzen
					document.getElementById("selectNameAlt").selectedIndex=selectedNameAlt;
				}
				
				// Wartezeichen verstecken
				$('#waitAlteDetails').hide();
			}
			
			// Wenn nichts geändert wurde oder feld leer ist
			else{

				// Globale Variable wird auf den aktuellen Index gesetzt
				selectedNameAlt = document.getElementById("selectNameAlt").selectedIndex;
				
				// Globale Variablen zurücksetzen
				nameAlt = "";
				breitengradAlt = "";
				laengengradAlt = "";
				beschreibungAlt = "";
				
				// Neue Daten und alte Details verstecken
				$('#neueDaten').hide();
				$('#alteDetails').hide();

				// Wartezeichen verstecken
				$('#waitAlteDetails').hide();
			}
		}
		
		// Wenn eine Art ausgewählt ist
		else if (value != ""){

			// Alle Usereingaben abfragen
			var nameNeu = $('#nameNeu').val();
			var beschrNeu = $('#beschreibungNeu').val();
			var longNeu = $('#laengengradNeu').val();
			var latNeu = $('#breitengradNeu').val();
			
			// Wenn etwas geändert wurde
			if ((nameNeu != nameAlt && nameAlt != "") || (beschrNeu != beschreibungAlt && beschreibungAlt != "") ||
					 (longNeu != laengengradAlt && laengengradAlt != "") || (latNeu != breitengradAlt && breitengradAlt != "")){
				
				// Meldung anzeigen ob alles verwerfen werden soll
				var verwerfen = confirm(unescape("Sollen deine bisherigen Eingaben verworfen werden?"));
				
				// Wenn alles verwerfen werden soll
				if (verwerfen){
			
					// Alle Alte Detais abfragen
					var inputAltesObjekt = $('#selectNameAlt :selected').text();						// z.B. "Grüneburgpark (8.34, 3.45)"
					var nameundkoordinaten = inputAltesObjekt.split("("); 								// 0="Grüneburgpark " / 1="8.34, 3.45)"
					var name = nameundkoordinaten[0].substr(0, nameundkoordinaten[0].length-1); 		// "Grüneburgpark"
					var beidekoor = nameundkoordinaten[1].substr(0, nameundkoordinaten[1].length-1); 	// "8.34, 3.45"
					var beidekoordinaten = beidekoor.split(","); 										// 0="8.34" / 1=" 3.45"
					var breitengrad = beidekoordinaten[0];												// "8.34"
					var laengengrad = beidekoordinaten[1].substr(1, beidekoordinaten[1].length);		// "3.45"
					var beschreibung = document.getElementById("beschr_" + name).innerHTML;
			
					// Neue Details füllen
					$('#beschreibungAlt').val(beschreibung);
					$('#breitengradAlt').val(breitengrad);
					$('#laengengradAlt').val(laengengrad);
			
					// Neue Felder mit aktuellen Werten füllen
					$('#nameNeu').val(name);
					$('#beschreibungNeu').val(beschreibung);
					$('#breitengradNeu').val(breitengrad);
					$('#laengengradNeu').val(laengengrad);

					// Globale Variablen zurücksetzen
					nameAlt = name;
					breitengradAlt = breitengrad;
					laengengradAlt = laengengrad;
					beschreibungAlt = beschreibung;
					
					// Globale Variable wird auf den aktuellen Index gesetzt
					selectedNameAlt = document.getElementById("selectNameAlt").selectedIndex;
					
					// Alle Marker löschen
					clearMarkers();
					
					// Koordinaten berechnen
					var myLatlng = new google.maps.LatLng(breitengrad, laengengrad);
		
					// Neuen Marker setzen
					marker2 = new google.maps.Marker({
		            	position: myLatlng,
		            	map: map,
		            	title: name
		        	});
        	
					// Die unterschiedlichen Arten erhalten unterschiedliche Marker
					var iconFile = "";
					if (document.getElementById("selectArtAenderung").selectedIndex == 1) 		// Sehenswürdigkeit
		        		iconFile = 'img/sight.png';
					else if (document.getElementById("selectArtAenderung").selectedIndex == 2) 	// Park
		        		iconFile = 'img/park.png';
					else if (document.getElementById("selectArtAenderung").selectedIndex == 3) 	// Museum
		        		iconFile = 'img/museum.png';

		    		// Icon dem Marker zuweisen
			    	marker2.setIcon(iconFile);
		
			    	// Marker dem Markerarray hinzufügen
					markersArray.push(marker2);
		
					// Karte auf neuen Standort zentrieren und zoomen
					map.setCenter(myLatlng);
					map.setZoom(15);
					
					// Dem aktuellen Marker einen Klick-Listener hinzufügen
					google.maps.event.addListener(marker2, "click", function(event) {
						
						// Auswahlfelder abfragen
						var radioNeueintrag = document.getElementById("radioNeueintrag").checked;
						var radioAendern =  document.getElementById("radioAenderung").checked;
						
						// Wenn das Neueintragsformular gewählt ist
						if (radioNeueintrag){
		
							// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
							document.getElementById('breitengrad').value   = event.latLng.lat();
			            	document.getElementById('laengengrad').value  = event.latLng.lng();
		
			            	// Prüfe, ob das Formular vollständig ausgefüllt ist
			            	pruefeFormular('1');
						}
		
						// Wenn das Änderungsformular gewählt ist
						else if (radioAendern){
		
							// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
							document.getElementById('breitengradNeu').value   = event.latLng.lat();
			            	document.getElementById('laengengradNeu').value  = event.latLng.lng();
						}
					});
					
					// Da vorher alle Marker gelöscht wurden, muss der HomeMarker neu gesetzt werden, falls ein Standort gefunden wurde
					if (erfolg){
						setHomeMarker();
					}
					
					// Neue Daten und Alte Details anzeigen
					$('#neueDaten').show();
					$('#alteDetails').show();
					
					// Feld zum Eintragen eines neuen Namens fokussieren
					document.getElementById('nameNeu').focus();
		
					// Wartezeichen verstecken
					$('#waitAlteDetails').hide();
				}
				// Wenn nichts verworfen werden soll
				else{
					
					// Selectfeld zurücksetzen
					document.getElementById("selectNameAlt").selectedIndex=selectedNameAlt;

					// Wartezeichen verstecken
					$('#waitAlteDetails').hide();
				}
			}

			// Wenn nichts geändert wurde oder Felder leer sind
			else{
				
				// Alle Alte Detais abfragen
				var inputAltesObjekt = $('#selectNameAlt :selected').text();						// z.B. "Grüneburgpark (8.34, 3.45)"
				var nameundkoordinaten = inputAltesObjekt.split("("); 								// 0="Grüneburgpark " / 1="8.34, 3.45)"
				var name = nameundkoordinaten[0].substr(0, nameundkoordinaten[0].length-1); 		// "Grüneburgpark"
				var beidekoor = nameundkoordinaten[1].substr(0, nameundkoordinaten[1].length-1); 	// "8.34, 3.45"
				var beidekoordinaten = beidekoor.split(","); 										// 0="8.34" / 1=" 3.45"
				var breitengrad = beidekoordinaten[0];												// "8.34"
				var laengengrad = beidekoordinaten[1].substr(1, beidekoordinaten[1].length);		// "3.45"
				var beschreibung = document.getElementById("beschr_" + name).innerHTML;
		
				// Neue Details füllen
				$('#beschreibungAlt').val(beschreibung);
				$('#breitengradAlt').val(breitengrad);
				$('#laengengradAlt').val(laengengrad);
		
				// Neue Felder mit aktuellen Werten füllen
				$('#nameNeu').val(name);
				$('#beschreibungNeu').val(beschreibung);
				$('#breitengradNeu').val(breitengrad);
				$('#laengengradNeu').val(laengengrad);

				// Globale Variablen setzen
				nameAlt = name;
				breitengradAlt = breitengrad;
				laengengradAlt = laengengrad;
				beschreibungAlt = beschreibung;

				// Globale Variable wird auf den aktuellen Index gesetzt
				selectedNameAlt = document.getElementById("selectNameAlt").selectedIndex;
				
				// Alle Marker löschen
				clearMarkers();
				
				// Koordinaten berechnen
				var myLatlng = new google.maps.LatLng(breitengrad, laengengrad);
	
				// Neuen Marker setzen
				marker2 = new google.maps.Marker({
	            	position: myLatlng,
	            	map: map,
	            	title: name
	        	});
    	
				// Die unterschiedlichen Arten erhalten unterschiedliche Marker
				var iconFile = "";
				if (document.getElementById("selectArtAenderung").selectedIndex == 1) 		// Sehenswürdigkeit
	        		iconFile = 'img/sight.png';
				else if (document.getElementById("selectArtAenderung").selectedIndex == 2) 	// Park
	        		iconFile = 'img/park.png';
				else if (document.getElementById("selectArtAenderung").selectedIndex == 3) 	// Museum
	        		iconFile = 'img/museum.png';

	    		// Icon dem Marker zuweisen
		    	marker2.setIcon(iconFile);
	
		    	// Marker dem Markerarray hinzufügen
				markersArray.push(marker2);
	
				// Karte auf neuen Standort zentrieren und zoomen
				map.setCenter(myLatlng);
				map.setZoom(15);
				
				// Dem aktuellen Marker einen Klick-Listener hinzufügen
				google.maps.event.addListener(marker2, "click", function(event) {
					
					// Auswahlfelder abfragen
					var radioNeueintrag = document.getElementById("radioNeueintrag").checked;
					var radioAendern =  document.getElementById("radioAenderung").checked;
					
					// Wenn das Neueintragsformular gewählt ist
					if (radioNeueintrag){
	
						// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
						document.getElementById('breitengrad').value   = event.latLng.lat();
		            	document.getElementById('laengengrad').value  = event.latLng.lng();
	
		            	// Prüfe, ob das Formular vollständig ausgefüllt ist
		            	pruefeFormular('1');
					}
	
					// Wenn das Änderungsformular gewählt ist
					else if (radioAendern){
	
						// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
						document.getElementById('breitengradNeu').value   = event.latLng.lat();
		            	document.getElementById('laengengradNeu').value  = event.latLng.lng();
					}
				});
				
				// Da vorher alle Marker gelöscht wurden, muss der HomeMarker neu gesetzt werden, falls ein Standort gefunden wurde
				if (erfolg){
					setHomeMarker();
				}
				
				// Neue Daten und Alte Details anzeigen
				$('#neueDaten').show();
				$('#alteDetails').show();
				
				// Feld zum Eintragen eines neuen Namens fokussieren
				document.getElementById('nameNeu').focus();
	
				// Wartezeichen verstecken
				$('#waitAlteDetails').hide();
			}
		}
	}

	// FUNKTION ZUM ENTFERNEN ALLER MARKER AUF DER KARTE
	function clearMarkers() {

		// Für alle Marker des Markerarrays
		for (var i = 0; i < markersArray.length; i++ ) {

			// Aktuellen Marker aus der Karte entfernen
			markersArray[i].setMap(null);
		}
	}

	// FUNKTION ZUM ENTFERNEN ALLER MARKER AUF DER KARTE
	function clearPlaces() {

		// Für alle Marker des Markerarrays
		for (var i = 0; i < markersPlacesArray.length; i++ ) {

			// Aktuellen Marker aus der Karte entfernen
			markersPlacesArray[i].setMap(null);
		}
	}

	// FUNKTION ZUM ERSTELLEN DER KARTE
	function initializeMap() {

		// Neuen Geocoder definieren
		geocoder = new google.maps.Geocoder();

		// Wenn der aktuelle Standort abgerufen werden kann
		if (navigator.geolocation) {

			// Bei erfolgreicher Standortbestimmung
			success = function(position) {

				// Globale Variablen zuweisen
				erfolg = 1;
				longitude = position.coords.longitude;
				latitude = position.coords.latitude;

				// Karte erstellen
				createMap();
			};

			// Bei erfolgloser Standortbestimmung
			error = function() {

				// Globale Variable zuweisen
				erfolg = 0;

				// Karte erstellen
				createMap();
			};

			// Aktuellen Standort abrufen
			navigator.geolocation.getCurrentPosition(success, error);
		}

		else {

			// Globale Variable zuweisen
			erfolg = 0;

			// Karte erstellen
			createMap();
		}
	}

	// FUNKTION ZUM ERSTELLEN DER KARTE
	function createMap() {

		// Variable initialisieren
		var mapOptions = [];
		
		// Wenn der Userstandort gefunden worden ist
		if (erfolg){

			// Koordinaten berechnen
			var latLng = new google.maps.LatLng(latitude, longitude);

			// Kartenoptionen definieren
			mapOptions = {
				center : latLng,
				zoom : 8,
				scrollwheel : true,
				mapTypeId : google.maps.MapTypeId.ROADMAP
			};			
		}
		
		// Wenn kein Userstandort gefunden wurde
		else{

			// Koordinaten berechen von Frankfurt
			var latLng = new google.maps.LatLng("50.110922", "8.682127");

			// Kartenoptionen definieren
			mapOptions = {
				center : latLng,
				zoom : 8,
				scrollwheel : true,
				mapTypeId : google.maps.MapTypeId.ROADMAP
			};
		}
		
		// Neue Karte erstellen
		map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		
		// Cursor zum Kreuz ändern
		map.setOptions({draggableCursor:'crosshair'});

		// HomeMarker setzen
		if (erfolg)
			setHomeMarker();
		
		// Der Karte einen Klick-Listener hinzufügen
		google.maps.event.addListener(map, "click", function(event) {
			
			// Auswahlfelder abfragen
			var radioNeueintrag = document.getElementById("radioNeueintrag").checked;
			var radioAendern =  document.getElementById("radioAenderung").checked;
			
			// Wenn Neueintrag gewählt ist
			if (radioNeueintrag){
				
				// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
				document.getElementById('breitengrad').value   = event.latLng.lat();
            	document.getElementById('laengengrad').value  = event.latLng.lng();

            	// Prüfe, ob das Formular vollständig ausgefüllt ist
            	pruefeFormular('1');
			}
			
			else if (radioAendern){

				// Trage die aktuellen Koordinaten in die Felder des Änderungsformulars ein
				document.getElementById('breitengradNeu').value   = event.latLng.lat();
            	document.getElementById('laengengradNeu').value  = event.latLng.lng();
			}
		});
	}

	// FUNKTION ZUM SETZEN DES HOMEMARKERS
	function setHomeMarker(){

		// Koordinaten berechen
		var latLng = new google.maps.LatLng(latitude, longitude);

		// Einen neuen Marker definieren
		var marker = new google.maps.Marker({
			position : latLng,
			map : map,
			draggable : true,
			title: "Dein Standort"
		});

		// Eigenes Marker Bild definieren
		var iconFile = 'img/home.png';
    	marker.setIcon(iconFile);

    	// Marker dem Markerarray hinzufügen
		markersArray.push(marker);		
		
		// Dem Marker einen Klick-Listener hinzufügen
		google.maps.event.addListener(marker, "click", function(event) {
			
			// Auswahlfelder abfragen
			var radioNeueintrag = document.getElementById("radioNeueintrag").checked;
			var radioAendern =  document.getElementById("radioAenderung").checked;
			
			// Wenn Neueintrag gewählt ist
			if (radioNeueintrag){

				// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
				document.getElementById('breitengrad').value   = event.latLng.lat();
            	document.getElementById('laengengrad').value  = event.latLng.lng();

            	// Prüfe, ob das Formular vollständig ausgefüllt ist
            	pruefeFormular('1');
			}
			
			else if (radioAendern){

				// Trage die aktuellen Koordinaten in die Felder des Änderungsformulars ein
				document.getElementById('breitengradNeu').value   = event.latLng.lat();
            	document.getElementById('laengengradNeu').value  = event.latLng.lng();
			}
		});
	}

	// FUNKTION ZUM ANZEIGEN DER ADRESSE, DIE ÜBER DAS SUCHFELD EINGEGEBEN WURDE
	function showAddress() {

		// Variable initialisieren mit dem Inputwert des Feldes
		var address = document.getElementById('address').value;

		geocoder.geocode( { 'address': address}, function(results, status) {

			// Wenn ein gültiger Wert gefunden wurde
		    if (status == google.maps.GeocoderStatus.OK) {
			    
		    	// Alle Marker löschen
				//clearMarkers();
				
				// Da vorher alle Marker gelöscht wurden, neuen HomeMarker setzen
				//if (erfolg){
				//	setHomeMarker();
				//}

				// Karte zentrieren und zoomen
		      	map.setCenter(results[0].geometry.location);
		      	map.setZoom(11);

		      	// Neuen Marker erstellen
		      	var marker3 = new google.maps.Marker({
					position : results[0].geometry.location,
					map : map,
					draggable : false,
					title: address
				});

				// Marker dem Markerarray hinzufügen
				markersArray.push(marker3);

				// Dem Marker einen Klick-Listener hinzufügen
				google.maps.event.addListener(marker3, "click", function(event) {
					
					// Auswahlfelder abfragen
					var radioNeueintrag = document.getElementById("radioNeueintrag").checked;
					var radioAendern =  document.getElementById("radioAenderung").checked;
					
					// Wenn Neueintrag gewählt ist
					if (radioNeueintrag){

						// Trage die aktuellen Koordinaten in die Felder des Neueintragsformulars ein
						document.getElementById('breitengrad').value   = event.latLng.lat();
		            	document.getElementById('laengengrad').value  = event.latLng.lng();

		            	// Prüfe, ob das Formular vollständig ausgefüllt ist
		            	pruefeFormular('1');
					}
					
					else if (radioAendern){

						// Trage die aktuellen Koordinaten in die Felder des Änderungsformulars ein
						document.getElementById('breitengradNeu').value   = event.latLng.lat();
		            	document.getElementById('laengengradNeu').value  = event.latLng.lng();
					}
				});
			}

		    // Wenn kein Ergebnis gefunden wurde
			else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
				alert("Es wurde kein Ergebnis gefunden!");
		    }

		    // Wenn ein anderer Fehler aufgetreten ist
		    else {
				alert("Es ist folgender Fehler aufgetreten:\n" + status);
			}
		});
	}

	// FUNKTION ZUM SETZEN DER KARTENMITTE AUF EIN BUNDESLAND
	function setCenterOnBl(value){

		// Variablen initialisieren
		var zoom = 8;
		var lng = 10;
		var lat = 50;
		
		// Wenn "Bitte wählen" gewählt wurde
		if (value == ""){

			// Kartenmitte auf Deutschland zentrieren
			zoom = 5;
			lng = 10.0634765625;
			lat = 50.875311142200765;
		}

		// Wenn Wert ausgewählt wurde
		else{

			// Switch für die verschiedenen Bundesländer
			switch (value) {
			case "BW": zoom = 7; lng = 9.151611328125; lat = 48.785151998043155; break;
			case "BY": zoom = 7; lng = 11.35986328125; lat = 48.951366470947725; break;
			case "BE": zoom = 10; lng = 13.414306640625; lat = 52.51622086393074; break;
			case "BB": zoom = 7; lng = 13.304443359375; lat = 52.71633093604627; break;
			case "HB": zoom = 10; lng = 8.81103515625; lat = 53.077527569052094; break;
			case "HH": zoom = 8; lng = 9.99481201171875; lat = 53.54520363618777; break;
			case "HE": zoom = 7; lng = 8.690185546875; lat = 50.42951794712287; break;
			case "MV": zoom = 7; lng = 12.72216796875; lat = 53.8460456413833; break;
			case "NI": zoom = 7; lng = 9.239501953125; lat = 53.028000167735165; break;
			case "NW": zoom = 7; lng = 7.503662109375; lat = 51.590722643120145; break;
			case "RP": zoom = 8; lng = 7.239990234375; lat = 50.11705589409776; break;
			case "SL": zoom = 9; lng = 6.9818115234375; lat = 49.403824657885124; break;
			case "SN": zoom = 8; lng = 13.46923828125; lat = 51.086272518565515; break;
			case "ST": zoom = 7; lng = 11.722412109375; lat = 52.11325243469631; break;
			case "SH": zoom = 7; lng = 9.591064453125; lat = 54.316523240258284; break;
			case "TH": zoom = 7; lng = 11.085205078125; lat = 50.97572378679332; break;
			default: zoom = 5; lng = 10.0634765625; lat = 50.875311142200765; break;
			}
		}

		// Koordinaten berechen
		var latLng = new google.maps.LatLng(lat, lng);
		
		// Karte zentrieren und zoomen
		map.setCenter(latLng);
      	map.setZoom(zoom);
	}

	// FUNKTION ZUM ANZEIGEN ALLER PLÄTZE EINER ART IN DER GEWÄHLTEN STADT
	function showPlaces(value){

		// Alle Platzmarker löschen
		//clearPlaces();
		
		// Bundesland abfragen
		var bl = $('#selectBundesland :selected').text();
		
		// Wenn kein Bundesland gewählt ist
		if (bl == "Bitte wählen"){

			// Alle Marker löschen
			clearMarkers();
			
			// Wartezeichen anzeigen
			$('#mapLoad').show();
			
			// Fallunterscheidung anhand der art
			switch (value) {
				case "S": tabelle = "sehensw"; break;
				case "P": tabelle = "parks"; break;
				case "M": tabelle = "museen"; break;
			}
			
			// xmlhttp Objekt erstellen für IE7+, Firefox, Chrome, Opera, Safari
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}

			// xmlhttp Objekt erstellen für IE6, IE5
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}

			// Für das xmlhttp-Objekt eine Aktion definieren wenn es fertig ist
			xmlhttp.onreadystatechange=function(){

				// Wenn der Server mit "200" geantwortet hat und eine Antwort zurückkam
				if (xmlhttp.readyState==4 && xmlhttp.status==200){

					// Variable deklarieren und zuweisen
					text = xmlhttp.responseText;

					showAllPlacesOnMap(text, value);
					// Den Platzhalter für die Orte füllen und anzeigen
		    		//document.getElementById("gefundenePlaetze").innerHTML = text;

		    		// Wartezeichen verstecken
		    		//$('#waitNameAlt').hide();
			    }
				else{

					// Evtl. Fehlermeldung anzeigen
					
					
					// Wartezeichen verstecken
		    		//$('#waitNameAlt').hide();
				}
			};

			// Das xmlhttp-Objekt mit Parametern absenden 
			xmlhttp.open("GET","holeOrte.php?tabelle=" + tabelle + "&action=4",true);
			xmlhttp.send();
		}
	
		// Wenn ein Bundesland gewählt wurde
		else{

			// Wartezeichen anzeigen
			$('#mapLoad').show();

			// Stadt und Bundesland abfragen
			var ort = $('#selectOrtsname :selected').text();
			
			// Bundesland erhalten
			var bundesland = $('#selectBundesland').val();
			
			// Wenn kein Ortsname gewählt wurde
			if (ort == "Bitte wählen"){
				
				// Fallunterscheidung anhand der art
				switch (value) {
					case "S": tabelle = "sehensw"; break;
					case "P": tabelle = "parks"; break;
					case "M": tabelle = "museen"; break;
				}
				
				// xmlhttp Objekt erstellen für IE7+, Firefox, Chrome, Opera, Safari
				if (window.XMLHttpRequest){
					xmlhttp=new XMLHttpRequest();
				}

				// xmlhttp Objekt erstellen für IE6, IE5
				else{
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
	
				// Für das xmlhttp-Objekt eine Aktion definieren wenn es fertig ist
				xmlhttp.onreadystatechange=function(){
	
					// Wenn der Server mit "200" geantwortet hat und eine Antwort zurückkam
					if (xmlhttp.readyState==4 && xmlhttp.status==200){
	
						// Variable deklarieren und zuweisen
						text = xmlhttp.responseText;

						showAllPlacesOnMap(text, value);
						// Den Platzhalter für die Orte füllen und anzeigen
			    		//document.getElementById("gefundenePlaetze").innerHTML = text;
	
			    		// Wartezeichen verstecken
			    		//$('#waitNameAlt').hide();
				    }
					else{
	
						// Evtl. Fehlermeldung anzeigen
						
						
						// Wartezeichen verstecken
			    		//$('#waitNameAlt').hide();
					}
				};
	
				// Das xmlhttp-Objekt mit Parametern absenden 
				xmlhttp.open("GET","holeOrte.php?tabelle=" + tabelle + "&bundesland=" + bundesland + "&action=3",true);
				xmlhttp.send();
			}
			
			// Wenn ein Ortsname gewählt wurde
			else if (ort != "Bitte wählen"){
				name = ort;
				
				// Bundesland erhalten
				bundesland = $('#selectBundesland').val();
				
				// Fallunterscheidung anhand der art
				switch (value) {
					case "S": tabelle = "sehensw"; break;
					case "P": tabelle = "parks"; break;
					case "M": tabelle = "museen"; break;
				}
				
				// xmlhttp Objekt erstellen für IE7+, Firefox, Chrome, Opera, Safari
				if (window.XMLHttpRequest){
					xmlhttp=new XMLHttpRequest();
				}
				
				// xmlhttp Objekt erstellen für IE6, IE5
				else{
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
	
				// Für das xmlhttp-Objekt eine Aktion definieren wenn es fertig ist
				xmlhttp.onreadystatechange=function(){
	
					// Wenn der Server mit "200" geantwortet hat und eine Antwort zurückkam
					if (xmlhttp.readyState==4 && xmlhttp.status==200){
	
						// Variable deklarieren und zuweisen
						text = xmlhttp.responseText;
	
						showAllPlacesOnMap(text, value);
						// Den Platzhalter für die Orte füllen und anzeigen
			    		//document.getElementById("gefundenePlaetze").innerHTML = text;
	
			    		// Wartezeichen verstecken
			    		//$('#waitNameAlt').hide();
				    }
					else{
	
						// Evtl. Fehlermeldung anzeigen
						
						
						// Wartezeichen verstecken
			    		//$('#waitNameAlt').hide();
					}
				};
	
				// Das xmlhttp-Objekt mit Parametern absenden 
				xmlhttp.open("GET","holeOrte.php?tabelle=" + tabelle + "&name=" + name + "&bundesland=" + bundesland + "&action=2",true);
				xmlhttp.send();
			}
		}
	}

	// FUNKTION ZUM ANZEIGEN ALLER PLÄTZE AUF DER KARTE
	function showAllPlacesOnMap(text, value){
		
		// Alle Platzmarker löschen
		clearPlaces();
		
		// Wenn Werte gefunden wurden
		if (text != "Nichts"){

			// Wenn eine Art gewählt wurde
			if (value != ""){
			
				// String teilen
				var allePlaetze = text.split("||");
				
				// Vom letzten die zwei letzen Zeichen wegschneiden
				var laenge = allePlaetze.length;
	
				// Die unterschiedlichen Arten erhalten unterschiedliche Marker
				var iconFile = "";
				if (value == "S") 					// Sehenswürdigkeit
	        		iconFile = 'img/sight.png';
				else if (value == "P") 				// Park
	        		iconFile = 'img/park.png';
				else if (value == "M") 				// Museum
	        		iconFile = 'img/museum.png';
	
				// Schleifendurchlauf für alle Gefundenen Orte
				for(var i = 0; i < (laenge-1); i++){
	
					// aktuellen Platz zerlegen
					var aktPlatz = allePlaetze[i].split("|");	// 0=Palmengarten / 1=50.5164654 / 2=8.459848945
	
					// Koordinaten berechnen
					var myLatlng = new google.maps.LatLng(aktPlatz[1], aktPlatz[2]);
		
					// Neuen Marker setzen
					var markerPlatz = new google.maps.Marker({
		            	position: myLatlng,
		            	map: map,
		            	title: aktPlatz[0]
		        	});
		        	
		    		// Icon dem Marker zuweisen
			    	markerPlatz.setIcon(iconFile);
		
			    	// Marker dem Markerarray hinzufügen
					markersPlacesArray.push(markerPlatz);
				}
			}
		}
		
		// Wartezeichen verstecken
		$('#mapLoad').hide();
	}

	// Funktionsaufruf zum initialisieren der Karte
	initializeMap();

	$(function(){
		$('#myModal').modal({show: false});
	});
	
</script>

<?php //include 'footer.php';?>