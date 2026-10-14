<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head><script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script>
        <title>DAS BIERSPIEL</title>
        
        <meta charset="utf-8">
        <meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, maximum-scale=1">
        <meta name="description" content="Metro UI CSS">
        <meta name="author" content="Sergey Pimenov">
        <meta name="keywords" content="windows 8, modern style, Metro UI, style, modern, css, framework">
    
        <link href="css/modern.css" rel="stylesheet">
        <link href="css/modern-responsive.css" rel="stylesheet">
        <link href="css/site.css" rel="stylesheet">
    
        <!--<link href="css/modern.css" rel="stylesheet">
        <link href="css/modern-responsive.css" rel="stylesheet">
        <link href="css/site.css" rel="stylesheet" type="text/css">-->
        <link href="http://share.pluso.ru/pluso.css" type="text/css" rel="stylesheet">
        
        <script src="http://share.pluso.ru/pluso.js" charset="UTF-8"></script>
        <script src="http://share.pluso.ru/counter.php?u=http%3A%2F%2Fmetroui.org.ua%2F%23&amp;k=PLUSO_KEY" charset="UTF-8"></script>
        <script src="http://share.pluso.ru/ping.php?t=show&amp;u=http%3A%2F%2Fmetroui.org.ua%2F%23&amp;ref=&amp;w=1920&amp;h=1080" charset="UTF-8"></script>
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
        
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="js/google-analytics.js"></script>
        <script src="js/github.info.js"></script>
        <script src="js/dropdown.js"></script>
        
        <!--<script src="js/jquery-1.8.2.min.js"></script>
        <script src="js/google-analytics.js"></script>
        <script src="js/github.info.js"></script>
        <script src="js/dropdown.js"></script>-->
        
        <script src="js/index.js"></script>
    </head>
    
    <body class="modern-ui">
        <div class="page">
            <script type="text/javascript">
                function getWindowsSize(){
                    $("#sizeX").html('W: '+$(window).width());
                    $("#sizeY").html('H: '+$(window).height());
                }
                $(function(){
                    getWindowsSize();
                    $(window).resize(function(){
                    getWindowsSize()
                    });
                })
            </script>
    
        <div class="navigation-bar">
            <div class="navigation-bar-inner">
                <span class="menu-pull"></span>
        
                <div class="brand">
                    <a href="index.php" title="Home"><span class="metro-ui-logo place-left"></span></a>
                    <span class="name">DAS BIERSPIEL</span>
                </div>
        <div id="result"></div>
        <div id="userdata"></div>
                <ul>
                    <li><a href="index.php">Home</a></li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Das Spiel</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="startseite/bierspiel_aufbau.php">Der Aufbau</a></li>
                            <li><a href="startseite/screenshots.php">Screenshots</a></li>
                        </ul>
                    </li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Kontakt</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="mailto:MAIL_CONTACT@example.com">E-Mail</a></li>
                            <li><a href="startseite/support_ticket.php">Support Ticket</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <script src="js/ausklappen.js"></script>
        <div class="page-region">
            
            <div class="page-region-content">
                <div class="grid">
                    <!---------------------------------------------------------------------------------------------->
                    <!----------------------------------------- Spalte 1 ------------------------------------------->
                    <!---------------------------------------------------------------------------------------------->
                    <div class="row">
                        <!------------------------------------------------------------------------------------------>
                        <!---------------------- Registrier-Hinweis und Bier Logo ---------------------------------->
                        <!------------------------------------------------------------------------------------------>
                        <div class="span8">
                            <div class="hero-unit">
                                
                                <img src="images/bier.png" class="place-right" style="width: 210px;"/>
                                <div style="">
                                    <h1 class="fg-color-blue">DAS BIERSPIEL</h1>
                                    <h2>Registriere dich jetzt und gr&uuml;nde dein eigenes Bierimperium!</h2>
                                </div>
                                <br><br><br><br><br><br>
                            </div>
                        </div>
                        <script src="js/pagecontrol.js"></script>
                         <div class="span4 page-control" data-role="page-control">
                            <!--Tabs-->
                            <ul>
                                <li class="active"><a href="#frameLogin">Login</a></li>
                                <li><a href="#frameRegistrierung">Registrierung</a></li>
                            </ul>
                            <!--Tabs content-->
                            <div class="frames" height="200px">
                                <div class="frame active bg-color-yellow" id="frameLogin">
                                    <!------------------------------------------------------------------------------------------>
                                    <!--------------------------------------Login-Bereich--------------------------------------->
                                    <!------------------------------------------------------------------------------------------>
                                    <img src="images/simple.png" class="place-right" style="margin: 10px;"><br>
                                    <h2 class="fg-color-white">&nbsp;Login</h2>
                                    <!-- Hinweise -->
                                    <div class="fg-color-orange" id="loginmoment" style="display: none;"><b>...pr&uuml;ft...</b></div>
                                    <div class="fg-color-green" id="loginsuccess" style="display: none;"><b>Login erfolgreich</b></div>
                                    <div class="fg-color-red" id="loginerror" style="display: none;"><b>Benutzername oder Passwort falsch</b></div>
                                    <div class="fg-color-red" id="loginactivate" style="display: none;"><b>Dieser Account ist noch nicht aktiviert!</b></div>
                                    <!-- Formular -->
                                    <form name="login" method="post" action="" id="login">
                                        <input class="span3_marg" autocomplete="on" placeholder="Nickname oder E-Mail Adresse" id="nickname" name="nickname" type="text" size="30" maxlength="60"><br>
                                        <input class="span3_marg" placeholder="Passwort" name="passwort" id="passwort" type="password" size="30" maxlength="20"><br><br>
                                        <div class="margin_top" align="center">
                                            <input type="button" onClick="parent.location='pw_vergessen.php'" value="Passwort vergessen">
                                            <button class="bg-color-grayDark fg-color-white" name="submit" type="submit">Login</button><br>
                                        </div>
                                    </form>
                                </div>
                                <div class="frame bg-color-orange" id="frameRegistrierung">
                                    <!------------------------------------------------------------------------------------------>
                                    <!------------------------------- Registrierungs-Bereich ----------------------------------->
                                    <!------------------------------------------------------------------------------------------>
                                    <img src="images/registrieren.png" class="place-right" style="margin: 10px;"><br>
                                    <h2 class="fg-color-white">&nbsp;Registrieren</h2>
                                    <!-- Hinweise -->
                                    <div class="fg-color-orange" id="regmoment" style="display: none;"><b>...pr&uuml;ft...</b></div>
                                    <div class="fg-color-red" id="regerrorPwUngleich" style="display: none;"><b>Die Passw&ouml;rter stimmen nicht &uuml;berein!</b></div>
                                    <div class="fg-color-green" id="regsuccess" style="display: none;"><b>Registrierung erfolgreich<br> Du hast nun eine Best&auml;tigungsmail
                                                                                                            erhalten. Um dich einloggen zu k&ouml;nnen,
                                                                                                            musst du diese best&auml;tigen.</b></div>
                                    <div class="fg-color-red" id="regerrorKeinEintrag" style="display: none;"><b>Fehler beim Eintragen des neuen Users</b></div>
                                    <div class="fg-color-red" id="regerror" style="display: none;"><b>Benutzername und/oder EMail Adresse bereits vorhanden</b></div>
                                    <!-- Formular -->
                                    <form id="registrierung" name="registrierung" method="post" action="">
                                        <input class="span3_marg" autocomplete="on" placeholder="Nickname" id="regname" name="nickname" type="text" size="30" maxlength="20"><br>
                                        <input class="span3_marg" autocomplete="on" placeholder="E-Mail Adresse" id="email" name="email" type="email" size="30" maxlength="30"><br><br>
                                        <input class="span3_marg" placeholder="Passwort" name="passwort" id="passwort1" type="password" size="30" maxlength="20"><br>
                                        <input class="span3_marg" placeholder="Passwort wiederholen" name="passwort" id="passwort2" type="password" size="30" maxlength="20"><br><br>
                                        <div class="margin_top" align="center">
                                            <input class="bg-color-gray fg-color-grayDark" name="reset" type="reset" value="Zur&uuml;cksetzen">
                                            <input class="bg-color-grayDark fg-color-white" name="submit" type="submit" value="Senden"><br>
                                        </div>
                                    </form>                              
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <!---------------------------------------------------------------------------------------------->
                    <!----------------------------------------- Spalte 2 ------------------------------------------->
                    <!---------------------------------------------------------------------------------------------->
                    <!--<div class="row">
                        <br><br><br><br>
                        <!------------------------------------------------------------------------------------------>
                        <!----------------------------------- Vorschau-Bereich ------------------------------------->
                        <!------------------------------------------------------------------------------------------>
                        <!--<div class="span8 bg-color-green">
                            <img src="images/simple.png" class="place-right" style="margin: 10px;"><br>
                            <ausklapp><a href="#"><h2 class="fg-color-white">&nbsp;Vorschau</h2></a></ausklapp>
                            <form style="display: none;">
                                Das ist ein Test
                            </form>
                        </div>
                        
                    </div>-->
                </div>

                <div class="grid">
                    <!---------------------------------------------------------------------------------------------->
                    <!----------------------------------------- Spalte 3 ------------------------------------------->
                    <!---------------------------------------------------------------------------------------------->
                    <div class="row">
                       
                        <!--  unter Vorschau-Bereich -->
                        <div class="span8">
                        </div>
                        
                        <!--  unter Registrieren-Button -->
                        <div class="span4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bar">
            
        </div>
    </div>
            <div class="header-bar-inner bg-color-darken" style="padding: 10px 10px 5px; vertical-align: bottom;">
                        <p class="tertiary-text fg-color-white">2012, BIERSPIEL &copy; by <a class="fg-color-yellow" href="mailto:MAIL_CONTACT@example.com">AUTHOR_NAME</a> and <a class="fg-color-yellow" href="mailto:MAIL_CONTACT2@example.com">AUTHOR_NAME_2</a></p>
            </div>
            
            </body>
</html>