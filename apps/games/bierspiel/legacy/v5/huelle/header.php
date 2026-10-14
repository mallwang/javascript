<?php include 'sessionpruefung.php'; include 'connect.php'; include '../funktionen/funktionen.php';
// aktueller User
$aktUser = $_SESSION['nickname'];
// Bargeld abfragen und umwandeln
$bargeld = anfrage("SELECT bargeld FROM firmendaten WHERE user_id=(SELECT id FROM user WHERE nickname='$aktUser')");
$bargeld = decimalUmwandeln($bargeld[0]);
// Kontostand abfragen und umwandeln
$festgeld = anfrage("SELECT festgeld FROM firmendaten WHERE user_id=(SELECT id FROM user WHERE nickname='$aktUser')");
$festgeld = decimalUmwandeln($festgeld[0]);
// Kredit abfragen und umwandeln
$kredit = anfrage("SELECT kredit FROM firmendaten WHERE user_id=(SELECT id FROM user WHERE nickname='$aktUser')");
$kredit = decimalUmwandeln($kredit[0]);
// Ausgabe aller Userinfos
echo "Aktueller User: <b>" . $aktUser . "</b>  /  Bargeld: <b>" . $bargeld . "</b>  /  Festgeld: <b>" . $festgeld . 
		"</b>  /  Kredit: <b>" . $kredit . "</b>";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head><script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script>
        <title><?php echo $title;?></title>
        
        <meta charset="utf-8">
        <meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, maximum-scale=1">
        <meta name="description" content="Metro UI CSS">
        <meta name="author" content="Sergey Pimenov">
        <meta name="keywords" content="windows 8, modern style, Metro UI, style, modern, css, framework">
    
        <link href="../css/modern.css" rel="stylesheet">
        <link href="../css/modern-responsive.css" rel="stylesheet">
        <link href="../css/site.css" rel="stylesheet" type="text/css">
            
        <!--    
        <link href="http://share.pluso.ru/pluso.css" type="text/css" rel="stylesheet">
        
        <script src="http://share.pluso.ru/pluso.js" charset="UTF-8"></script>
        <script src="http://share.pluso.ru/counter.php?u=http%3A%2F%2Fmetroui.org.ua%2F%23&amp;k=PLUSO_KEY" charset="UTF-8"></script>
        <script src="http://share.pluso.ru/ping.php?t=show&amp;u=http%3A%2F%2Fmetroui.org.ua%2F%23&amp;ref=&amp;w=1920&amp;h=1080" charset="UTF-8"></script>
        -->
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
        <script src="../js/jquery-1.8.2.min.js"></script>
        <script src="../js/google-analytics.js"></script>
        <script src="../js/github.info.js"></script>
        <script src="../js/dropdown.js"></script>
        <script src="../js/funktionen.js"></script>
    </head>
    
    <body class="modern-ui">
        <div class="page">
            <!--<script type="text/javascript">
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
            </script>-->
    
        <div class="navigation-bar">
            <div class="navigation-bar-inner">
                <span class="menu-pull"></span>
        
                <div class="brand">
                    <a href="../intern/startseite.php" title="Home"><span class="metro-ui-logo place-left"></span></a>
                    <span class="name"><?php echo $title;?></span>
                </div>
        
                <ul>
                    <li><a href="../intern/startseite.php">Home</a></li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Die Firma</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../firma/firma_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="../firma/grundstuecke.php">Grundst&uuml;cke</a></li>
                            <li><a href="../firma/arbeitnehmer.php">Arbeitnehmer</a></li>
                            <li><a href="#">Punkt 3</a></li>
                            <li><a href="#">Punkt 4</a></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../firma/firma_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Stadt</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../stadt/stadt_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Punkt 1</a></li>
                            <li><a href="#">Punkt 2</a></li>
                            <li><a href="#">Punkt 3</a></li>
                            <li><a href="#">Punkt 4</a></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../stadt/stadt_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Finanzen</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../finanzen/finanzen_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Einnahmen</a></li>
                            <li><a href="#">Ausgaben</a></li>
                            <li><a href="#">Bankkonten</a></li>
                            <li><a href="#">Punkt 4</a></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../bank/bank_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Markt</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../markt/markt_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="../markt/angebote.php">Angebote</a></li>
                            <li><a href="../markt/neuesAngebot.php">neues Angebot</a></li>
                            <li><a href="#">Punkt 3</a></li>
                            <li><a href="#">Punkt 4</a></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../markt/markt_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Arbeitsmarkt</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../arbeitsmarkt/arbeitsmarkt_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="../arbeitsmarkt/arbeitsmarkt.php">Arbeitsmarkt</a></li>
                            <li><a href="../arbeitsmarkt/hilfsarbeitermarkt.php">Hilfskr&auml;fte</a></li>
                            <li><a href="../arbeitsmarkt/ausbildungsmarkt.php">Ausbildungsmarkt</a></li>
                            <li><a href="../arbeitsmarkt/arbeitnehmersuche.php">Arbeitnehmersuche</a></li>
                            <li><a href="../arbeitsmarkt/verarbeitung.php">Verarbeitung</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../arbeitsmarkt/arbeitsmarkt_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Vertr&auml;ge</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../vertraege/vertraege_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Punkt 1</a></li>
                            <li><a href="#">Punkt 2</a></li>
                            <li><a href="#">Punkt 3</a></li>
                            <li><a href="#">Punkt 4</a></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../vertraege/vertraege_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Nachrichten</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../nachricht/neue_nachricht.php">Neue Nachricht</a></li>
                            <li class="divider"></li>
                            <li><a href="../nachricht/posteingang.php">Posteingang</a></li>
                            <li><a href="../nachricht/postausgang.php">Postausgang</a></li>
                            <li><a href="../nachricht/freundesliste.php">Freundesliste</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../nachricht/nachrichten_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Statistiken</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../statistiken/statistiken_uebersicht.php">&Uuml;bersicht</a></li>
                            <li class="divider"></li>
                            <li><a href="../statistiken/useruebersicht.php?a=0&b=10&suche=nickname&richtung=ASC">User&uuml;bersicht</a></li>
                            <li><a href="#">Punkt 5</a></li>
                            <li><a href="#">Punkt 6</a></li>
                            <li><a href="#">Punkt 7</a></li>
                            <li><a href="#">Punkt 8</a></li>
                            <li class="divider"></li>
                            <li><a href="../statistiken/statistiken_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Profil</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="../profil/persoenliche_daten.php">Pers&ouml;nliche Daten</a></li>
                            <li class="divider"></li>
                            <li><a href="#">E-Mail &auml;ndern</a></li>
                            <li><a href="#">Passwort &auml;ndern</a></li>
                            <li class="divider"></li>
                            <li><a href="../profil/profil_infos_hilfe.php">Infos & Hilfe</a></li>
                        </ul>
                    </li>
                    
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Kontakt</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="mailto:MAIL_CONTACT@example.com">E-Mail</a></li>
                            <li><a href="../kontakt/support_ticket.php">Support Ticket</a></li>
                        </ul>
                    </li>
        
                    <li><a href="../intern/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        <div id="layout">
            <?php include 'firmenpruefung.php';?>