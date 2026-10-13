$(document).ready(function(){
	
	// Registrierungsformular abgeschickt
	$("#register").submit(function(){
		$("#alertRegister").hide();
		$("#waitRegister").show();
		$("#btnRegister").attr("disabled");
		// Datenfelder zuweisen
		var vorname = $('#vornameRegister').val();
		var nachname = $('#nachnameRegister').val();
		var verein = $('#vereinRegister').val();
		var email = $('#emailRegister').val();
		var passwort = $('#passwortRegister').val();
		// Ajax-Aufruf
		$.ajax({
			type: "POST",
			url: "functions/register.php",
			cache: false,
			data: {vorname: vorname, nachname: nachname, verein: verein, email: email, passwort: passwort},
				success: function(data){
					$("#waitRegister").hide();
					$("#btnRegister").removeAttr("disabled");
					if(data=='emailSchonVorhanden'){
						$("#alertRegister").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Eine identische E-Mail Adresse existiert bereits.</div></div>");
						$("#alertRegister").show();
					}
					else if(data=='ok'){
						// Alle Felder l�schen
						$('#vornameRegister').val("");
						$('#nachnameRegister').val("");
						$('#vereinRegister').val("");
						$('#emailRegister').val("");
						$('#passwortRegister').val("");
						// Fokus setzen
						$('#vornameRegister').focus();
						// Meldung anzeigen
						$("#alertRegister").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong><br>Der Verein " + verein + " wurde erstellt!<br>" + 
								"Sie erhalten diesbezüglich in Kürze eine E-Mail an <strong>" + email + ".</strong><br>Um die Registrierung abzuschließen, folgen Sie bitte den darin enthaltenen Bestätigungslink.</div></div>");
						$("#alertRegister").show();
					}
					else{
						$("#alertRegister").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
						$("#alertRegister").show();
					}
				}
		});
		return false;
	});
});