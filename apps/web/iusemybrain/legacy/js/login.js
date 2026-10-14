$(document).ready(function(){
                // +++++++++++++++++++++++
                // +++++ LOGIN CHECK +++++
                // +++++++++++++++++++++++
                $("#login").submit(function(){
                	$("#logWarten").show();
                	inputName = $('#loginName').val();
                	inputPasswort = $('#loginPasswort').val();
                	art = "login";
                       $.ajax({
                    	   		type: "POST",
                    	   		url: "validate.php",
                    	   		cache: false,
                    	   		data: { nickname: inputName, passwort: inputPasswort, art: art},
                    	   		success: function(data){
                    	   		$("#logWarten").hide();
                                if(data=='nichtAktiviert'){
                                	$("#alertLoginFehler").html("<strong>Fehler!</strong> Ihr Account wurde noch nicht aktiviert!<br>" +
                                			"Bitte bestätigen Sie die Willkommens-EMail, die sie nach der Registrierung erhalten haben.");
                                 	$("#alertLoginFehler").show();
                                }
                                else if(data=='no'){
                                	$("#alertLoginFehler").html("<strong>Fehler!</strong> Fehlerhafte Zugangsdaten!<br>Bitte überprüfen Sie Ihre Eingabe und versuchen Sie es erneut.");
                                	$("#alertLoginFehler").show();
                                }
                                else if(data=='ok'){
                                    document.location='index.php';
                                }
                                else{
                                	$("#alertLoginFehler").html("<strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>" +
                                			"Bitte kontaktieren sie diesbezüglich den Support<br><br>Fehler: " + data);
                                	$("#alertLoginFehler").show();
                                }
                            }
                        });
                    return false;
                });
            });