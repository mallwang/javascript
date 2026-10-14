 $(document).ready(function(){
                // +++++++++++++++++++++++
                // +++++ LOGIN CHECK +++++
                // +++++++++++++++++++++++
                $("#login").submit(function(){
                    $("#loginmoment").show();
                    $("#loginerror").hide();
                    $("#loginsuccess").hide();
                    $("#loginactivate").hide();
                    inputField = $('#nickname').attr('value');
                    inputField2 = $('#passwort').attr('value');
                    art = "login";
                    //alert(art);
                        $.ajax({
                            type: "POST",
                            url: "startseite/validate.php",
                            cache: false,
                            data: { nickname: inputField, passwort: inputField2, art: art},
                            success: function(data){
                                //alert(data);
                                if(data=='ok'){
                                    $("#loginmoment").hide();
                                    //alert("Login erfolgreich!");
                                    document.location='intern/startseite.php';
                                    $("#loginsuccess").show("slow");
                                }
                                else if(data=='nichtAktiviert'){
                                    $("#loginmoment").hide();
                                    //alert("Account nicht aktiviert");
                                    $("#loginactivate").show("slow");
                                }
                                else{
                                    $("#loginmoment").hide();
                                    $("#loginerror").show("slow");
                                }
                            }   
                        });
                    return false;
                });
                // +++++++++++++++++++++++
                // +++++ REGIS CHECK +++++
                // +++++++++++++++++++++++
                $("#registrierung").submit(function(){
                    $("#regmoment").show();
                    $("#regerror").hide();
                    $("#regsuccess").hide();
                    $("#regerrorPwUngleich").hide();
                    $("#regerrorKeinEintrag").hide();
                    inputField = $('#regname').attr('value');
                    inputField2 = $('#email').attr('value');
                    inputField3 = $('#passwort1').attr('value');
                    inputField4 = $('#passwort2').attr('value');
                    art = "reg";
                    //alert(art);
                       $.ajax({
                            type: "POST",
                            url: "startseite/validate.php",
                            cache: false,
                            data: { nickname: inputField, email: inputField2, passwort1: inputField3, passwort2: inputField4, art: art},
                            success: function(data){
                                //alert(data);
                                if(data=='eintragOk'){
                                    $("#regmoment").hide();
                                    //alert("Registrierung erfolgreich! Sie k&ouml;nnen sich jetzt einloggen");
                                    $("#regsuccess").show("slow");
                                }
                                else if(data=='eintragNichtOk'){
                                    $("#regmoment").hide();
                                    //alert("Das Anlegens eines neuen Benutzers schlug fehl! Bitte wenden Sie sich an den Support");
                                    $("#regerrorKeinEintrag").show("slow");
                                }
                                else if(data=='pwUngleich'){
                                    $("#regmoment").hide();
                                    //alert("Die Passwörter stimmen nicht überein!");
                                    $("#regerrorPwUngleich").show("slow");
                                }
                                else{
                                    $("#regmoment").hide();
                                    $("#regerror").show("slow");
                                }
                            }
                        });
                    return false;
                });
            });