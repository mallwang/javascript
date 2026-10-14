<?php $title="Registrieren"; include 'sessionpruefung.php';
// Wenn User eingeloggt ist
if (isset($_SESSION['nickname'])){
	// Meldung und Abbruch
	echo "Registrieren nicht möglich, Sie sind bereits eingeloggt!";
	include 'footer.php';
	exit;
}
?>
<script type="text/javascript">
//Globale Variablen für die Eingabeprüfung
var benutzer;
var email;
var pw1;
var pw2;
var fir;
var st;

// Funktion registrieren durch Klick auf Button
function registrieren(){
	// Alerts verstecken
	$("#alertBenutzername").hide();
	$("#alertEmail").hide();
	$("#alertFirmenname").hide();
	$("#alertErfolg").hide();
	// Wartezeichen anzeigen
	$("#waitRegistrieren").show();
	// Variablen deklarieren
	inputName = $('#inputName').val();
	inputEmail1 = $('#inputEmail1').val();
	inputPasswort1 = $('#inputPasswort1').val();
	inputPasswort2 = $('#inputPasswort2').val();
	inputFirmenname = $('#inputFirmenname').val();
	inputStandort = $('#inputStandort').val();
	art = "regis";
	// Ajax-Aufruf
	$.ajax({
		type: "POST",
		url: "validate.php",
		cache: false,
		data: { nickname: inputName, email1: inputEmail1, passwort1: inputPasswort1,
				passwort2: inputPasswort2, firmenname: inputFirmenname, standort: inputStandort, art: art},
				success: function(data){
					// Wartezeichen verstecken
					$("#waitRegistrieren").hide();
					if(data=='ok'){
						$('#btnRegistrieren').attr('disabled', 'disabled');
						$('#inputPasswort1').val("");
						$('#inputPasswort2').val("");
						document.getElementById('inputPasswort1').focus();
						$("#alertErfolg").html("<strong>Erfolg!</strong> Ihre Daten wurden erfolgreich &uuml;bermittelt!<br>" +
                 	 		"Sie erhalten diesbez&uuml;glich in K&uuml;rze eine E-Mail an " + inputEmail1 + ".<br>" +
                 	 		"Um die Registrierung abzuschlie&szlig;en, folgen Sie bitte den darin enthaltenen Best&auml;tigungslink.");
						$("#alertErfolg").show();
						// Alle globale Variablen zurücksetzen
						benutzer=0;
						email=0;
						pw1=0;
						pw2=0;
						fir=0;
						st=0;
					}
					else if(data=='benEmailVorhanden'){
						$("#alertFehler").html("<strong>Fehler!</strong> Benutzername und/oder E-Mail Adresse bereits vergeben!");
						$("#alertFehler").show();
					}
					else if(data=='firmaVorhanden'){
						$("#alertFehler").html("<strong>Fehler!</strong> Firmenname bereits vergeben!");
						$("#alertFehler").show();
					}
					else if(data=='nichtAlleAusgefuellt'){
						$("#alertFehler").html("<strong>Fehler!</strong> Bitte f&uuml;llen Sie alle Felder korrekt aus!");
						$("#alertFehler").show();
					}
					else{
               	 		$("#alertFehler").html("<strong>Fehler!</strong> Das Anlegens eines neuen Benutzers schlug fehl!<br>" +
               	 				"Bitte kontaktieren sie den Support<br>Fehler: " + data);
               	 		$("#alertFehler").show();
					}
				}
	});
	return false;
} // Ende Funktion registrieren

/* Funktion regCheck
 * Prüft, ob alle Eingabefelder korrekt ausgefüllt sind
 */
function regCheck(zahl){
	// Benutzername
	if(zahl == '1'){
		// Zeige Wartezeichen
		$("#alertBenutzername").hide();
		var inputName = $('#inputName').val();
		var art = "benutzer";
		if (inputName.length==0){
			benutzer = 0;
		}
		else if(inputName.length>3){
			$("#benutzerCheck").show();
			// öffen skript, das den Namen sucht
            $.ajax({
				type: "POST",
				url: "namenspruefung.php",
				cache: false,
				data: { nickname: inputName, art: art},
				success: function(data){
					$("#benutzerCheck").hide();
                    if(data=='yes'){
                        // Input-Hintergrund hellgrün
                    	$("#inputName").css("border-color","green");
                    	$("#inputName").css("background-color","palegreen");
                    	benutzer = 1;
                    	alleGefuellt(benutzer, email, pw1, pw2, fir, st);
                    }
                    else if(data=='no'){
                        // Input-Hintergrund rot
                        $("#inputName").css("border-color","red");
                        $("#inputName").css("background-color","salmon");
                        $("#alertBenutzername").html("<strong>Fehler!</strong> Benutzername existiert bereits in unserer Datenbank!");
                       	$("#alertBenutzername").show();
                        // bootstrap-alert in rot anzeigen
                        benutzer = 0;
                    }
				}
            });
            return false;
		}
		else{
			benutzer = 0;
			$("#inputName").css("border-color","red");
			$("#inputName").css("background-color","salmon");
			$("#alertBenutzername").html("<strong>Fehler!</strong> Benutzername ist zu kurz!");
            $("#alertBenutzername").show();
		}
	}
	// E-Mail Adresse
	else if(zahl == '2'){
		$("#alertEmail").hide();
		var inputEmail = $('#inputEmail1').val();
		if (inputEmail.length>3){
			// if Email Adresse valide
			var validmailregex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.([a-z][a-z]+)|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i
  			if (validmailregex.test(inputEmail)){
  	  			$("#emailCheck").show();
				var art = "email";
				// öffen skript, das den Namen sucht
	            $.ajax({
					type: "POST",
					url: "namenspruefung.php",
					cache: false,
					data: { email: inputEmail, art: art},
					success: function(data){
						$("#emailCheck").hide();
	                    if(data=='yes'){
	                        // Input-Hintergrund hellgrün
	                    	$("#inputEmail1").css("border-color","green");
	                    	$("#inputEmail1").css("background-color","palegreen");
	                    	email = 1;
	                    	alleGefuellt(benutzer, email, pw1, pw2, fir, st);
	                    }
	                    else if(data=='no'){
	                        // Input-Hintergrund rot
	                        $("#inputEmail1").css("border-color","red");
	                        $("#inputEmail1").css("background-color","salmon");
	                        $("#alertEmail").html("<strong>Fehler!</strong> E-Mail Adresse existiert bereits in unserer Datenbank!");
                        	$("#alertEmail").show();
	                        email = 0;
	                        // bootstrap-alert in rot anzeigen
	                    }
					}
	            });
	            return false;
  			}
  			// Email nicht valide
  			else{
  				$("#emailCheck").hide();
  				$("#inputEmail1").css("border-color","red");
                $("#inputEmail1").css("background-color","salmon");
                $("#alertEmail").html("<strong>Fehler!</strong> Keine g&uuml;ltige E-Mail Adresse!");
            	$("#alertEmail").show();
            	email = 0;
  			}
		}
		else{
			email = 0;
		}
	}
	// Passwort1
	else if(zahl == '3'){
		// pruefe, ob Passwort sicher ist
		var p = '0%';
		var passwort = $('#inputPasswort1').val();
		if(passwort.length<6){
			$("#inputPasswort1").css("border-color","red");
			$("#inputPasswort1").css("background-color","salmon");
			$("#progress").css("width", p);
			pw1 = 0;
		}
		else{
			// Sicherheit verteilen
			var s = 0;
			if(passwort.match(/[a-z]/)||passwort.match(/[A-Z]/)){
				s += 2;
			}
			if(passwort.match(/[^a-z\d]/i)||passwort.match(/[1-9]/)){
			s += 2;
				if(!passwort.match(/[a-z]/)||!passwort.match(/[A-Z]/)){
					s -= 2;
				}
			}
			if(passwort.match(/[\d]/i)) {
				s += 2;
			}
			// Prozente des Progressbalkens verteilen
			switch (s) {
			case 2: p='33%'; break;
			case 4: p='66%'; break;
			case 6: p='100%'; break;
			}
			if(p=='33%'){
				$("#barArt").removeAttr('class');
				$("#barArt").attr('class', 'progress progress-danger');
			}
			else if(p=='66%'){
				$("#barArt").removeAttr('class');
				$("#barArt").attr('class', 'progress progress-warning');
			}
			else if(p=='100%'){
				$("#barArt").removeAttr('class');
				$("#barArt").attr('class', 'progress progress-success');
			}
			$("#inputPasswort1").css("border-color","green");
			$("#inputPasswort1").css("background-color","palegreen");
			$("#progress").css("width", p);
			pw1 = 1;
			regCheck('4');
			alleGefuellt(benutzer, email, pw1, pw2, fir, st);
		}
	}
	// Passwort2
	else if(zahl == '4'){
		// pruefe, ob passwort mit passwort 2 übereinstimmt
		var paw1 = $('#inputPasswort1').val();
		var paw2 = $('#inputPasswort2').val();
		if (paw1 == paw2){
			$("#inputPasswort2").css("border-color","green");
			$("#inputPasswort2").css("background-color","palegreen");
			pw2 = 1;
			alleGefuellt(benutzer, email, pw1, pw2, fir, st);
		}
		else{
			$("#inputPasswort2").css("border-color","red");
			$("#inputPasswort2").css("background-color","salmon");
			pw2 = 0;
			alleGefuellt(benutzer, email, pw1, pw2, fir, st);
		}
	}
	// Firmenname
	else if(zahl == '5'){
			// Prüfen, ob firmenname schon vorhanden
			// Zeige Wartezeichen
			$("#alertFirmenname").hide();
			var inputFirmenname = $('#inputFirmenname').val();
			var art = "firmenname";
			if (inputFirmenname.length==0){
				fir = 0;
			}
			else if (inputFirmenname.length>3){
				// prüfen, ob Name korrekt ist
				//if (inputFirmenname.match(/[A-Za-z0-9]+/)){
				//	alert("Name ok");
				//}
				//else{
				//	alert("Name nicht ok");
				//}
				$("#firmaCheck").show();
				// öffen skript, das den Namen sucht
	            $.ajax({
					type: "POST",
					url: "namenspruefung.php",
					cache: false,
					data: { firmenname: inputFirmenname, art: art},
					success: function(data){
						$("#firmaCheck").hide();
	                    if(data=='yes'){
	                        // Input-Hintergrund hellgrün
	                    	$("#inputFirmenname").css("border-color","green");
	                    	$("#inputFirmenname").css("background-color","palegreen");
	                    	fir = 1;
	                    	alleGefuellt(benutzer, email, pw1, pw2, fir, st);
	                    }
	                    else if(data=='no'){
	                        // Input-Hintergrund rot
	                        $("#inputFirmenname").css("border-color","red");
	                        $("#inputFirmenname").css("background-color","salmon");
	                        $("#alertFirmenname").html("<strong>Fehler!</strong> Firmenname existiert bereits in unserer Datenbank!");
                        	$("#alertFirmenname").show();
	                        // bootstrap-alert in rot anzeigen
	                        fir = 0;
	                    }
					}
	            });
	            return false;
			}
			else{
				fir = 0;
				alleGefuellt(benutzer, email, pw1, pw2, fir, st);
				$("#inputFirmenname").css("border-color","red");
				$("#inputFirmenname").css("background-color","salmon");
				$("#alertFirmenname").html("<strong>Fehler!</strong> Firmenname ist zu kurz!");
            	$("#alertFirmenname").show();
			}
		}
		// Standort
		else if(zahl == '6'){
			// Prüfen, ob Standort
			var standort = $('#inputStandort').val();
			if (standort>=1 && standort<=7){
				st = 1;
				$("#inputStandort").css("border-color","green");
				$("#inputStandort").css("background-color","palegreen");
			}
			else{
				st = 0;
				$("#inputStandort").css("border-color","red");
				$("#inputStandort").css("background-color","salmon");
			}
			alleGefuellt(benutzer, email, pw1, pw2, fir, st);
		}
	} // Ende Funktion regCheck
	
/* Funktion alleGefuellt
 * Aktiviert den Button wenn alle Felder korrekt ausgefüllt sind
 */
function alleGefuellt(benutzer, email, pw1, pw2, fir, st){
	if (benutzer == '1'){
		if (email == '1'){
			if (pw1 == '1'){
				if (pw2 == '1'){
					if (fir == '1'){
						if (st == '1'){
							$('#btnRegistrieren').removeAttr('disabled');
						}
						else{
							$('#btnRegistrieren').attr('disabled', 'disabled');
						}
					}
					else{
						$('#btnRegistrieren').attr('disabled', 'disabled');
					}
				}
				else{
					$('#btnRegistrieren').attr('disabled', 'disabled');
				}
			}
			else{
				$('#btnRegistrieren').attr('disabled', 'disabled');
			}
		}
		else{
			$('#btnRegistrieren').attr('disabled', 'disabled');
		}
	}
	else{
		$('#btnRegistrieren').attr('disabled', 'disabled');
	}
} // Ende Funktion alleGefuellt
</script>
<!-- Komplettes Formular -->
<div class="registrieren">
	<p>
		<br><br><h1>Sch&ouml;n, Sie zu sehen!</h1><br><br>
	</p>
	<form id="registrierung" class="form-horizontal">
		<!-- Benutzername -->
		<div class="control-group">
	    	<label class="control-label" name="inputName">Benutzername:</label>
	    	<div class="controls">
	      		<input type="text" id="inputName" onblur="regCheck('1')" placeholder="Benutzername">
	      		<div style="display: none;" id="benutzerCheck"class="progress progress-striped progress-success active">
  					<div class="bar" style="width: 100%;"></div>
				</div>
				<div id="alertBenutzername" style="display: none;" class="alert alert-error"></div>
	    	</div>
	  	</div>
	  	<!-- E-Mail -->
	  	<div class="control-group">
	    	<label class="control-label" name="inputEmail1">Email*:</label>
	    	<div class="controls">
	      		<input type="email" id="inputEmail1" onblur="regCheck('2')" placeholder="Email">
	      		<div style="display: none;" id="emailCheck"class="progress progress-striped progress-success active">
  					<div class="bar" style="width: 100%;"></div>
				</div>
				<div id="alertEmail" style="display: none;" class="alert alert-error"></div>
	    	</div>
	  	</div>
	  	<!-- Passwort1 -->
	  	<div class="control-group">
	    	<label class="control-label" name="inputPassword">Passwort*:</label>
	    	<div class="controls">
	      		<input type="password" id="inputPasswort1" onkeyup="regCheck('3')" placeholder="Passwort">
	    	</div>
	  	</div>
	  	<!-- Passwort2 + Sicherheitsanzeige -->
	  	<div class="control-group">
	    	<label class="control-label" name="inputPassword">Passwort best&auml;tigen:</label>
	    	<div class="controls">
	      		<input type="password" id="inputPasswort2" onkeyup="regCheck('4')" placeholder="Passwort">
	    	</div><br>
	    	<div class="linkKlein">Passwortsicherheit: </div>
	    	<div id="barArt" class="progress progress-info progress-striped">
  				<div id="progress" class="bar" style="width: 0%"></div>
			</div>
	  	</div>
	  	<!-- Firmenname -->
	  	<div class="control-group">
	    	<label class="control-label" name="inputFirmenname">Firmenname*:</label>
	    	<div class="controls">
	      		<input type="text" id="inputFirmenname" onblur="regCheck('5')" placeholder="Firmenname">
	    		<div style="display: none;" id="firmaCheck"class="progress progress-striped progress-success active">
  					<div class="bar" style="width: 100%;"></div>
				</div>
	    		<div id="alertFirmenname" style="display: none;" class="alert alert-error"></div>
			</div>
	  	</div>
	  	<!-- Standort -->
	  	<div class="control-group">
	    	<label class="control-label" name="inputStandort">Standort*:</label>
	    	<div class="controls">
	      		<select id="inputStandort" onchange="regCheck('6')">
	      			<option value="0">Bitte w&auml;hlen</option>
  					<option value="1">Ansbach</option>
  					<option value="7">Augsburg</option>
  					<option value="4">Bayreuth</option>
  					<option value="2">Landshut</option>
  					<option value="3">M&uuml;nchen</option>
  					<option value="5">Regensburg</option>
  					<option value="6">W&uuml;rzburg</option>
				</select>
	    	</div>
	  	</div>
	  	<!-- Hinweise -->
	  	<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
		<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
		<!-- Registrieren-Button -->
	  	<div class="control-group">
	   		<div class="controls">
	   			<button disabled="disabled" id="btnRegistrieren" type="button" class="btn btn-info" onclick="registrieren()">Registrieren</button>
	      		<img style="display: none;" id="waitRegistrieren" src="/img/loading.gif"/>
	    	</div>
	  	</div>
	  	<br>
	  	<div style="font-size: 12px;">* Die gekennzeichneten Felder k&ouml;nnen im Spielverlauf ge&auml;ndert werden</div>
	</form>
</div>

<!-- Focus auf das Feld Benutzername setzen -->
<script type="text/javascript">
	document.getElementById('inputName').focus();
</script>

<?php include 'footer.php';?>