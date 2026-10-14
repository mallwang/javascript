$(document).ready(function(){
                // +++++++++++++++++++++++
                // +++++ SUPPORT TIC +++++
                // +++++++++++++++++++++++
                $("#support").submit(function(){
                	 $("#alertError").hide();
                	 $("#alertSuccess").hide();
                	$("#supportWarten").show();
                	betreff = $('#betreff').val();
                	email = $('#eMail').val();
                	text = $('#text').val();
                       $.ajax({
                    	   		type: "POST",
                    	   		url: "support_ticket.php",
                    	   		cache: false,
                    	   		data: { betreff: betreff, email: email, text: text},
                    	   		success: function(data){
                    	   		$("#supportWarten").hide();
                                if(data=='keineEmail'){
                                	$("#alertError").html("<strong>Fehler!</strong> Bitte geben Sie eine gültige E-Mail Adresse ein!");
                                	$("#alertError").show();
                                }
                                else if (data=='nichtAlleFelder'){
                                	$("#alertError").html("<strong>Fehler!</strong> Bitte füllen Sie alle Felder korrekt aus!");
                                	$("#alertError").show();
                                }
                                else if (data=='ok'){
                                	$("#alertSuccess").html("<strong>Erfolg!</strong> Ihre Nachricht wurde erfolgreich übermittelt!<br>" +
                                			"Eine Kopie Ihrer Nachricht wurde an Ihre E-Mail Adresse versandt.");
                                	$("#alertSuccess").show();
                                }
                                else{
                                	$("#alertError").html("<strong>Fehler!</strong> Das Übermitteln Ihrer Nachricht schlug fehl!<br>" +
                                 	 		"Bitte teilen Sie uns per E-Mail diesen Fehler mit:<br>Fehler: " + data);
                                 	$("#alertError").show();
                                }
                            }
                        });
                    return false;
                });
            });