<?php include 'funktionen.php';
// aktueller User
$aktUser = $_SESSION['nickname'];
// aktuelle Firmen Id
$firma_id = $_SESSION['firmaId'];
// Finanzen abfragen
$finanzen = anfrage("SELECT konto, kredit, kreditrahmen FROM finanzen WHERE firma_id='$firma_id'");
// Kontostand umwandeln
$festgeld = $finanzen[0];
// Kredit
$kredit = $finanzen[1];
// Kreditrahmen
$kreditrahmen = $finanzen[2];
?>
<!-- <div align="center"><b><h4>geheimer Bereich!</h4></b></div> -->
<!DOCTYPE HTML>
<html>
	<head>
		<!-- Metadaten -->
		<title><?php echo $title;?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="author" content="AUTHOR_NAME"/>
		<meta name="keywords" content="bierspiel, browsergame, onlinespiel, schankimperium, schank, bier, kneipe, wirtshaus"/>
		<link rel="icon" href="/img/bier_icon.png" type="image/ico">
		<!-- Einbinden von css- und js-files -->
		<link href="/css/bootstrap.css" rel="stylesheet"/>
		<link href="/css/bootstrap-responsive.css" rel="stylesheet"/>
		<link href="/css/jvectormap-1.2.2.css" rel="stylesheet"/>
		<script type="text/javascript" src="/js/jquery-1.9.0.js"></script>
		<script type="text/javascript" src="/js/bootstrap.js"></script>
		<script type="text/javascript" src="/js/support.js"></script>
		<script type="text/javascript" src="/js/jvectormap-1.2.2.min.js"></script>
	</head>
	<body>
			<div class="navbar">
				<noscript>
					<div align="center" class="alert alert-info">
						<b>Sie haben JavaScript deaktiviert!</b><br>
						Um den vollen Leistungsumfang unseres Browsergames nutzen zu können, benötigen Sie JavaScript.<br>
						Bitte aktivieren Sie JavaScript in Ihren Browsereinstellungen und laden Sie diese Seite neu.<br>
						Vielen Dank!
					</div>
				</noscript>
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	                    	<span class="icon-bar"></span>
	                    	<span class="icon-bar"></span>
	                    	<span class="icon-bar"></span>
	                  	</a>
	                  	<a class="brand" href="/Home"><img style="height: 15px; width: 15px;" src="/img/bier_icon.png" class="img-circle"> Schankimperium</a>
			          	<div class="nav-collapse collapse navbar-responsive-collapse">
	                  		<ul class="nav">
		                      	<li class="dropdown">
	                        		<a href="" class="dropdown-toggle" data-toggle="dropdown"><div id="firmaName1"><?php echo $_SESSION['firmenname']; ?><b class="caret"></b></div></a>
	                        		<ul class="dropdown-menu">
	                          			<li class="nav-header">Geb&auml;ude</li>
	                          			<li><a href="/Firma" onclick="startLoad('firma/firmaUebersicht.php', 'firmaUebersicht')">&Uuml;bersicht</a></li>
	                          			<li class="divider"></li>
	                          			<li><a href="/Verkauf">Verkaufsgeb&auml;ude</a></li>
	                          			<!--
	                          			<li><a href="/Produktion">Produktion</a></li>
	                          			<li><a href="/Forschung">Forschung</a></li>
	                          			<li><a href="/Landwirtschaft">Landwirtschaft</a></li>
	                          			<li><a href="/Transport">Transport</a></li>
	                          			<li><a href="/Sonstige">Sonstige</a></li>
	                          			
	                          			<li><a href="/Lager">Lager</a></li>-->
	                          			<li class="divider"></li>
	                          			<li><a href="/Neues_Gebaeude">Neues Geb&auml;ude</a></li>
	                          			<!-- 
	                          			<li class="nav-header">Arbeitskr&auml;fte</li>
	                          			<li><a href="" onclick="startLoad('firma/firmaArbeitnehmer.php', 'firmaArbeitnehmer')">Arbeitnehmer</a></li>
	                          			<li class="divider"></li>
	                          			<li><a href="" onclick="startLoad('firma/firmaInfosHilfe.php', 'firmaInfosHilfe')">Infos & Hilfe</a></li>
	                          			 -->
	                        		</ul>
	                      		</li>
	                      		<li class="dropdown">
	                        		<a href="" class="dropdown-toggle" data-toggle="dropdown">Finanzen <b class="caret"></b></a>
	                        		<ul class="dropdown-menu">
	                          			<li><a href="/Finanzen">&Uuml;bersicht</a></li>
	                          			<li><a href="/Einnahmen">Einnahmen</a></li>
	                          			<li><a href="/Ausgaben">Ausgaben</a></li>
	                          			<li><a href="/Bank">Bank</a></li>
	                          			<!-- 
	                          			<li class="divider"></li>
	                          			<li><a href="" onclick="startLoad('finanzen/finanzenInfosHilfe.php', 'finanzenInfosHilfe')">Infos & Hilfe</a></li>
	                          			 -->
	                        		</ul>
	                      		</li>
	                      		<li class="dropdown">
	                        		<a href="" class="dropdown-toggle" data-toggle="dropdown">Stadt <b class="caret"></b></a>
	                        		<ul class="dropdown-menu">
	                          			<li><a href="/Stadt">&Uuml;bersicht</a></li>
	                          			<!-- 
	                          			<li class="divider"></li>
	                          			<li class="divider"></li>
	                          			<li><a href="" onclick="startLoad('stadt/stadtInfosHilfe.php', 'stadtInfosHilfe')">Infos & Hilfe</a></li>
	                          			 -->
	                        		</ul>
	                      		</li>
	                      		<li class="dropdown">
	                        		<a href="" class="dropdown-toggle" data-toggle="dropdown">Markt <b class="caret"></b></a>
	                        		<ul class="dropdown-menu">
	                          			<li><a href="/Markt">Angebote</a></li>
	                          			<!-- <li><a href="/Eigene_Angebote">Eigene Angebote</a></li>
	                          			<li><a href="" onclick="startLoad('markt/marktNeuesAngebot.php', 'marktNeuesAngebot')">Neues Angebot</a></li>
	                          			<li class="divider"></li>
	                          			<li><a href="" onclick="startLoad('markt/marktInfosHilfe.php', 'marktInfosHilfe')">Infos & Hilfe</a></li>
	                          			 -->
	                        		</ul>
	                      		</li>
	                      		<!-- 
	                      		<li class="dropdown">
	                        		<a href="" class="dropdown-toggle" data-toggle="dropdown">Arbeitsmarkt <b class="caret"></b></a>
	                        		<ul class="dropdown-menu">
	                          			<li><a href="" onclick="startLoad('arbeitsmarkt/arbeitsmarktUebersicht.php', 'arbeitsmarktUebersicht')">&Uuml;bersicht</a></li>
	                          			<li class="divider"></li>
	                          			<li><a href="" onclick="startLoad('arbeitsmarkt/arbeitsmarktArbeitskraefte.php', 'arbeitsmarktArbeitskraefte')">Arbeitskr&auml;fte</a></li>
	                          			<li><a href="" onclick="startLoad('arbeitsmarkt/arbeitsmarktHilfskraefte.php', 'arbeitsmarktHilfskraefte')">Hilfskr&auml;fte</a></li>
	                          			<li><a href="" onclick="startLoad('arbeitsmarkt/arbeitsmarktAusbildungsmarkt.php', 'arbeitsmarktAusbildungsmarkt')">Ausbildungsmarkt</a></li>
	                          			<li><a href="" onclick="startLoad('arbeitsmarkt/arbeitsmarktArbeitnehmersuche.php', 'arbeitsmarktArbeitnehmersuche')">Arbeitnehmersuche</a></li>
	                          			<li class="divider"></li>
	                          			<li><a href="" onclick="startLoad('arbeitsmarkt/arbeitsmarktInfosHilfe.php', 'arbeitsmarktInfosHilfe')">Infos & Hilfe</a></li>
	                        		</ul>
	                      		</li>
	                      		 -->
	                    	</ul>
	                    	<ul class="nav pull-right">
	                    		<li class="dropdown">
		                    		<a href="" class="dropdown-toggle" data-toggle="dropdown">Statistiken<b class="caret"></b></a>
		                    		<ul class="dropdown-menu">
		                          		<li><a href="/Onlineuser">User online</a></li>
		                          		<li class="divider"></li>
		                          		<li><a href="/Userstatistik">Userstatistiken</a></li>
		                        	</ul>
		                    	</li>
	                    		<li class="dropdown">
		                        		<a><div id="kontostand"><?php echo "Kontostand: <b>" . $festgeld . " &euro;</b>";?></div></a>
		                      	</li>
		                    	<li class="dropdown">
		                    		<a href="" class="dropdown-toggle" data-toggle="dropdown"><img style="height: 20px;" src="/img/briefumschlag.png" /><b class="caret"></b></a>
		                    		<ul class="dropdown-menu">
		                          		<li><a href="/Posteingang">Posteingang<div style="display: none;" id="anzahlMailsEingang">(0)</div></a></li>
		                          		<li><a href="/Postausgang">Postausgang</a></li>
		                        		<li class="divider"></li>
		                        		<li><a href="/Neue_Nachricht">Neue Nachricht</a></li>
		                        	</ul>
		                    	</li>
			                    <li class="dropdown">
	                        		<a href="" class="dropdown-toggle" data-toggle="dropdown"><div id="eMail1"><?php echo $aktUser; ?><b class="caret"></b></div></a>
	                        		<ul class="dropdown-menu">
	                          			<li><a href="/Profil"><i style="margin-top: 5px;" class='icon-user'></i> Pers&ouml;nliche Daten</a></li>
	                          			<li class="divider"></li>
	                          			<!-- 
	                          			<li><a href="" onclick="startLoad('profil/profilInfosHilfe.php', 'profilInfosHilfe')">Infos & Hilfe</a></li>
	                          			 -->
	                          			<li><a href="/Logout"><i style="margin-top: 5px;" class='icon-off'></i> Logout</a></li>	
									</ul>
		                      	</li>
		                      	
			          		</ul>
		                </div>
	                    
	                    
	                    <!--
	                  	<div class="nav-collapse collapse navbar-responsive-collapse">
	                    	<ul class="nav pull-right">
	                    		<li><a onclick="startLoad('startseite/statistik.php', 'statistik')">Statistik</a></li>
	                      		<li><a onclick="startLoad('startseite/werbung.php', 'werbung')">Werbung</a></li>
	                    	</ul>
	                  	</div>
	                  	-->
	                </div>
				</div>
			</div>