$(document).ready(function(){
                // +++++++++++++++++++++++
                // +++++ REGIS CHECK +++++
                // +++++++++++++++++++++++
                $("#registrierung").submit(function(){
                	 $("#alertPwNeuFehler1").hide();
                	 $("#alertRegErfolg").hide();
                	$("#regWarten").show();
                	inputName = $('#inputName').val();
                	inputEmail1 = $('#inputEmail1').val();
                	inputPasswort1 = $('#inputPasswort1').val();
                	inputPasswort2 = $('#inputPasswort2').val();
                	art = "regis";
                       $.ajax({
                    	   		type: "POST",
                    	   		url: "validate.php",
                    	   		cache: false,
                    	   		data: { nickname: inputName, email1: inputEmail1, passwort1: inputPasswort1, passwort2: inputPasswort2, art: art},
                    	   		success: function(data){
                    	   		$("#regWarten").hide();
                                if(data=='ok'){
                                	$('#regButton').attr('disabled', 'disabled');
                                	$('#inputPasswort1').val("");
                                	$('#inputPasswort2').val("");
                                	document.getElementById('inputPasswort1').focus();
                                	$("#alertRegErfolg").html("<strong>Erfolg!</strong> Ihre Daten wurden erfolgreich übermittelt!<br>" +
                                 	 		"Sie haben diesbezüglich soeben eine E-Mail erhalten.<br>" +
                                 	 		"Um die Registrierung abzuschließen, bestätigen Sie diese bitte");
                                 	 $("#alertRegErfolg").show();
                                 	 benutzer=0;
                                 	 email=0;
                                 	 pw1=0;
                                 	 pw2=0;
                                 	 
                                }
                                else{
                               	 	$("#alertPwNeuFehler").html("<strong>Fehler!</strong> Das Anlegens eines neuen Benutzers schlug fehl!<br>" +
                             	 		"Bitte kontaktieren sie den Support<br>Fehler: " + data);
                             	 	$("#alertPwNeuFehler").show();
                                }
                            }
                        });
                    return false;
                });
            });