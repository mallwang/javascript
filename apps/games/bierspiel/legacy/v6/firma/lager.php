<?php $title="Lager"; include '../sessionpruefung.php'; include '../geheim.php';
// Aktuelle firma_id
$firma_id = $_SESSION['firmaId'];
// Abfrage des kompletten Lagerbestandes
$ergebnis = anfrage("SELECT bier, quellwasser, hopfen, gerste, malz FROM lager WHERE firma_id = '$firma_id'");
?>
<script type="text/javascript">
	var bier = parseInt("<?php echo $ergebnis[0];?>");
	var quellwasser = parseInt("<?php echo $ergebnis[1];?>");
	var hopfen = parseInt("<?php echo $ergebnis[2];?>");
	var gerste = parseInt("<?php echo $ergebnis[3];?>");
	var malz = parseInt("<?php echo $ergebnis[4];?>");
	/********************************************
	/************** MENGENPRÜFUNG ***************
	********************************************/
	function pruefeMenge(id){
		// Bier
		if (id == "0"){
			// Variable initailisieren
			var inputBier = $('#bier').val();
			// Wenn mindestens ein Zeichen eingegeben wurde
			if (inputBier.length != 0){
				// Prüfen ob Zahlen im Bereich von null bis akt Lagerbestand eingegeben wurden
				if (inputBier>0 && inputBier <= bier){
					// Feld grün hinterlegen
					$("#bier").css("border-color","green");
					$("#bier").css("background-color","palegreen");
					// Buttons aktivieren
					$('#bierBtn0').removeAttr('disabled');
					$('#bierBtn1').removeAttr('disabled');
					$('#bierBtn2').removeAttr('disabled');
					// Wert aus Inputfeldern abfragen
					var inputBierMarktPreis = $('#bierMarkt').val();
					var inputBierPartnerPreis = $('#bierPartnerPreis').val();
					// Wenn Marktinputfeld einen Preis enthält
					if (inputBierMarktPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputBierMarktPreis > 0){
							// Button wieder aktivieren
							$('#bierMarktBtnVk').removeAttr('disabled');
						}
					}
					// Wenn Inputfeld einen Preis enthält
					if (inputBierPartnerPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputBierPartnerPreis > 0){
							// Button wieder aktivieren
							$('#bierPartnerBtnVk').removeAttr('disabled');
						}
					}
				}
				// Wenn Zahl ausser dem Bereich ist
				else{
					// Feld rot hinterlegen
					$("#bier").css("border-color","red");
					$("#bier").css("background-color","salmon");
					// Buttons deaktivieren
					$('#bierBtn0').attr('disabled', 'disabled');
					$('#bierBtn1').attr('disabled', 'disabled');
					$('#bierBtn2').attr('disabled', 'disabled');
					// Verkaufenbutton deaktivieren
					$('#bierMarktBtnVk').attr('disabled', 'disabled');
					$('#bierPartnerBtnVk').attr('disabled', 'disabled');
				}
			}
			// Wenn kein Zeichen eingegeben wurde
			else{
				$("#bier").css("border-color","rgb(204,204,204)");
				$("#bier").css("background-color","white");
				// Buttons deaktivieren
				$('#bierBtn0').attr('disabled', 'disabled');
				$('#bierBtn1').attr('disabled', 'disabled');
				$('#bierBtn2').attr('disabled', 'disabled');
				// TODO: Problem mit keyup tabulator
			}
		}
		// Quellwasser
		if (id == "1"){
			// Variable initailisieren
			var inputQuellwasser = $('#quellwasser').val();
			// Wenn mindestens ein Zeichen eingegeben wurde
			if (inputQuellwasser.length != 0){
				// Prüfen ob Zahlen im Bereich von null bis akt Lagerbestand eingegeben wurden
				if (inputQuellwasser>0 && inputQuellwasser <= quellwasser){
					// Feld grün hinterlegen
					$("#quellwasser").css("border-color","green");
					$("#quellwasser").css("background-color","palegreen");
					// Buttons aktivieren
					$('#quellwasserBtn0').removeAttr('disabled');
					$('#quellwasserBtn1').removeAttr('disabled');
					$('#quellwasserBtn2').removeAttr('disabled');
					// Wert aus Inputfeldern abfragen
					var inputQuellwasserMarktPreis = $('#quellwasserMarkt').val();
					var inputQuellwasserPartnerPreis = $('#quellwasserPartnerPreis').val();
					// Wenn Marktinputfeld einen Preis enthält
					if (inputQuellwasserMarktPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputQuellwasserMarktPreis > 0){
							// Button wieder aktivieren
							$('#quellwasserMarktBtnVk').removeAttr('disabled');
						}
					}
					// Wenn Inputfeld einen Preis enthält
					if (inputQuellwasserPartnerPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputQuellwasserPartnerPreis > 0){
							// Button wieder aktivieren
							$('#quellwasserPartnerBtnVk').removeAttr('disabled');
						}
					}
				}
				// Wenn Zahl ausser dem Bereich ist
				else{
					// Feld rot hinterlegen
					$("#quellwasser").css("border-color","red");
					$("#quellwasser").css("background-color","salmon");
					// Buttons deaktivieren
					$('#quellwasserBtn0').attr('disabled', 'disabled');
					$('#quellwasserBtn1').attr('disabled', 'disabled');
					$('#quellwasserBtn2').attr('disabled', 'disabled');
					// Verkaufenbutton deaktivieren
					$('#quellwasserMarktBtnVk').attr('disabled', 'disabled');
					$('#quellwasserPartnerBtnVk').attr('disabled', 'disabled');
				}
			}
			// Wenn kein Zeichen eingegeben wurde
			else{
				$("#quellwasser").css("border-color","rgb(204,204,204)");
				$("#quellwasser").css("background-color","white");
				// Buttons deaktivieren
				$('#quellwasserBtn0').attr('disabled', 'disabled');
				$('#quellwasserBtn1').attr('disabled', 'disabled');
				$('#quellwasserBtn2').attr('disabled', 'disabled');
				// TODO: Problem mit keyup tabulator
			}
		}
		// Hopfen
		if (id == "2"){
			// Variable initailisieren
			var inputHopfen = $('#hopfen').val();
			// Wenn mindestens ein Zeichen eingegeben wurde
			if (inputHopfen.length != 0){
				// Prüfen ob Zahlen im Bereich von null bis akt Lagerbestand eingegeben wurden
				if (inputHopfen>0 && inputHopfen <= hopfen){
					// Feld grün hinterlegen
					$("#hopfen").css("border-color","green");
					$("#hopfen").css("background-color","palegreen");
					// Buttons aktivieren
					$('#hopfenBtn0').removeAttr('disabled');
					$('#hopfenBtn1').removeAttr('disabled');
					$('#hopfenBtn2').removeAttr('disabled');
					// Wert aus Inputfeldern abfragen
					var inputHopfenMarktPreis = $('#hopfenMarkt').val();
					var inputHopfenPartnerPreis = $('#hopfenPartnerPreis').val();
					// Wenn Marktinputfeld einen Preis enthält
					if (inputHopfenMarktPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputHopfenMarktPreis > 0){
							// Button wieder aktivieren
							$('#hopfenrMarktBtnVk').removeAttr('disabled');
						}
					}
					// Wenn Inputfeld einen Preis enthält
					if (inputHopfenPartnerPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputHopfenPartnerPreis > 0){
							// Button wieder aktivieren
							$('#hopfenPartnerBtnVk').removeAttr('disabled');
						}
					}
				}
				// Wenn Zahl ausser dem Bereich ist
				else{
					// Feld rot hinterlegen
					$("#hopfen").css("border-color","red");
					$("#hopfen").css("background-color","salmon");
					// Buttons deaktivieren
					$('#hopfenBtn0').attr('disabled', 'disabled');
					$('#hopfenBtn1').attr('disabled', 'disabled');
					$('#hopfenBtn2').attr('disabled', 'disabled');
					// Verkaufenbutton deaktivieren
					$('#hopfenMarktBtnVk').attr('disabled', 'disabled');
					$('#hopfenPartnerBtnVk').attr('disabled', 'disabled');
				}
			}
			// Wenn kein Zeichen eingegeben wurde
			else{
				$("#hopfen").css("border-color","rgb(204,204,204)");
				$("#hopfen").css("background-color","white");
				// Buttons deaktivieren
				$('#hopfenBtn0').attr('disabled', 'disabled');
				$('#hopfenBtn1').attr('disabled', 'disabled');
				$('#hopfenBtn2').attr('disabled', 'disabled');
				// TODO: Problem mit keyup tabulator
			}
		}
		// Gerste
		if (id == "3"){
			// Variable initailisieren
			var inputGerste = $('#gerste').val();
			// Wenn mindestens ein Zeichen eingegeben wurde
			if (inputGerste.length != 0){
				// Prüfen ob Zahlen im Bereich von null bis akt Lagerbestand eingegeben wurden
				if (inputGerste>0 && inputGerste <= gerste){
					// Feld grün hinterlegen
					$("#gerste").css("border-color","green");
					$("#gerste").css("background-color","palegreen");
					// Buttons aktivieren
					$('#gersteBtn0').removeAttr('disabled');
					$('#gersteBtn1').removeAttr('disabled');
					$('#gersteBtn2').removeAttr('disabled');
					// Wert aus Inputfeldern abfragen
					var inputGersteMarktPreis = $('#gersteMarkt').val();
					var inputGerstePartnerPreis = $('#gerstePartnerPreis').val();
					// Wenn Marktinputfeld einen Preis enthält
					if (inputGersteMarktPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputGersteMarktPreis > 0){
							// Button wieder aktivieren
							$('#gersteMarktBtnVk').removeAttr('disabled');
						}
					}
					// Wenn Inputfeld einen Preis enthält
					if (inputGerstePartnerPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputGerstePartnerPreis > 0){
							// Button wieder aktivieren
							$('#gerstePartnerBtnVk').removeAttr('disabled');
						}
					}
				}
				// Wenn Zahl ausser dem Bereich ist
				else{
					// Feld rot hinterlegen
					$("#gerste").css("border-color","red");
					$("#gerste").css("background-color","salmon");
					// Buttons deaktivieren
					$('#gersteBtn0').attr('disabled', 'disabled');
					$('#gersteBtn1').attr('disabled', 'disabled');
					$('#gersteBtn2').attr('disabled', 'disabled');
					// Verkaufenbutton deaktivieren
					$('#gersteMarktBtnVk').attr('disabled', 'disabled');
					$('#gerstePartnerBtnVk').attr('disabled', 'disabled');
				}
			}
			// Wenn kein Zeichen eingegeben wurde
			else{
				$("#gerste").css("border-color","rgb(204,204,204)");
				$("#gerste").css("background-color","white");
				// Buttons deaktivieren
				$('#gersteBtn0').attr('disabled', 'disabled');
				$('#gersteBtn1').attr('disabled', 'disabled');
				$('#gersteBtn2').attr('disabled', 'disabled');
				// TODO: Problem mit keyup tabulator
			}
		}
		// Malz
		if (id == "4"){
			// Variable initailisieren
			var inputMalz = $('#malz').val();
			// Wenn mindestens ein Zeichen eingegeben wurde
			if (inputMalz.length != 0){
				// Prüfen ob Zahlen im Bereich von null bis akt Lagerbestand eingegeben wurden
				if (inputMalz>0 && inputMalz <= malz){
					// Feld grün hinterlegen
					$("#malz").css("border-color","green");
					$("#malz").css("background-color","palegreen");
					// Buttons aktivieren
					$('#malzBtn0').removeAttr('disabled');
					$('#malzBtn1').removeAttr('disabled');
					$('#malzBtn2').removeAttr('disabled');
					// Wert aus Inputfeldern abfragen
					var inputMalzMarktPreis = $('#malzMarkt').val();
					var inputMalzPartnerPreis = $('#malzPartnerPreis').val();
					// Wenn Marktinputfeld einen Preis enthält
					if (inputMalzMarktPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputMalzMarktPreis > 0){
							// Button wieder aktivieren
							$('#malzMarktBtnVk').removeAttr('disabled');
						}
					}
					// Wenn Inputfeld einen Preis enthält
					if (inputMalzPartnerPreis.length > 0){
						// Prüfen ob Preis in Ordnung ist
						if (inputMalzPartnerPreis > 0){
							// Button wieder aktivieren
							$('#malzPartnerBtnVk').removeAttr('disabled');
						}
					}
				}
				// Wenn Zahl ausser dem Bereich ist
				else{
					// Feld rot hinterlegen
					$("#malz").css("border-color","red");
					$("#malz").css("background-color","salmon");
					// Buttons deaktivieren
					$('#malzBtn0').attr('disabled', 'disabled');
					$('#malzBtn1').attr('disabled', 'disabled');
					$('#malzBtn2').attr('disabled', 'disabled');
					// Verkaufenbutton deaktivieren
					$('#malzMarktBtnVk').attr('disabled', 'disabled');
					$('#malzPartnerBtnVk').attr('disabled', 'disabled');
				}
			}
			// Wenn kein Zeichen eingegeben wurde
			else{
				$("#malz").css("border-color","rgb(204,204,204)");
				$("#malz").css("background-color","white");
				// Buttons deaktivieren
				$('#malzBtn0').attr('disabled', 'disabled');
				$('#malzBtn1').attr('disabled', 'disabled');
				$('#malzBtn2').attr('disabled', 'disabled');
				// TODO: Problem mit keyup tabulator
			}
		}
	}
	
	/********************************************
	/*************** PREISPRÜFUNG ***************
	********************************************/
	function pruefePreis(id, wert){
		// id zuweisen
		var inputId = id.getAttribute("id");
		// Preis zuweisen
		var inputWert = wert;
		// ID des Buttons festlegen
		var btnId = inputId + "BtnVk";
		// Prüfen, ob Preis positiv
		if (wert > 0){
			// Feld grün hinterlegen
			document.getElementById(inputId).style.borderColor = "green";
			document.getElementById(inputId).style.backgroundColor = "palegreen";
			// Verkaufenfeld aktivieren
			document.getElementById(btnId).removeAttribute('disabled');
		}
		// Wenn Preis nicht positiv oder eine Zahl
		else{
			// Feld rot hinterlegen
			document.getElementById(inputId).style.borderColor = "red";
			document.getElementById(inputId).style.backgroundColor = "salmon";
			// Verkaufenfeld deaktivieren
			document.getElementById(btnId).setAttribute('disabled', 'disabled');
		}
	}
	
	/*********************************************
	/************* WARE VERKAUFEN ****************
	*********************************************/
	function verkaufenMarkt(element){
		var id = element.getAttribute("id");
		// Menge abfragen
		var wert = id.substring(0, id.length - 10); // z.B. quellwasserMarktBtnVk -> quellwasser
		var menge = document.getElementById(wert).value;
		var ware = wert.substr(0, 1).toUpperCase() + wert.substr(1);
		// Einzelpreis abfragen
		var einzelpreisId = wert + "Markt"; //z.B. quellwasserMarktPreis
		var ep = document.getElementById(einzelpreisId).value;
		// Maßeinheit
		var masseinheit;
		if (ware == "Bier" || ware=="Quellwasser"){
			masseinheit = "ltr";
		}
		else if (ware == "Hopfen" || ware == "Gerste" || ware == "Malz"){
			masseinheit = "kg";
		}
		// Maximalpreis
		var maximalpreis = menge * ep;
		// Confirm
		var anbieten = confirm(unescape("M%F6chtest du wirklich " + menge + " " + masseinheit + " " + ware + " am Markt f%FCr einen Einzelpreis von je " + ep + "%u20AC anbieten?"
				+ "%0AMaximaler Umsatz: " + maximalpreis + "%u20AC"));
		if (anbieten){
			// LadezeichenID
			//var ladezeichenId = wert + "WaitVkMarkt";
			// Ladezeichen anzeigen
			//document.getElementById(ladezeichenId).removeAttribute('style');
			// Ladeanimation
	        $("#warten").show();
	     	// Aktualisieren des Lagerbestandes
			var neuerLagerbestand;
			if (ware == "Bier"){
				alterLagerbestand = bier;
				neuerLagerbestand = bier-menge;
				bier = neuerLagerbestand;
			}
			else if (ware == "Quellwasser"){
				alterLagerbestand = quellwasser;
				neuerLagerbestand = quellwasser-menge;
				quellwasser = neuerLagerbestand;
			}
			else if (ware == "Hopfen"){
				alterLagerbestand = hopfen;
				neuerLagerbestand = hopfen-menge;
				hopfen = neuerLagerbestand;
			}
			else if (ware == "Gerste"){
				alterLagerbestand = gerste;
				neuerLagerbestand = gerste-menge;
				gerste = neuerLagerbestand;
			}
			else if (ware == "Malz"){
				alterLagerbestand = malz;
				neuerLagerbestand = malz-menge;
				malz = neuerLagerbestand;
			}
	        var art = "verkaufMarkt";
			// Ajax aufruf
	        $.ajax({
				type: "POST",
				url: "firma/lagerupdate.php",
				cache: false,
				data: { ware: wert, alterLagerbestand: alterLagerbestand, neuerLagerbestand: neuerLagerbestand, menge: menge, einzelpreis: ep, art: art},
				success: function(data){
					// Ladeanimation verstecken
					$("#warten").hide();
                    if(data=='bestandNichtAktuell'){
                    	$("#alertFehler").html("<strong>Fehler!</strong> Der Lagerbestand ist nicht aktuell!<br>Bitte aktualisiere die Seite.");
                        $("#alertFehler").show();
                    }
                    else if(data=='ok'){
                        // Neuer Lagerbestand
						document.getElementById(wert + "Anzahl").innerHTML = neuerLagerbestand + " " + masseinheit;
						// Felder leeren
						document.getElementById(wert).value = "";
						document.getElementById(wert + "Markt").value = "";
						// Felder Rahmen zurücksetzen
						document.getElementById(wert + "Markt").style.borderColor = "rgb(204,204,204)";
						document.getElementById(wert + "Markt").style.backgroundColor = "white";
						document.getElementById(wert).style.borderColor = "rgb(204,204,204)";
							document.getElementById(wert).style.backgroundColor = "white";
						// Buttons deaktivieren
						document.getElementById(wert + "MarktBtnVk").setAttribute('disabled', 'disabled');
						document.getElementById(wert + "Btn0").setAttribute('disabled', 'disabled');
						document.getElementById(wert + "Btn1").setAttribute('disabled', 'disabled');
						document.getElementById(wert + "Btn2").setAttribute('disabled', 'disabled');
						// Form verstecken
						document.getElementById(wert + "MarktForm").setAttribute('style', 'display: none;');
						// Meldung
                    	$("#alertErfolg").html("<strong>Erfolg!</strong> Die Ware wird jetzt am Markt angeboten!");
                        $("#alertErfolg").show();
                    }
                    else {
                    	$("#alertFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
                    			"Fehler: " + data);
                        $("#alertFehler").show();
                    }
				}
            });
            return false;
		}
		else{
			alert("abbruch");
		}
	}
		
	/*********************************************
	/************* WARE VERNICHTEN ***************
	*********************************************/
	function vernichten(element){
		var id = element.getAttribute("id");
		// Wert abfragen
		var wert = id.substring(0, id.length - 4);
		var menge = document.getElementById(wert).value;
		var ware = wert.substr(0, 1).toUpperCase() + wert.substr(1);
		var masseinheit;
		if (ware == "Bier" || ware=="Quellwasser"){
			masseinheit = "ltr";
		}
		else if (ware == "Hopfen" || ware == "Gerste" || ware == "Malz"){
			masseinheit = "kg";
		}
		var eingabe = confirm(unescape("M%F6chten Sie wirklich " + menge + " " + masseinheit + " " + ware + " vernichten?"));
		// Wenn Vernichtung akzeptiert wurde
		if(eingabe){
			// Aktualisieren des Lagerbestandes
			var neuerLagerbestand;
			if (ware == "Bier"){
				alterLagerbestand = bier;
				neuerLagerbestand = bier-menge;
				bier = neuerLagerbestand;
			}
			else if (ware == "Quellwasser"){
				alterLagerbestand = quellwasser;
				neuerLagerbestand = quellwasser-menge;
				quellwasser = neuerLagerbestand;
			}
			else if (ware == "Hopfen"){
				alterLagerbestand = hopfen;
				neuerLagerbestand = hopfen-menge;
				hopfen = neuerLagerbestand;
			}
			else if (ware == "Gerste"){
				alterLagerbestand = gerste;
				neuerLagerbestand = gerste-menge;
				gerste = neuerLagerbestand;
			}
			else if (ware == "Malz"){
				alterLagerbestand = malz;
				neuerLagerbestand = malz-menge;
				malz = neuerLagerbestand;
			}
			// Ladeanimation
	        $("#warten").show();
			var art = "lagerbestandUpdate";
			// Ajax aufruf
	            $.ajax({
					type: "POST",
					url: "firma/lagerupdate.php",
					cache: false,
					data: { ware: wert, alterLagerbestand: alterLagerbestand, neuerLagerbestand: neuerLagerbestand, art: art},
					success: function(data){
						$("#warten").hide();
	                    if(data=='bestandNichtAktuell'){
	                    	$("#alertFehler").html("<strong>Fehler!</strong> Der Lagerbestand ist nicht aktuell!<br>Bitte aktualisiere die Seite.");
	                        $("#alertFehler").show();
	                    }
	                    else if(data=='ok'){
							document.getElementById(wert + "Anzahl").innerHTML = neuerLagerbestand + " " + masseinheit;
							// Feld leeren
							document.getElementById(wert).value = "";
							// Feld Rahmen zurücksetzen
							document.getElementById(wert).style.borderColor = "rgb(204,204,204)";
							document.getElementById(wert).style.backgroundColor = "white";
							// Buttons deaktivieren
							document.getElementById(wert + "Btn0").setAttribute('disabled', 'disabled');
							document.getElementById(wert + "Btn1").setAttribute('disabled', 'disabled');
							document.getElementById(wert + "Btn2").setAttribute('disabled', 'disabled');
	                    	$("#alertErfolg").html("<strong>Erfolg!</strong> Die Ware wurde vernichtet");
	                        $("#alertErfolg").show();
	                    }
	                    else {
	                    	$("#alertFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
	                    			"Fehler: " + data);
	                        $("#alertFehler").show();
	                    }
					}
	            });
	            return false;
		}
		// Wenn nicht aktzeptiert
		else{
			// nichts machen
		}
	}
	
	/*********************************************
	/**************** BEI KLICKS *****************
	*********************************************/
	// Bier - verkaufen
	function bierMarkt() {
		// Andere Felder einfahren
		$('#bierPartnerForm').hide();
		// Feld kleiner machen
		$("#lager").css("width","550");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#bierMarktForm').show();		
	}
	function bierPartner() {
		// Andere Felder einfahren
		$('#bierMarktForm').hide();
		// Feld größer machen
		$("#lager").css("width","620");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#bierPartnerForm').show();
	}
	
	// Quellwasser - verkaufen
	function quellwasserMarkt() {
		// Andere Felder einfahren
		$('#quellwasserPartnerForm').hide();
		// Feld kleiner machen
		$("#lager").css("width","550");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#quellwasserMarktForm').show();
	}
	function quellwasserPartner() {
		// Andere Felder einfahren
		$('#quellwasserMarktForm').hide();
		// Feld größer machen
		$("#lager").css("width","620");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#quellwasserPartnerForm').show();
	}
	
	// Hopfen - verkaufen
	function hopfenMarkt() {
		// Andere Felder einfahren
		$('#hopfenPartnerForm').hide();
		// Feld kleiner machen
		$("#lager").css("width","550");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#hopfenMarktForm').show();
	}
	function hopfenPartner() {
		// Andere Felder einfahren
		$('#hopfenMarktForm').hide();
		// Feld größer machen
		$("#lager").css("width","620");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#hopfenPartnerForm').show();
	}
	
	// Gerste - verkaufen
	function gersteMarkt() {
		// Andere Felder einfahren
		$('#gerstePartnerForm').hide();
		// Feld kleiner machen
		$("#lager").css("width","550");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#gersteMarktForm').show();
	}
	function gerstePartner() {
		// Andere Felder einfahren
		$('#gersteMarktForm').hide();
		// Feld größer machen
		$("#lager").css("width","620");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#gerstePartnerForm').show();
	}
	
	// Malz - verkaufen
	function malzMarkt() {
		// Andere Felder einfahren
		$('#malzPartnerForm').hide();
		// Feld kleiner machen
		$("#lager").css("width","550");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#malzMarktForm').show();
	}
	function malzPartner() {
		// Andere Felder einfahren
		$('#malzMarktForm').hide();
		// Feld größer machen
		$("#lager").css("width","620");
		// Preiseingabefeld und Bestätigenbutton ausfahren
		$('#malzPartnerForm').show();
	}
</script>
<!-- Überschrift -->
<div align="center"><h2>Ihr Lagerbestand:</h2>
<br>

<br>
</div>
<!-- Tabelle -->
<div id="lager" class="lager" align="center">
<img style="display: none;" id="warten" src="/img/loading.gif"/>
<div id="alertFehler" style="display: none;" class="alert alert-error span4"></div>
<div id="alertErfolg" style="display: none;" class="alert alert-success span4"></div>
	<table class='table table-condensed'>
		<thead>
			<tr>
				<th>Art</th>
				<th>Menge</th>
				<th>Aktion</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Bier</td>
				<td id="bierAnzahl" class="textRight"><?php echo $ergebnis[0];?> ltr </td>
				<td>
					<div class='input-append offset0'>
						<input style='height: 20px;' class='span1-2' id='bier' type='text' placeholder="Menge" onkeyup="pruefeMenge('0')">
						<!-- Aktionsbuttons -->
						<button onclick="bierMarkt()" disabled="disabled" id="bierBtn0" title="Am Markt verkaufen" class='btn' type='button'><i class="icon-globe"></i></button>
						<button onclick="bierPartner()" disabled="disabled" id="bierBtn1" title="An Vertragspartner verkaufen" class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick="vernichten(this)" disabled="disabled" id="bierBtn2" title="Ware vernichten" class='btn' type='button'><i class='icon-trash'></i></button>
					</div>
				</td>
			</tr>
			<tr>
				<td>Quellwasser</td>
				<td id="quellwasserAnzahl" class="textRight"><?php echo $ergebnis[1];?> ltr </td>
				<td>
					<div class='input-append offset0'>
						<input style='height: 20px;' class='span1-2' id='quellwasser' type='text' placeholder="Menge" onkeyup="pruefeMenge('1')">
						<!-- Aktionsbuttons -->
						<button onclick="quellwasserMarkt()" disabled="disabled" id="quellwasserBtn0" title="Am Markt verkaufen" class='btn' type='button'><i class="icon-globe"></i></button>
						<button onclick="quellwasserPartner()" disabled="disabled" id="quellwasserBtn1" title="An Vertragspartner verkaufen" class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick="vernichten(this)" disabled="disabled" id="quellwasserBtn2" title="Ware vernichten" class='btn' type='button'><i class='icon-trash'></i></button>
						<!-- Preis & Verkaufenbutton -->
						<div id="quellwasserMarktForm" style="display: none;">
							<input style='height: 20px;' class='span112' id='quellwasserMarkt' type='text' placeholder="Einzelpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<button onclick="verkaufenMarkt(this)" disabled="disabled" id="quellwasserMarktBtnVk" title="Am Markt verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
						<!-- Preis & Verkaufenbutton Partner -->
						<div id="quellwasserPartnerForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='quellwasserPartnerPreis' type='text' placeholder="Gesamtpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<input style='height: 20px;' class='span2' id='quellwasserPartnerName' type='text' placeholder="Vertragspartner" onkeyup="pruefeName('1')">
							<button disabled="disabled" id="quellwasserPartnerBtnVk" title="An Vertragspartner verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>Hopfen</td>
				<td id="hopfenAnzahl" class="textRight"><?php echo $ergebnis[2];?> kg </td>
				<td>
					<div class='input-append offset0'>
						<input style='height: 20px;' class='span1-2' id='hopfen' type='text' placeholder="Menge" onkeyup="pruefeMenge('2')">
						<!-- Aktionsbuttons -->
						<button onclick="hopfenMarkt()" disabled="disabled" id="hopfenBtn0" title="Am Markt verkaufen" class='btn' type='button'><i class="icon-globe"></i></button>
						<button onclick="hopfenPartner()" disabled="disabled" id="hopfenBtn1" title="An Vertragspartner verkaufen" class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick="vernichten(this)" disabled="disabled" id="hopfenBtn2" title="Ware vernichten" class='btn' type='button'><i class='icon-trash'></i></button>
						<!-- Preis & Verkaufenbutton -->
						<div id="hopfenMarktForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='hopfenMarkt' type='text' placeholder="Einzelpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<button onclick="verkaufenMarkt(this)" disabled="disabled" id="hopfenMarktBtnVk" title="Am Markt verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
						<!-- Preis & Verkaufenbutton Partner -->
						<div id="hopfenPartnerForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='hopfenPartnerPreis' type='text' placeholder="Gesamtpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<input style='height: 20px;' class='span2' id='hopfenPartnerName' type='text' placeholder="Vertragspartner" onkeyup="pruefeName('2')">
							<button disabled="disabled" id="hopfenPartnerBtnVk" title="An Vertragspartner verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>Gerste</td>
				<td id="gersteAnzahl" class="textRight"><?php echo $ergebnis[3];?> kg </td>
				<td>
					<div class='input-append offset0'>
						<input style='height: 20px;' class='span1-2' id='gerste' type='text' placeholder="Menge" onkeyup="pruefeMenge('3')">
						<!-- Aktionsbuttons -->
						<button onclick="gersteMarkt()" disabled="disabled" id="gersteBtn0" title="Am Markt verkaufen" class='btn' type='button'><i class="icon-globe"></i></button>
						<button onclick="gerstePartner()" disabled="disabled" id="gersteBtn1" title="An Vertragspartner verkaufen" class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick="vernichten(this)" disabled="disabled" id="gersteBtn2" title="Ware vernichten" class='btn' type='button'><i class='icon-trash'></i></button>
						<!-- Preis & Verkaufenbutton -->
						<div id="gersteMarktForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='gersteMarkt' type='text' placeholder="Einzelpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<button onclick="verkaufenMarkt(this)" disabled="disabled" id="gersteMarktBtnVk" title="Am Markt verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
						<!-- Preis & Verkaufenbutton Partner -->
						<div id="gerstePartnerForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='gerstePartnerPreis' type='text' placeholder="Gesamtpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<input style='height: 20px;' class='span2' id='gerstePartnerName' type='text' placeholder="Vertragspartner" onkeyup="pruefeName('3')">
							<button disabled="disabled" id="gerstePartnerBtnVk" title="An Vertragspartner verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>Malz</td>
				<td id="malzAnzahl" class="textRight"><?php echo $ergebnis[4];?> kg</td>
				<td>
					<div class='input-append offset0'>
						<input style='height: 20px;' class='span1-2' id='malz' type='text' placeholder="Menge" onkeyup="pruefeMenge('4')">
						<!-- Aktionsbuttons -->
						<button onclick="malzMarkt()" disabled="disabled" id="malzBtn0" title="Am Markt verkaufen" class='btn' type='button'><i class="icon-globe"></i></button>
						<button onclick="malzPartner()" disabled="disabled" id="malzBtn1" title="An Vertragspartner verkaufen" class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick="vernichten(this)" disabled="disabled" id="malzBtn2" title="Ware vernichten" class='btn' type='button'><i class='icon-trash'></i></button>
						<!-- Preis & Verkaufenbutton Markt -->
						<div id="malzMarktForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='malzMarkt' type='text' placeholder="Einzelpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<button onclick="verkaufenMarkt(this)" disabled="disabled" id="malzMarktBtnVk" title="Am Markt verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
						<!-- Preis & Verkaufenbutton Partner -->
						<div id="malzPartnerForm" style="display: none;">
							<input style='height: 20px;' class='span1-2' id='malzPartnerPreis' type='text' placeholder="Gesamtpreis" onkeyup="pruefePreis(this, this.value)">
							<span class="add-on"> &euro;</span>
							<input style='height: 20px;' class='span2' id='malzPartnerName' type='text' placeholder="Vertragspartner" onkeyup="pruefeName('4')">
							<button disabled="disabled" id="malzPartnerBtnVk" title="An Vertragspartner verkaufen" class='btn' type='button'>verkaufen</button>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php include '../footer.php';?>