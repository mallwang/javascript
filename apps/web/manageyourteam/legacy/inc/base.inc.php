<?php
require_once('inc/DB.php');
	
// Platzhalter initialisieren
global $base;
$base = array(
	"title" => "",
	"template" => "",
	"meta" => "",
	"link" => "",
	"css" => "",
	"js" => "",
	"nav" => "",
	"msg" => "",
	"sidebar" => "",
	"main" => "",
	"clubinfo" => "",
	"js2" => "",
	"footer" => "",
	"tag_start" => "",
	"tag_end" => "");
	
// Platzhalter füllen
$base["title"] = 	'ManageYourTeam';
$base["template"] =	'templates/main.htm'; 
$base["meta"] =		'<meta http-equiv="content-type" content="text/html; charset=utf-8" />'.
					'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'.
					'<meta name="author" content="Markus Allwang" />'.
					'<meta name="keywords" content="Fussball, Verein, Verwaltung, Team, Soccer, Manage" />'.
					'<meta name="Title" content="ManageYourTeam - Die kostenlose Vereinsverwaltung im Internet">'.
					'<meta name="robots" content="index, nofollow" />'.
					'<meta name="Description" content="ManageYourTeam ist eine kostenlose Webanwendung zur Verwaltung eines Fussballvereins. Erstellen Sie Ihren Fussballverein und Verwalten Sie Trainer, Spieler, Anwesenheiten sowie Aufstellungen Online." lang="de">';
$base["link"] = 	'<link rel="icon" href="../../../../img/logo.png" type="image/ico">';
$base["css"] = 		'<link rel="stylesheet" href="../../../../css/metro-bootstrap.css">'.
					'<link rel="stylesheet" href="../../../../css/metro-bootstrap-responsive.min.css">'. 
					'<link rel="stylesheet" href="../../../../css/iconFont.min.css">'.
					'<link rel="stylesheet" href="../../../../css/fullcalendar.print.css">'.
					'<link rel="stylesheet" href="../../../../css/fullcalendar.min.css">';
$base["js"] = 		'<script type="text/javascript" src="../../../../js/jquery.min.js"></script>'.
					'<script type="text/javascript" src="../../../../js/jquery.mousewheel.min.js"></script>'.
					'<script type="text/javascript" src="../../../../js/jquery.widget.min.js"></script>'.
					'<script type="text/javascript" src="../../../../js/metro.min.js"></script>'.
					'<script type="text/javascript" src="../../../../js/metro-dialog.min.js"></script>'.	
					'<script type="text/javascript" src="../../../../js/metro-dropdown.min.js"></script>'.
					'<script type="text/javascript" src="../../../../js/metro-hint.min.js"></script>'.
					'<script type="text/javascript" src="../../../../js/liveSearch.js"></script>'.
					'<script type="text/javascript" src="../../../../js/login.js"></script>'.
					'<script type="text/javascript" src="../../../../js/register.js"></script>';
$base["nav"] = 		'<nav class="navigation-bar green">
						<nav class="navigation-bar-content green">
							<span class="element" style="z-index: 10;"><img width="100px" height="100px" src="../../../../img/spieler.png" style=" margin-top: -5px;"></span>
							<a class="element brand" href="\"><div style="font-weight: bold;">MANAGE YOUR TEAM</div></a>
							<!-- LOGIN ELEMENTE -->
							<div class="element input-element place-right">
								<form id="login">
									<div class="input-control text span3">
										<input id="email" type="text" placeholder="E-Mail" required="required" style="font-size: 14px;">
									</div>
									<div class="input-control text span2">
										<input id="passwort" type="password" placeholder="Passwort" required="required" style="font-size: 14px;">
									</div>
									<div class="input-control text span1" style="padding-bottom: 0px; margin-bottom: 13px; height: 0px;">
										<!-- LOGIN BUTTON -->
										<button id="btnSubmit" type="submit" class="place-right" style="margin-top: -3px;"> Login
											<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />
										</button>
									</div>
									<div id="alert" style="display: none; padding-top: 10px;"></div>
								</form>
							</div>
						</nav>
					</nav>
					<nav class="navigation-bar green">
    					<nav class="navigation-bar-content green">
							<span class="element"><img width="100px" height="100px" src="" style=" margin-top: -5px;"></span>
						</nav>
					</nav>';
$base["msg"] =		'';
$base["sidebar"] = 	'<div class="span4">'.
						'<div>'.
							'<form>'.
								'<fieldset>'.
									'<legend>Suchen Sie hier nach einem Verein</legend>'.
									'<div class="input-control text" data-role="input-control">'.
										'<input id="vereinLiveSearch" type="text" placeholder="Vereinsname" onkeyup="liveSearch(this.value)">'.
									'</div>'.
									'<img id="waitLiveSearch" src="../../../../img/loading.gif" style="display: none;" />'.
									'<br>'.
									'<div id="placeholderLiveSearch"></div>'.
								'</fieldset>'.
							'</form>'.
						'</div>'.
					'</div>';
$base["main"] = 	'<div class="span6">
						<div align="left" class="panel-content fg-dark nlp nrp">
							<img src="../../../../img/spieler.png" class="place-left margin10 size2">
							<h3>MANAGE YOUR TEAM</h3>
							ManageYourTeam ist eine kostenlose Webanwendung zur Verwaltung eines Fussballvereins. Erstellen Sie Ihren Fussballverein und verwalten Sie Trainer, Spieler, Anwesenheiten, Aufstellungen und vieles mehr online.
						</div>
					</div>';
$base["clubinfo"] = '<div class="span4">
						<div>
							<!-- Anzeige eines Hinweises zur Registrierung -->
							<div>
								<form id="register">
									<fieldset>
										<legend>Registrieren Sie sich noch heute!</legend>
										<div class="input-control text" data-role="input-control">
											<input id="vornameRegister" type="text" placeholder="Vorname*" autofocus="autofocus" required="required">
										</div>
										<div class="input-control text" data-role="input-control">
											<input id="nachnameRegister" type="text" placeholder="Nachname*" required="required">
										</div>
										<div class="input-control text" data-role="input-control">
											<input id="vereinRegister" type="text" placeholder="Vereinsname*" required="required">
										</div>
										<div class="input-control text" data-role="input-control">
											<input id="emailRegister" type="email" placeholder="E-Mail Adresse*" required="required">
										</div>
										<div align="left" class="input-control password" data-role="input-control">
											<input id="passwortRegister" type="password" placeholder="Passwort*" required="required">
											<span style="font-size: 12px;">
												<br>
												*Pflichtfeld
												<br>
											</span>
										</div>
										<br>
										<br>
										<input id="btnRegister" class="inverse" type="submit" value="Registrieren">
											<img id="waitRegister" src="../../../../img/loading.gif" style="display: none;" />
										<br>
										<div id="alertRegister" style="display: none; font-size: 14px; margin-top: 8px;"></div>
									</fieldset>
								</form>
							</div>
						</div>
					</div>';
/*
$base["footer"] = 	'<div align="center" class="footer bg-green">'.
						'<div class="container tertiary-text bg-green fg-white" style="padding: 10px">'.
						'<a href="#impressum.php" class="fg-yellow">IMPRESSUM</a> | 2014 © by Manage Your Team | <a href="#kontakt.php" class="fg-yellow">KONTAKT</a> | <a href="#problems.php" class="fg-yellow">FEHLER / TODO</a>'.
						'</div>'.
					'</div>';
*/

// verwendete tags im template
$base["tag_start"] = '<@';
$base["tag_end"] = '@>';

function get_file_as_string($filename){		
	$tmprslt = file($filename);
	$tmprslt = implode('',$tmprslt);
	return $tmprslt;
}  
?>