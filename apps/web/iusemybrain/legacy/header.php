<?php session_start(); include 'analyticstracking.php'; include "counter.php";?>
<!DOCTYPE HTML>
<html>
<head>
<!-- Metadaten -->
<title><?php echo $title;?></title>
<meta name="author" content="Markus Allwang" />
<meta name="keywords" content="brain, gehirn" />
<link rel="icon" href="img/logo.png" type="image/ico">
<!-- Einbinden von css- und js-files -->
<link href="css/bootstrap.css" rel="stylesheet" />
<link href="css/bootstrap-responsive.css" rel="stylesheet" />
<!-- <link href="css/docs.css" rel="stylesheet" /> -->
<link href="highlighter/prettify.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.10.0.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
<script src="js/registrierung.js"></script>
<script src="js/login.js"></script>
<script src="js/pwNeu.js"></script>
<script src="js/bestNeu.js"></script>
<script src="js/support.js"></script>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".navbar-responsive-collapse"> <span class="icon-bar"></span>
					<span class="icon-bar"></span> <span class="icon-bar"></span>
				</a> <a class="brand" href="/"><img src="img/logo.png"
					class="img-circle"> i-use-my-brain</a>
				<ul class="nav">
					<li><a href="/">Home</a></li>
					<li><a href="/Neuer_Dateneintrag">Neuer Dateneintrag</a></li>
					<?php if ($_SESSION["nickname"] == "ADMIN_EMAIL") {?>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Programmierung <b class="caret"></b>
					</a>
						<ul class="dropdown-menu">
							<li class="dropdown-submenu"><a tabindex="-1">Android</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="/Android">Diverses</a>
									</li>
								</ul>
							</li>
							<li class="dropdown-submenu"><a tabindex="-1">PHP</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="/PHP">Diverses</a></li>
								</ul>
							</li>
							<li class="dropdown-submenu"><a tabindex="-1">JavaScript</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="/JavaScript">Diverses</a>
									</li>
								</ul>
							</li>
							<!-- 
							<li class="dropdown-submenu"><a tabindex="-1">AJAX</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="/Ajax">Diverses</a></li>
								</ul>
							</li>
							-->
						</ul>
					</li>
					<?php }?>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Denksport <b class="caret"></b>
					</a>
						<ul class="dropdown-menu">
							<li><a href="/Memory">Memory</a></li>
						</ul>
					</li>
				</ul>
				<div class="nav-collapse collapse navbar-responsive-collapse">
					<!--
	                    	<form class="navbar-search pull-left" action="">
	                      		<input type="text" class="search-query span2" placeholder="Search">
	                    	</form>
	                    	-->
					<ul class="nav pull-right">
					<li>
						<form style="padding-top: 6px; margin-bottom: 0px;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="TMWCPUTJGY7E8">
							<input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Mit Paypal spenden">
							<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
						</form>
					</li>
						<li><a href="/Bibliothek">Bibliothek</a></li>
						<li><a href="/Download">Download</a></li>
						<li><a href="/Statistik">Statistik</a></li>
						<li><a href="/Werbung">Werbung</a></li>
						<li class="divider-vertical"></li>
						<li class="dropdown" id="user"><?php
						if (isset($_SESSION['nickname'])){
							?> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Angemeldet
								unter:
								<div style="display: inline;" id="eMail1">
									<?php echo $_SESSION['nickname'];?>
								</div> <b class="caret"></b>
						</a>
							<ul class="dropdown-menu">
								<li><a href="/Profil">Pers&ouml;nliche Daten</a></li>
								<li class="divider"></li>
								<li><a href="/Logout">Logout</a></li>
							</ul> <?php	} else{?>
							<button onclick="window.location.href='/Login';" type="submit"
								class="btn">Login</button>
						
						<li><a href="/Registrieren">Registrieren</a>
						</li>
						<?php }	?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="content">