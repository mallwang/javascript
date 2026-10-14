$(document).ready(function(){
                // +++++++++++++++++++++++
                // +++++ NEUES PASSW +++++
                // +++++++++++++++++++++++
                $("#pwNeu").submit(function(){
                	$("#alertPwNeu").hide();
                	$("#alertPwNeuFehler").hide();
                	$("#pwNeuWarten").show();
                	inputName = $('#pwNeuName').val();
                	art = "passwort";
                       $.ajax({
                    	   		type: "POST",
                    	   		url: "datenNeu.php",
                    	   		cache: false,
                    	   		data: { nickname: inputName, art: art},
                    	   		success: function(data){
                    	   		$("#pwNeuWarten").hide();
                                if(data=='nichtAlleWerte'){
                                	$("#alertPwNeuFehler").html("<strong>Fehler!</strong> Bitte geben Sie einen Wert ein!");
                                	$("#alertPwNeuFehler").show();
                                }
                                else if(data=='nichtExistentEmail'){
                                	$("#alertPwNeuFehler").html("<strong>Fehler!</strong> Die angegebene E-Mail Adresse existiert nicht in unserer Datenbank.");
                                	$("#alertPwNeuFehler").show();
                                }
                                else if(data=='nichtExistentBenutzer'){
                                	$("#alertPwNeuFehler").html("<strong>Fehler!</strong> Der angegebene Benutzer existiert nicht in unserer Datenbank.");
                                	$("#alertPwNeuFehler").show();
                                }
                                else if(data=='ok'){
                                	$("#alertPwNeu").show();
                                }
                                else{
                                	$("#alertPwNeuFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbezüglich den Support\n\n" + 
                                			"Fehler: " + data);
                                	$("#alertPwNeuFehler").show();
                                }
                            }
                        });
                    return false;
                });
            });