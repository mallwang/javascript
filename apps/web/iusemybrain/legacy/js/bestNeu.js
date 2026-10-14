$(document).ready(function(){
                // +++++++++++++++++++++++
                // +++++ NEUE BESTMA +++++
                // +++++++++++++++++++++++
                $("#bestNeu").submit(function(){
                	$("#alertBestNeu").hide();
                	$("#alertBestNeuFehler").hide();
                	$("#bestNeuWarten").show();
                	inputEmail = $('#bestNeuEMail').val();
                	art = "best";
                       $.ajax({
                    	   		type: "POST",
                    	   		url: "datenNeu.php",
                    	   		cache: false,
                    	   		data: { email: inputEmail, art: art},
                    	   		success: function(data){
                    	   		$("#bestNeuWarten").hide();
                                if(data=='nichtAlleWerte'){
                                	$("#alertBestNeuFehler").html("<strong>Fehler!</strong> Bitte geben Sie einen Wert ein!");
                                	$("#alertBestNeuFehler").show();
                                }
                                else if(data=='schonAktiviert'){
                                	$("#alertBestNeuFehler").html("<strong>Fehler!</strong> Die angegebene E-Mail Adresse wurde schon aktiviert!");
                                	$("#alertBestNeuFehler").show();
                                }
                                else if(data=='ok'){
                                	$("#alertBestNeu").show();
                                }
                                else{
                                	$("#alertBestNeuFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbezüglich den Support\n\n" + 
                                			"Fehler: " + data);
                                	$("#alertBestNeuFehler").show();
                                }
                            }
                        });
                    return false;
                });
            });