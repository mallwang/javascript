<?php $title="Angebote"; include '../sessionpruefung.php'; include '../geheim.php';
// Aktuelle Preise abfragen
$preisanfrage = mysql_query("SELECT * FROM preise");
$preis = mysql_fetch_array($preisanfrage, MYSQL_NUM);
?>
<script type="text/javascript">
	// Globale Variablen setzen
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
	// Funktion zum Setzen der Getränkeart
	function setGetraenk(){
		// Meldungen verstecken
		$('#alertFehler').hide();
		$('#alertErfolg').hide();
		// Selektiertes Element holen
		gattung = $('#inputArt').val();
		// Auswahl zurücksetzen
		$('#bier').hide();
		$('#spirituosen').hide();
		$('#biermisch').hide();
		$('#alkoholfrei').hide();
		$('#saefte').hide();
		$('#cocktails').hide();
		$('#weine').hide();
		// AnzahlAuswahl zurücksetzen
		$('#bierAnzahlAuswahl').hide();
		$('#spirituosenAnzahlAuswahl').hide();
		$('#biermischAnzahlAuswahl').hide();
		$('#alkoholfreiAnzahlAuswahl').hide();
		$('#saefteAnzahlAuswahl').hide();
		$('#cocktailsAnzahlAuswahl').hide();
		$('#weineAnzahlAuswahl').hide();
		// Wenn Art gewählt
		if (gattung == 1){
			$('#bier').show();
			artName = "v";
		}
		else if (gattung == 2){
			$('#spirituosen').show();
			artName = "p";
		}
		else if (gattung == 3){
			$('#biermisch').show();
			artName = "f";
		}
		else if (gattung == 4){
			$('#alkoholfrei').show();
			artName = "l";
		}
		else if (gattung == 5){
			$('#saefte').show();
			artName = "t";
		}
		else if (gattung == 6){
			$('#cocktails').show();
			artName = "s";
		}
		else if (gattung == 7){
			$('#weine').show();
			artName = "s";
		}
		checkForm();
	}

	// Prüft ob alle Felder ausgefüllt sind
	function checkForm(){
		// Meldungen verstecken
		$('#alertFehler').hide();
		$('#alertErfolg').hide();
		// Variablen initialisieren
		art = 0;
		// Art abfragen
		gattung = $('#inputArt').val();
		// Wenn art gewählt
		if (gattung >= 1 && gattung <= 7){
			// Wenn bier gewählt
			if (gattung == 1){
				// Bierauswahl holen
				var bier = $('#inputBier').val();
				// Wenn etwas gewählt
				if (bier >= 1 && bier <= 6){
					// Bierauswahl zeigen
					$('#bierAnzahlAuswahl').show();
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
					document.getElementById("bierAnzahl").selectedIndex = 0;
					$('#kostenBiere').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// Bierauswahl verstecken
					$('#bierAnzahlAuswahl').hide();
				}
			}
			// Wenn spirituosen gewählt
			else if (gattung == 2){
				// Spirituosenauswahl holen
				var spirituosen = $('#inputSpirituosen').val();
				// Wenn etwas gewählt
				if (spirituosen >= 1 && spirituosen <= 6){
					// Spirituosenauswahl zeigen
					$('#spirituosenAnzahlAuswahl').show();
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
					$('#spirituosenAnzahl').val("1");
					document.getElementById("spirituosenAnzahl").select();
					$('#kostenSpirituosen').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// Spirituosenauswahl verstecken
					$('#spirituosenAnzahlAuswahl').hide();
				}
			}
			// Wenn biermischgetränke gewählt
			else if (gattung == 3){
				// biermischgetränkeauswahl holen
				var biermisch = $('#inputBiermisch').val();
				// Wenn etwas gewählt
				if (biermisch >= 1 && biermisch <= 6){
					// biermischgetränkeauswahl zeigen
					$('#biermischAnzahlAuswahl').show();
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
					document.getElementById("biermischAnzahl").selectedIndex = 0;
					$('#kostenBiermisch').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// biermischgetränkeauswahl verstecken
					$('#biermischAnzahlAuswahl').hide();
				}
			}
			// Wenn alkoholfreie Getränke gewählt
			else if (gattung == 4){
				// alkoholfreie Getränke holen
				var alkfrei = $('#inputAlkoholfrei').val();
				// Wenn etwas gewählt
				if (alkfrei >= 1 && alkfrei <= 6){
					// alkoholfreie Getränkeauswahl zeigen
					$('#alkoholfreiAnzahlAuswahl').show();
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
					document.getElementById("alkoholfreiAnzahl").selectedIndex = 0;
					$('#kostenAlkoholfrei').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// alkoholfreie Getränkeauswahl verstecken
					$('#alkoholfreiAnzahlAuswahl').hide();
				}
			}
			// Wenn Säfte gewählt
			else if (gattung == 5){
				// Säfte holen
				var saefte = $('#inputSaefte').val();
				// Wenn etwas gewählt
				if (saefte >= 1 && saefte <= 6){
					// Saftauswahl zeigen
					$('#saefteAnzahlAuswahl').show();
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
					document.getElementById("saefteAnzahl").selectedIndex = 0;
					$('#kostenSaefte').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// Saftauswahl verstecken
					$('#saefteAnzahlAuswahl').hide();
				}
			}
			// Wenn Cocktails gewählt
			else if (gattung == 6){
				// Cocktailauswahl holen
				var cocktail = $('#inputCocktails').val();
				// Wenn etwas gewählt
				if (cocktail >= 1 && cocktail <= 6){
					// Cocktailauswahl zeigen
					$('#cocktailsAnzahlAuswahl').show();
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
					document.getElementById("cocktailsAnzahl").selectedIndex = 0;
					$('#kostenCocktails').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// Cocktailauswahl verstecken
					$('#cocktailsAnzahlAuswahl').hide();
				}
			}
			// Wenn Weine gewählt
			else if (gattung == 7){
				// Weinauswahl holen
				var wein = $('#inputWeine').val();
				// Wenn etwas gewählt
				if (wein >= 1 && wein <= 6){
					// Weinauswahl zeigen
					$('#weineAnzahlAuswahl').show();
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
					$('#weineAnzahl').val("1");
					document.getElementById("weineAnzahl").select();
					$('#kostenWeine').html(einzelpreis + " &euro;");
					berechnePreis("1");
				}
				else{
					// Weinauswahl verstecken
					$('#weineAnzahlAuswahl').hide();
				}
			}
		}
		// Wenn art ungleich null und Gaststätte gewählt
		if (art != 0 && gaststaette != 0){
			// Button aktivieren
			$('#btnKaufen').removeAttr('disabled');
		}
		else{
			// Button deaktivieren
			$('#btnKaufen').attr('disabled', 'disabled');
		}
	}

	// Funktion zum Preis berechnen
	function berechnePreis(menge){
		// Meldungen verstecken
		$('#alertFehler').hide();
		$('#alertErfolg').hide();
		// Wenn Menge keine Zahl
		if(isNaN(menge) || menge <= 0){
			// Fehlermeldung
			$('#alertFehler').html("<b>Fehler!</b> Bitte geben Sie eine korrekte Zahl ein!");
			$('#alertFehler').show();
			// Preisfeld mit einer 0 füllen
			switch (gattung){
				case "1":
				  	var preisfeld = "kostenBiere";
				  	break;
				case "2":
					var preisfeld = "kostenSpirituosen";
				  	break;
				case "3":
					var preisfeld = "kostenBiermisch";
				  	break;
				case "4":
					var preisfeld = "kostenAlkoholfrei";
				  	break;
				case "5":
					var preisfeld = "kostenSaefte";
				 	break;
				case "6":
					var preisfeld = "kostenCocktails";
				  	break;
				case "7":
					var preisfeld = "kostenWeine";
				  	break;
			}
			document.getElementById(preisfeld).innerHTML = "0 &euro;";
			// Button deaktivieren
			$('#btnKaufen').attr('disabled', 'disabled');
		}
		// Wenn Menge eine Zahl ist
		else{
			// Button aktivieren
			$('#btnKaufen').removeAttr('disabled');
			// Preisfeld füllen
			switch (gattung){
				case "1":
				  	var preisfeld = "kostenBiere";
				  	gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "2":
					var preisfeld = "kostenSpirituosen";
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "3":
					var preisfeld = "kostenBiermisch";
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "4":
					var preisfeld = "kostenAlkoholfrei";
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "5":
					var preisfeld = "kostenSaefte";
					gesamtpreis = einzelpreis * (menge * 1);
				 	break;
				case "6":
					var preisfeld = "kostenCocktails";
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
				case "7":
					var preisfeld = "kostenWeine";
					gesamtpreis = einzelpreis * (menge * 1);
				  	break;
			}
			gesamtmenge = (menge * 1);
			document.getElementById(preisfeld).innerHTML = gesamtpreis + " &euro;";
		}
	}

	// Funktion zur Wahl der Gaststaette
	function setGaststaette(index){
		// Wenn index gewählt, dann globale Variable setzen
		if (index == 0)
			gaststaette = 0;
		else
			gaststaette = document.getElementById('inputGaststaette').options[index].id;
		checkForm();
	}
	
	// Funktion zum Kaufen der ausgewählten Ware
	function kaufen(){
		// Meldungen verstecken
		$('#alertFehler').hide();
		$('#alertErfolg').hide();
		// Wenn aktuelle Finanzen ausreichen
		if (finanzen >= gesamtpreis){
			// Gattungsname festellen
			if (gattung == 1){
				if (gesamtmenge == 1)
					var gattungsname = "Kasten Bier";
				else
					var gattungsname = "K%E4sten Bier";
			}
			else if (gattung == 2){
				if (gesamtmenge == 1)
					var gattungsname = "Flasche Schnaps";
				else
					var gattungsname = "Flaschen Schnaps";
			}
			else if (gattung == 3){
				if (gesamtmenge == 1)
					var gattungsname = "Kasten Biermischgetr%E4nke";
				else
					var gattungsname = "K%E4sten Biermischgetr%E4nke";
			}
			else if (gattung == 4){
				if (gesamtmenge == 1)
					var gattungsname = "Kasten Softdrinks";
				else
					var gattungsname = "K%E4sten Softdrinks";
			}
			else if (gattung == 5){
				if (gesamtmenge == 1)
					var gattungsname = "Kasten Saft";
				else
					var gattungsname = "K%E4sten Saft";
			}
			else if (gattung == 6){
				if (gesamtmenge == 1)
					var gattungsname = "Kasten Cocktails";
				else
					var gattungsname = "K%E4sten Cocktails";
			}
			else if (gattung == 7){
				if (gesamtmenge == 1)
					var gattungsname = "Flasche Wein";
				else
					var gattungsname = "Flaschen Wein";
			}
			// Sicherheitsfrage
			var kaufen = confirm(unescape("M%F6chtest du wirklich " + gesamtmenge + " " + gattungsname + " f%FCr einen Gesamtpreis von " + gesamtpreis + "%u20AC kaufen?"));
			if (kaufen){
				// Wartezeichen anzeigen
				$("#warten").show();
				// Ajax aufruf
		        $.ajax({
					type: "POST",
					url: "markt/kauf.php",
					cache: false,
					data: { art: art, menge: gesamtmenge, gaststaette: gaststaette},
					success: function(data){
						// Ladeanimation verstecken
						$("#warten").hide();
	                    if(data=='nichtBesitzer'){
	                    	$("#alertFehler").html("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
	                        $("#alertFehler").show();
	                    }
	                    else if(data=='finanzenNichtAusreichend'){
	                    	$("#alertFehler").html("<b>Fehler!</b> Ihre Finanzen sind nicht ausreichend!");
	                        $("#alertFehler").show();
	                    }
	                    else if(data=='ok'){
	                        // Alle Arten auf Ursprung zurücksetzen
	                        document.getElementById("inputBier").selectedIndex = 0;
	                        document.getElementById("inputSpirituosen").selectedIndex = 0;
	                        document.getElementById("inputBiermisch").selectedIndex = 0;
	                        document.getElementById("inputAlkoholfrei").selectedIndex = 0;
	                        document.getElementById("inputSaefte").selectedIndex = 0;
	                        document.getElementById("inputCocktails").selectedIndex = 0;
	                        document.getElementById("inputWeine").selectedIndex = 0;
	                		$('#bierAnzahlAuswahl').hide();
	                		$('#spirituosenAnzahlAuswahl').hide();
	                		$('#biermischAnzahlAuswahl').hide();
	                		$('#alkoholfreiAnzahlAuswahl').hide();
	                		$('#saefteAnzahlAuswahl').hide();
	                		$('#cocktailsAnzahlAuswahl').hide();
	                		$('#weineAnzahlAuswahl').hide();
	                		// Button deaktivieren
	                		$('#btnKaufen').attr('disabled', 'disabled');
	                        // Kontostand updaten
	                        var kontostandNeu = finanzen*1 - gesamtpreis*1;
							finanzen = kontostandNeu;
							$("#kontostand").html("Kontostand: <b>" + finanzen + " &euro;</b>");
							// Meldung
	                    	$("#alertErfolg").html("<b>Erfolg!</b> Der Kauf war erfolgreich!");
	                        $("#alertErfolg").show();
	                    }
	                    else {
	                    	$("#alertFehler").html("<b>Es ist ein unerwarteter Fehler aufgetreten!</b>\n\n" + 
	                    			"Fehler: " + data);
	                        $("#alertFehler").show();
	                    }
					}
	            });
	            return false;
			}
		}
		// Wenn Finanzen nicht ausreichen
		else{
			$("#alertFehler").html("<b>Fehler!</b> Ihre Finanzen sind nicht ausreichend!");
            $("#alertFehler").show();
		}
	}
</script>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-info"><b>Diese Seite befindet sich momentan im Aufbau!</b></div>
<!-- INHALTSBESCHREIBUNG 
<div class="inhalt" align="center">
	<div align="center">
		<img style="height: 80px;" src="/img/bauarbeiter.png">
		<h2>Benutzer hat die M&ouml;glichkeit nach Produkten zu filtern.</h2><br>
	</div>
	<br>
</div>-->
<!-- Filtermenü -->
<div class="registrieren">
	<form class="form-horizontal">
		<!-- Gaststätte -->
		<div class="control-group">
			<label class="control-label" name="inputGaststaette">Gastst&auml;tte:</label>
			<div class="controls">
					<?php
						// FirmenId abfragen
						$firmaId = $_SESSION['firmaId'];
						// Alle Gaststätten abfragen
						$gaststaetten = mysql_query("SELECT id, name, standort FROM gebaeude WHERE firma_id = '$firmaId'");
						// Beginn selectfeld
						$selectFeld = "<select style='width: 300px;' id='inputGaststaette' onchange='setGaststaette(this.selectedIndex)'>";
						$selectFeld = $selectFeld . "<option value='0'>Bitte w&auml;hlen</option>";
						// Zählvariable
						$anzahlGebaeude = 0;
						// Schleifendurchlauf
						while($row = mysql_fetch_row($gaststaetten)){
							$selectFeld = $selectFeld . "<option id='$row[0]' value='1'>$row[1], $row[2]</option>";
							$anzahlGebaeude++;
						}
						// Ende selectfeld
						$selectFeld = $selectFeld . "</select>";
						// Wenn kein Gebäude gebaut wurde
						if ($anzahlGebaeude == 0){
							echo "<div style='font-size: 14px; background-color: rgb(242, 222, 222); color: rgb(185, 74, 72);'>Sie haben noch kein Geb&auml;ude errichtet!</div>";
						}
						else{
							echo $selectFeld;
						}
					?>
			</div>
		</div>
		
		<!-- Art -->
		<div class="control-group">
			<label class="control-label" name="inputArt">Gattung:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputArt" onchange="setGetraenk()">
					<option value="0">Bitte w&auml;hlen</option>
					<option id="Art1" value="1">Biere</option>
					<option id="Art2" value="2">Spirituosen</option>
					<option id="Art3" value="3">Biermischgetr&auml;nke</option>
					<option id="Art4" value="4">Alkoholfreie Getr&auml;nke</option>
					<option id="Art5" value="5">S&auml;fte</option>
					<option id="Art6" value="6">Cocktails</option>
					<option id="Art7" value="7">Weine</option>
				</select>
			</div>
		</div>
		
		
		<!-- Bier -->
		<div id="bier" style="display: none;" class="control-group">
			<label class="control-label" name="inputBier">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputBier" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Pils</option>
					<option value="2">Helles</option>
					<option value="3">Lager</option>
					<option value="4">Wei&szlig;bier</option>
					<option value="5">Schwarzbier</option>
					<option value="6">Altbier</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Bier -->
		<div id="bierAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl F&auml;sser (a 50 ltr):</label>
			<div class="controls">
				<select style="width:60px;" id="bierAnzahl" onchange="berechnePreis((this.selectedIndex + 1))">
					<option value="0">1</option>
					<option value="1">2</option>
					<option value="2">3</option>
					<option value="3">4</option>
					<option value="4">5</option>
					<option value="5">6</option>
					<option value="6">7</option>
					<option value="7">8</option>
					<option value="8">9</option>
					<option value="9">10</option>
				</select>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" name="inputSonstige" id="kostenBiere"></label>
			</div>
	  	</div>
	  	
	  	
		<!-- Spirituosen -->
		<div id="spirituosen" style="display: none;" class="control-group">
			<label class="control-label" name="inputSpirituosen">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputSpirituosen" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Korn</option>
					<option value="2">Whiskey</option>
					<option value="3">Wodka</option>
					<option value="4">Obstbrand</option>
					<option value="5">Rum</option>
					<option value="6">Lik&ouml;r</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Spirituosen -->
		<div id="spirituosenAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl Flaschen (a 1 ltr):</label>
			<div class="controls">
				<input onkeyup="berechnePreis(this.value)" value="1" style='height: 20px; width: 30px;' maxlength="2" id='spirituosenAnzahl' type='text'>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" id="kostenSpirituosen"></label>
			</div>
	  	</div>
		
		
		<!-- Biermischgetränke -->
		<div id="biermisch" style="display: none;" class="control-group">
			<label class="control-label" name="inputBiermisch">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputBiermisch" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Almradler</option>
					<option value="2">Radler</option>
					<option value="3">Diesel</option>
					<option value="4">Goa&szlig;</option>
					<option value="5">Colaweizen</option>
					<option value="6">Russ</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Biermischgetränke -->
		<div id="biermischAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl K&auml;sten (a 20 Fl. 0,5 ltr):</label>
			<div class="controls">
				<select style="width:60px;" id="biermischAnzahl" onchange="berechnePreis((this.selectedIndex + 1))">
					<option value="0">1</option>
					<option value="1">2</option>
					<option value="2">3</option>
					<option value="3">4</option>
					<option value="4">5</option>
					<option value="5">6</option>
					<option value="6">7</option>
					<option value="7">8</option>
					<option value="8">9</option>
					<option value="9">10</option>
				</select>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" id="kostenBiermisch"></label>
			</div>
	  	</div>
	  	
		
		<!-- Alkoholfreie Getränke -->
		<div id="alkoholfrei" style="display: none;" class="control-group">
			<label class="control-label" name="inputAlkoholfrei">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputAlkoholfrei" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Mineralwasser</option>
					<option value="2">Cola</option>
					<option value="3">Zitronenlimonade</option>
					<option value="4">Orangenlimonade</option>
					<option value="5">Apfelschorle</option>
					<option value="6">Cola-Mix</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Alkoholfreie Getränke -->
		<div id="alkoholfreiAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl K&auml;sten (a 20 Fl. 0,75 ltr):</label>
			<div class="controls">
				<select style="width:60px;" id="alkoholfreiAnzahl" onchange="berechnePreis((this.selectedIndex + 1))">
					<option value="0">1</option>
					<option value="1">2</option>
					<option value="2">3</option>
					<option value="3">4</option>
					<option value="4">5</option>
					<option value="5">6</option>
					<option value="6">7</option>
					<option value="7">8</option>
					<option value="8">9</option>
					<option value="9">10</option>
				</select>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" id="kostenAlkoholfrei"></label>
			</div>
	  	</div>
		
		
		<!-- Säfte -->
		<div id="saefte" style="display: none;" class="control-group">
			<label class="control-label" name="inputSaefte">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputSaefte" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Apfelsaft</option>
					<option value="2">Orangensaft</option>
					<option value="3">Kirschsaft</option>
					<option value="4">Bananenfruchtsaft</option>
					<option value="5">Johannisbeerfruchsaft</option>
					<option value="6">Pfirsichfruchtsaft</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Säfte -->
		<div id="saefteAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl K&auml;sten (a 20 Fl. 0,75 ltr):</label>
			<div class="controls">
				<select style="width:60px;" id="saefteAnzahl" onchange="berechnePreis((this.selectedIndex + 1))">
					<option value="0">1</option>
					<option value="1">2</option>
					<option value="2">3</option>
					<option value="3">4</option>
					<option value="4">5</option>
					<option value="5">6</option>
					<option value="6">7</option>
					<option value="7">8</option>
					<option value="8">9</option>
					<option value="9">10</option>
				</select>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" id="kostenSaefte"></label>
			</div>
	  	</div>
		
		
		<!-- Cocktails -->
		<div id="cocktails" style="display: none;" class="control-group">
			<label class="control-label" name="inputCocktails">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputCocktails" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Sex on the Beach</option>
					<option value="2">Long Island Iced Tea</option>
					<option value="3">Zombie</option>
					<option value="4">Hurricane</option>
					<option value="5">Caipirinha</option>
					<option value="6">Mojito</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Cocktails -->
		<div id="cocktailsAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl K&auml;sten (a 20 Fl. 0,5 ltr):</label>
			<div class="controls">
				<select style="width:60px;" id="cocktailsAnzahl" onchange="berechnePreis((this.selectedIndex + 1))">
					<option value="0">1</option>
					<option value="1">2</option>
					<option value="2">3</option>
					<option value="3">4</option>
					<option value="4">5</option>
					<option value="5">6</option>
					<option value="6">7</option>
					<option value="7">8</option>
					<option value="8">9</option>
					<option value="9">10</option>
				</select>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" id="kostenCocktails"></label>
			</div>
	  	</div>
		
		
		<!-- Weine -->
		<div id="weine" style="display: none;" class="control-group">
			<label class="control-label" name="inputWeine">Art:</label>
			<div class="controls">
				<select style="width: 200px;" id="inputWeine" onchange="checkForm()">
					<option value="0">Alle</option>
					<option value="1">Wei&szlig;wein</option>
					<option value="2">Rotwein</option>
					<option value="3">Schaumwein</option>
					<option value="4">Lik&ouml;rwein</option>
					<option value="5">Rose</option>
				</select>
			</div>
		</div>
		<!-- Anzahl Weine -->
		<div id="weineAnzahlAuswahl" style="display: none;" class="control-group">
			<label class="control-label">Anzahl Flaschen (a 0,75 ltr):</label>
			<div class="controls">
				<input onkeyup="berechnePreis(this.value)" value="1" style='height: 20px; width: 30px;' maxlength="2" id='weineAnzahl' type='text'>
			</div>
	   		<label class="control-label">Gesamtkosten:</label>
			<div class="controls">
				<label class="control-label" id="kostenWeine"></label>
			</div>
	  	</div>
		
		
		<!-- Anzeigen-Button -->
	  	<div class="control-group">
	   		<div class="controls">
	   			<button disabled="disabled" id="btnKaufen" type="button" class="btn btn-info" onclick="kaufen()">Kaufen</button>
	    	</div>
	  	</div>
	</form>
</div>

<!-- 
<div align="center">
	<div align="left" style="width: 1000px;">
		<div class="marktBez" align="center"><h3>Markt&uuml;bersicht</h3></div>
		<div class="marktPart">
			<div align="center" class="marktGetr"><h6>Alkoholische Getr&auml;nke</h6></div>
			<!-- Checkboxen 
			<div class="marktCB">
				<label class="checkbox">
  					<input id="a1" type="checkbox">Bier
				</label>
				<label class="checkbox">
  					<input id="a2" type="checkbox">Pils
				</label>
				
				<label class="checkbox">
  					<input id="a3" type="checkbox">Getr&auml;nk 3
				</label>
			</div>
			<div class="marktCB">
				<label class="checkbox">
  					<input id="a4" type="checkbox">Getr&auml;nk 4
				</label>
				<label class="checkbox">
  					<input id="a5" type="checkbox">Getr&auml;nk 5
				</label>
				<label class="checkbox">
  					<input id="a6" type="checkbox">Getr&auml;nk 6
				</label>
			</div>
			<div class="marktCB">
				<label class="checkbox">
  					<input id="a7" type="checkbox">Getr&auml;nk 7
				</label>
				<label class="checkbox">
  					<input id="a8" type="checkbox">Getr&auml;nk 8
				</label>
				<label class="checkbox">
  					<input id="a9" type="checkbox">Getr&auml;nk 9
				</label>
			</div>
		</div>
		<div class="marktPart">
			<div align="center" class="marktGetr"><h6>Mischgetr&auml;nke</h6></div>
			<!-- Checkboxen 
			<div class="marktCB">
				<label class="checkbox">
  					<input id="m1" type="checkbox">Radler
				</label>
				<label class="checkbox">
  					<input id="m2" type="checkbox">Diesel
				</label>
				
				<label class="checkbox">
  					<input id="m3" type="checkbox">Getr&auml;nk 3
				</label>
			</div>
			<div class="marktCB">
				<label class="checkbox">
  					<input id="m4" type="checkbox">Getr&auml;nk 4
				</label>
				<label class="checkbox">
  					<input id="m5" type="checkbox">Getr&auml;nk 5
				</label>
				<label class="checkbox">
  					<input id="m6" type="checkbox">Getr&auml;nk 6
				</label>
			</div>
			<div class="marktCB">
				<label class="checkbox">
  					<input id="m7" type="checkbox">Getr&auml;nk 7
				</label>
				<label class="checkbox">
  					<input id="m8" type="checkbox">Getr&auml;nk 8
				</label>
				<label class="checkbox">
  					<input id="m9" type="checkbox">Getr&auml;nk 9
				</label>
			</div>
		</div>
		<div class="marktPart">
			<div align="center" class="marktGetr"><h6>Alkoholfreie Getr&auml;nke</h6></div>
			<!-- Checkboxen 
			<div class="marktCB">
				<label class="checkbox">
  					<input id="n1" type="checkbox">Apfelschorle
				</label>
				<label class="checkbox">
  					<input id="n2" type="checkbox">Apfelsaft
				</label>
				
				<label class="checkbox">
  					<input id="n3" type="checkbox">Orangensaft
				</label>
			</div>
			<div class="marktCB">
				<label class="checkbox">
  					<input id="n4" type="checkbox">Wasser
				</label>
				<label class="checkbox">
  					<input id="n5" type="checkbox">Sprite
				</label>
				<label class="checkbox">
  					<input id="n6" type="checkbox">Fanta
				</label>
			</div>
			<div class="marktCB">
				<label class="checkbox">
  					<input id="n7" type="checkbox">Cola
				</label>
				<label class="checkbox">
  					<input id="n8" type="checkbox">BitterLemon
				</label>
				<label class="checkbox">
  					<input id="n9" type="checkbox">Ginger Ale
				</label>
			</div>
		</div>
		<br><br><br><br><br>
		<div class="marktBez" align="center">
			<button id="btnAnzeigen" type="button" class="btn btn-info" onclick="ladeTabelle()">Anzeigen</button>
		</div>
	</div>
</div>
-->
<br>
<!-- Ladeanimation -->
<div align="center">
	<img style="display: none;" id="warten" src="/img/loading.gif"/>
	<!-- Meldungen -->
	<div id="alertFehler" style="display: none; width: 400px;" class="alert alert-error"></div>
	<div id="alertErfolg" style="display: none; width: 400px;" class="alert alert-success"></div>
</div>
<!-- Angebote in der Übersicht -->
<div class="markt" id="inhalt"></div>
<?php include '../footer.php';?>