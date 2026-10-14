<?php $title="Profil"; include '../sessionpruefung.php'; include '../geheim.php';
$aktUser = $_SESSION['nickname'];
$aktFirma = $_SESSION['firmenname'];
$daten = anfrage("SELECT firmenname, nickname, email FROM user, firmendaten WHERE user.id = firmendaten.user_id AND email = '$aktUser'");
//print_r($daten);
$firmenname = $daten[0];
$nickname = $daten[1];
$email = $daten[2];
?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<script type="text/javascript">
// Globale Variablen setzen
var aktUser = "<?php echo $aktUser;?>";
var aktFirma = "<?php echo $aktFirma;?>";
function firmennameAendern(){
	$('#formNeueEmail').slideUp();
	$('#formNeuesPW').slideUp();
	$('#formAccountLoeschen').slideUp();
	$('#formNeuerFirmenname').slideDown();
}
function eMailAendern(){
	$('#formNeuesPW').slideUp();
	$('#formNeuerFirmenname').slideUp();
	$('#formAccountLoeschen').slideUp();
	$('#formNeueEmail').slideDown();
}
function passwortAendern(){
	$('#formNeueEmail').slideUp();
	$('#formNeuerFirmenname').slideUp();
	$('#formAccountLoeschen').slideUp();
	$('#formNeuesPW').slideDown();
}
function accountLoeschen(){
	$('#formNeueEmail').slideUp();
	$('#formNeuerFirmenname').slideUp();
	$('#formNeuesPW').slideUp();
	$('#formAccountLoeschen').slideDown();
}

// E-Mail Adresse
function neueEmail(){
	// Hinweise entfernen
	$("#alertNeueEMailFehler").hide();
	$("#alertNeueEMailErfolg").hide();
	// Daten aus Formular firmaName2
	var inputEmail1 = $('#inputEmail1').val().toLowerCase();
	var inputEmail2 = $('#inputEmail2').val().toLowerCase();
	var inputPasswort = $('#inputPasswort').val();
	// Prüfen ob alle ausgefüllt
	if (inputEmail1.length>0 && inputEmail2.length>0 && inputPasswort.length>0){
        // Prüfen, ob Email1 == Email2
        if (inputEmail1 == inputEmail2){
            // Prüfen, ob alte E-Mail != neuer E-Mail
            if (aktUser != inputEmail1){
                // if Email Adresse valide
				var validmailregex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.([a-z][a-z]+)|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i
  				if (validmailregex.test(inputEmail1)){
		            // Ladeanimation
		            $("#emailNeuWarten").show();
		            var art = "email";
		            // Ajax aufruf
		            $.ajax({
						type: "POST",
						url: "datenNeu.php",
						cache: false,
						data: { email1: inputEmail1, passwort: inputPasswort, art: art},
						success: function(data){
							$("#emailNeuWarten").hide();
		                    if(data=='pwFalsch'){
		                    	$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Das angegebene Passwort ist nicht korrekt!");
		                        $("#alertNeueEMailFehler").show();
		                    }
		                    else if(data=='emailFalsch'){
		                    	$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Die eingegeben E-Mail Adressen sind nicht korrekt!");
		                        $("#alertNeueEMailFehler").show();
		                    }
		                    else if(data=='ok'){
			                    // Felder leeren
		                    	$('#inputEmail1').val("");
		                    	$('#inputEmail2').val("");
		                    	$('#inputPasswort').val("");
		                    	// Angezeigte User in Navigationsleiste aktualisieren
		                        $("#eMail1").html(inputEmail1 + "<b class='caret'></b>");
		                        // Angezeigte Email im Profil aktualisieren
		                        $("#eMail2").html(inputEmail1 + " (<a id='neueEMail'>&auml;ndern</a>)");
		                        // Aktuellen User aktualisieren
		                        aktUser = inputEmail1;
		                        // Mitteilung
		                    	$("#alertNeueEMailErfolg").html("<strong>Erfolg!</strong> Deine E-Mail Adresse wurde erfolgreich ge&auml;ndert!<br>"
		                            	+ "Dir wurde an deine alte sowie neue E-Mail Adresse eine Best&auml;tigung gesandt.");
		                        $("#alertNeueEMailErfolg").show();
		                       
		                    }
		                    else if(data=='eMailVorhanden'){
		                    	$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Die eingegebe E-Mail Adresse ist bereits vorhanden!");
		                        $("#alertNeueEMailFehler").show();
		                    }
		                    else {
		                    	$("#alertNeueEMailFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
		                    			"Fehler: " + data);
		                        $("#alertNeueEMailFehler").show();
		                    }
						}
		            });
		            return false;
  				}
  				// Wenn E-Mail nicht valide
  				else{
  					$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Die eingegebenen E-Mail Adressen sind nicht g&uuml;ltig!");
  	                $("#alertNeueEMailFehler").show();
  				}
        	}
            // Wenn neue E-Mail == alte E-Mail
            else{
            	$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Die neue und alte E-Mail Adresse sind identisch!");
                $("#alertNeueEMailFehler").show();
            }
        }
        // Wenn E-Mail Adressen nicht überein stimmen
        else{
        	$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Die eingegebenen E-Mail Adressen stimmen nicht &uuml;berein!");
            $("#alertNeueEMailFehler").show();
        }
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alertNeueEMailFehler").html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
        $("#alertNeueEMailFehler").show();
	}
}
// Passwortsicherheit
var pw1;
function passwortSicherheit(){
	var p = '0%';
	var passwort = $('#inputPasswort1').val();
	if (passwort.length<6){
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
	}
}
// Passwortvergleich
var pw2;
function passwortVergleich(){
	var passwort1 = $('#inputPasswort1').val();
	var passwort2 = $('#inputPasswort2').val();
	// Prüfen, ob Passwort mindestens 6 Stellen lang
	if (passwort2.length==0){
		pw2 = 0;
	}
	// Wenn Passwort kleiner als 6 Stellen
	else if (passwort2.length<6){
		$("#inputPasswort2").css("border-color","red");
		$("#inputPasswort2").css("background-color","salmon");
		pw2 = 0;
	}
	// Wenn Passwort mindestens 6 Stellen lang
	else{
		// Prüfen ob Passwörter identisch
		if (passwort1 == passwort2){
			$("#inputPasswort2").css("border-color","green");
			$("#inputPasswort2").css("background-color","palegreen");
			pw2 = 1;
		}
		// Wenn Passwörter nicht identisch
		else{
			$("#inputPasswort2").css("border-color","red");
			$("#inputPasswort2").css("background-color","salmon");
			pw2 = 0;
		}
	}
}
// Neues Passwort
function neuesPW(){
	// Hinweise entfernen
	$("#alertNeuesPWFehler").hide();
	$("#alertNeuesPWErfolg").hide();
	// Daten aus Formular
	var inputPasswort1 = $('#inputPasswort1').val();
	var inputPasswort2 = $('#inputPasswort2').val();
	var inputPasswort = $('#inputPasswort3').val();
	// Prüfen ob alle ausgefüllt
	if (inputPasswort1.length>0 && inputPasswort2.length>0 && inputPasswort.length>0){
		// Prüfen, ob Passwort1 sicher ist
		if (pw1 == 1){
	        // Prüfen, ob Passwort1 == Passwort2
	        if (inputPasswort1 == inputPasswort2){
	            // Ladeanimation
	            $("#pwNeuWarten").show();
	            var art = "passwortIntern";
	            // Ajax aufruf
	            $.ajax({
					type: "POST",
					url: "datenNeu.php",
					cache: false,
					data: { passwort1: inputPasswort1, passwort2: inputPasswort2, passwort: inputPasswort, art: art},
					success: function(data){
						$("#pwNeuWarten").hide();
	                    if(data=='pwFalsch'){
	                    	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Das aktuelle Passwort ist nicht korrekt!");
	                        $("#alertNeuesPWFehler").show();
	                    }
	                    else if(data=='gleichesPW'){
	                    	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Das neue und alte Passwort sind identisch!");
	                        $("#alertNeuesPWFehler").show();
	                    }
	                    else if(data=='ok'){
	                    	// Felder leeren
	                    	$('#inputPasswort1').val("");
	                    	$('#inputPasswort2').val("");
	                    	$('#inputPasswort3').val("");
	                    	// Mitteilung
	                    	$("#alertNeuesPWErfolg").html("<strong>Erfolg!</strong> Dein Passwort wurde erfolgreich ge&auml;ndert!<br>"
	                            	+ "Dir wurde an dazu an deine E-Mail Adresse eine Best&auml;tigung gesendet");
	                        $("#alertNeuesPWErfolg").show();
							// Farbe und Rahmen der Felder zurücksetzen
	                        $("#inputPasswort1").css("border-color","rgb(204,204,204)");
	        				$("#inputPasswort1").css("background-color","white");
	        				$("#inputPasswort2").css("border-color","rgb(204,204,204)");
	        				$("#inputPasswort2").css("background-color","white");
	                    }
	                    else {
	                    	$("#alertNeuesPWFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
	                    			"Fehler: " + data);
	                        $("#alertNeuesPWFehler").show();
	                    }
					}
	            });
	            return false;
	        }
	        else{
	        	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Die eingegebenen Passw&ouml;rter stimmen nicht &uuml;berein!");
	            $("#alertNeuesPWFehler").show();
	        }
		}
		// Passwort ist nicht sicher
		else{
			$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Das Passwort ist nicht sicher und/oder zu kurz!");
            $("#alertNeuesPWFehler").show();
		}
	}
	else{
		$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
        $("#alertNeuesPWFehler").show();
	}
}
// Neuer Firmenname
function neuerFirmenname(){
	// Hinweise entfernen
	$("#alertNeuerFirmennameFehler").hide();
	$("#alertNeuerFirmennameErfolg").hide();
	// Daten aus Formular
	var inputFirmenname1 = $('#inputFirmenname1').val();
	var inputFirmenname2 = $('#inputFirmenname2').val();
	var inputPasswort = $('#inputPasswort4').val();
	// Prüfen ob alle ausgefüllt
	if (inputFirmenname1.length>0 && inputFirmenname2.length>0 && inputPasswort.length>0){
        // Prüfen ob Firmenname1 == Firmenname2
        if (inputFirmenname1 == inputFirmenname2){
        	// Prüfen, ob alter Firmenname != neuem Firmenname
            if (aktFirma != inputFirmenname1){
	            // Prüfen ob Firmenname mindestens 3 Zeichen hat
				if (inputFirmenname1.length>=3){
		            // Ladeanimation
		            $("#firmennameNeuWarten").show();
		            var art = "firmenname";
		            // Ajax aufruf
		            $.ajax({
						type: "POST",
						url: "datenNeu.php",
						cache: false,
						data: { firmenname1: inputFirmenname1, firmenname2: inputFirmenname2, passwort: inputPasswort, art: art},
						success: function(data){
							// Wartezeichen verstecken
							$("#firmennameNeuWarten").hide();
		                    if(data=='pwFalsch'){
		                    	$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Das angegebene Passwort ist nicht korrekt!");
		                        $("#alertNeuerFirmennameFehler").show();
		                    }
		                    else if(data=='ok'){
		                    	// Felder leeren
		                    	$('#inputFirmenname1').val("");
		                    	$('#inputFirmenname2').val("");
		                    	$('#inputPasswort4').val("");
		                    	// Mitteilung
		                    	$("#alertNeuerFirmennameErfolg").html("<strong>Erfolg!</strong> Dein Firmenname wurde erfolgreich ge&auml;ndert!<br>"
		                            	+ "Dir wurde dazu an deine E-Mail Adresse eine Best&auml;tigung gesendet");
		                        $("#alertNeuerFirmennameErfolg").show();
		                     	// Angezeigten Firmennamen in Navigationsleiste aktualisieren
		                        $("#firmaName1").html(inputFirmenname1 + "<b class='caret'></b>");
		                     	// Angezeigten Firmennamen im Profil aktualisieren
		                        $("#firmaName2").html(inputFirmenname1 + " (<a id='neuerFirmenname'>&auml;ndern</a>)");
		                        // Firmenname aktualisieren
		                        aktFirma = inputFirmenname1;
		                    }
		                    else if(data=='firmennameVorhanden'){
		                    	$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Der eingegebene Firmenname ist bereits vorhanden!");
		                        $("#alertNeuerFirmennameFehler").show();
		                    }
		                    else if(data=='updateNichtOk'){
		                    	$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Der Firmenname konnte nicht ge&auml;ndert werden!");
		                        $("#alertNeuerFirmennameFehler").show();
		                    }
		                    else {
		                    	$("#alertNeuerFirmennameFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
		                    			"Fehler: " + data);
		                        $("#alertNeuerFirmennameFehler").show();
		                    }
						}
		            });
		            return false;
				}
				// Wenn Firmenname kürzer als 3 Zeichen ist
				else{
					$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Der Firmenname ist zu kurz (mind. 3 Zeichen)!");
		            $("#alertNeuerFirmennameFehler").show();
		        }
            }
         	// Wenn neuer Firmenname == altem Firmennamen
            else{
            	$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Der neue und alte Firmenname sind identisch!");
                $("#alertNeuerFirmennameFehler").show();
            }
        }
        // Wenn Firmennamen nicht übereinstimmen
        else{
        	$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Die eingegebenen Firmennamen stimmen nicht &uuml;berein!");
            $("#alertNeuerFirmennameFehler").show();
        }
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alertNeuerFirmennameFehler").html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
        $("#alertNeuerFirmennameFehler").show();
	}
}

function btnAktivieren(){
	// Account löschen Button aktivieren wenn disabled
	if (document.getElementById('loescheAccountBtn').getAttribute('disabled') == "disabled"){
		$('#loescheAccountBtn').removeAttr('disabled');
	}
	else{
		$('#loescheAccountBtn').attr('disabled', 'disabled');
	}
}

function loescheAccount(){
	// Hinweise entfernen
	$("#alertloescheAccountFehler").hide();
	$("#alertloescheAccountErfolg").hide();
	// Daten aus Formular
	var inputPasswort = $('#inputPasswort5').val();
	// Prüfen ob alle ausgefüllt
	if (inputPasswort.length>0){
		// Ladeanimation
        $("#accountLoeschenWarten").show();
        var art = "accountLoeschen";
        // Ajax aufruf
        $.ajax({
			type: "POST",
			url: "datenNeu.php",
			cache: false,
			data: { passwort: inputPasswort, art: art},
			success: function(data){
				// Wartezeichen verstecken
				$("#accountLoeschenWarten").hide();
                if(data=='pwFalsch'){
                	$("#alertloescheAccountFehler").html("<strong>Fehler!</strong> Das angegebene Passwort ist nicht korrekt!");
                    $("#alertloescheAccountFehler").show();
                }
                else if(data=='ok'){
                    // Alert
                    alert(unescape("Dein Account wurde erfolgreich zum L%F6schen vorgesehen%21%0AEine Best%E4tigung wurde an deine E-Mail Adresse gesendet.%0A%0ADu wirst nun ausgeloggt."));
                    // Ausloggen
                    document.location='/Logout';
                }
                else {
                	$("#alertloescheAccountFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
                			"Fehler: " + data);
                    $("#alertloescheAccountFehler").show();
                }
			}
        });
        return false;
	}
	// Wenn Passwort nicht gesetzt
	else{
		$("#alertloescheAccountFehler").html("<strong>Fehler!</strong> Bitte trage dein Passwort ein!");
        $("#alertloescheAccountFehler").show();
	}
}
</script>

<div class="persoenlicheDaten">
	<h2>Ihre pers&ouml;nlichen Daten:</h2>
	<form class="form-horizontal">
		<!-- FIRMENNAME -->
		<div class="control-group">
	    	<label class="control-label">Firmenname:</label>
	    	<div class="controls">
	      		<label class="control-label"><div id="firmaName2"><?php echo $firmenname;?> (<a onclick="firmennameAendern()" id="neuerFirmenname">&auml;ndern</a>)</div></label>
			</div>
		</div>
		<!-- GESCHÄFTSFÜHRER -->
		<div class="control-group">
			<label class="control-label">Gesch&auml;ftsf&uuml;hrer:</label>
	    	<div class="controls">
	    		<label class="control-label"><?php echo $nickname;?></label>
	    	</div>
		</div>
		<!-- E-MAIL ADRESSE -->
		<div class="control-group">
	    	<label class="control-label">E-Mail Adresse:</label>
	    	<div class="controls">
	    		<label class="control-label"><div id="eMail2"><?php echo $email;?> (<a onclick="eMailAendern()" id="neueEmail">&auml;ndern</a>)</div></label>
	    	</div>
	    </div>
		<!-- PASSWORT ÄNDERN -->
		<div class="control-group">
	    	<label class="control-label"><a onclick="passwortAendern()" id="neuesPW">Passwort &auml;ndern</a></label>
	    	<div class="controls">
	    		<label class="control-label"></label>
	    	</div>
	    </div>
	    <!-- ACCOUNT LÖSCHEN -->
		<div class="control-group">
	    	<label class="control-label"><a onclick="accountLoeschen()" id="neuesPW">Account l&ouml;schen</a></label>
	    	<div class="controls">
	    		<label class="control-label"></label>
	    	</div>
	    </div>
	    <!-- E-MAIL ADRESSE ÄNDERN FORMULAR -->
		<div id="formNeueEmail" style="display: none;">
			<b>Neue E-Mail</b><br><br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Neue E-Mail:</label>
					<div class="controls">
						<input type="email" id="inputEmail1" placeholder="Email">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">E-Mail wiederholen:</label>
					<div class="controls">
						<input type="email" id="inputEmail2" placeholder="Email">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Aktuelles Passwort:</label>
					<div class="controls">
						<input type="password" id="inputPasswort" placeholder="aktuelles Passwort">
					</div>
				</div>
				<div id="alertNeueEMailFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertNeueEMailErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="emailNeuWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button onclick="neueEmail()" type="button" id="neueEmailBtn" class="btn">Absenden</button>
					</div>
				</div>
			</form>
		</div>
	    <!-- PASSWORT ÄNDERN FORMULAR -->
	    <div id="formNeuesPW" style="display: none;">
	    	<b>Neues Passwort</b><br><br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Neues Passwort:</label>
					<div class="controls">
						<input type="password" id="inputPasswort1" onkeyup="passwortSicherheit()" placeholder="Neues Passwort">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Passwort wiederholen:</label>
					<div class="controls">
						<input type="password" id="inputPasswort2" onkeyup="passwortVergleich()" placeholder="Passwort wiederholen">
					</div>
					<div class="linkKlein">Passwortsicherheit: </div>
	    				<div id="barArt" class="progress progress-info progress-striped">
  						<div id="progress" class="bar" style="width: 0%"></div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Aktuelles Passwort:</label>
					<div class="controls">
						<input type="password" id="inputPasswort3" placeholder="aktuelles Passwort">
					</div>
				</div>
				<div id="alertNeuesPWFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertNeuesPWErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="pwNeuWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button onclick="neuesPW()" type="button" id="neuesPWBtn" class="btn">Absenden</button>
					</div>
				</div>
			</form>
		</div>
		<!-- FIRMENNAME ÄNDERN FORMULAR -->
	    <div id="formNeuerFirmenname" style="display: none;">
	    	<b>Neuer Firmenname</b><br><br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Neuer Firmenname:</label>
					<div class="controls">
						<input type="text" id="inputFirmenname1" placeholder="Neuer Firmenname">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Firmenname wiederholen:</label>
					<div class="controls">
						<input type="text" id="inputFirmenname2" placeholder="Firmenname wiederholen">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Aktuelles Passwort:</label>
					<div class="controls">
						<input type="password" id="inputPasswort4" placeholder="aktuelles Passwort">
					</div>
				</div>
				<div id="alertNeuerFirmennameFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertNeuerFirmennameErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="firmennameNeuWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button onclick="neuerFirmenname()" type="button" id="neuerFirmennameBtn" class="btn">Absenden</button>
					</div>
				</div>
			</form>
		</div>
		<!-- ACCOUNT LÖSCHEN FORMULAR -->
	    <div id="formAccountLoeschen" style="display: none;">
	    	<b>Account l&ouml;schen</b>
	    	<br>
	    	<div class="alert alert-error">
	    		Wenn du deinen Account l&ouml;schen m&ouml;chtest, gib bitte hier dein aktuelles Passwort ein
	    		und best&auml;tige diese Aktion durch die CheckBox.<br><br>
	    		Im Anschluss wird dein Benutzerkonto zum L&ouml;schen vorgesehen und nach 3 Tagen gel&ouml;scht.<br>
	    		Falls du dich in dieser Zeitspanne jedoch erneut einloggst, wird das L&ouml;schen widerrufen.
	    	</div>
	    	<br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Aktuelles Passwort:</label>
					<div class="controls">
						<input type="password" id="inputPasswort5" placeholder="aktuelles Passwort">
					</div>
				</div>
				<div class="control-group">
					<label class="checkbox">
	     				<input type="checkbox" onclick="btnAktivieren()"> Ja, mein Account soll zur L&ouml;schung vorgesehen werden!
	    			</label>
    			</div>
    			<br>
				<div id="alertloescheAccountFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertloescheAccountErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="accountLoeschenWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button disabled="disabled" onclick="loescheAccount()" type="button" id="loescheAccountBtn" class="btn">Absenden</button>
					</div>
				</div>
			</form>
		</div>
	</form>
</div>
<?php include '../footer.php';?>