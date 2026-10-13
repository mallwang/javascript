<?php include 'analyticstracking.php'; include "counter.php";?>
<!DOCTYPE HTML>
<html>
	<head>
		<!-- Metadaten -->
		<title><?php echo $title;?></title>
		<meta name="author" content="Markus Allwang"/>
		<meta name="keywords" content="brain, gehirn"/>
		<link rel="icon" href="img/logo.png" type="image/ico">
		
		<!-- Einbinden von css- und js-files -->
		<link href="css/bootstrap.css" rel="stylesheet"/>
		<link href="css/bootstrap-responsive.css" rel="stylesheet"/>
		<script type="text/javascript" src="js/jquery-1.9.0.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script src="js/registrierung.js"></script>
		<script src="js/login.js"></script>
		<script src="js/pwNeu.js"></script>
		<script src="js/bestNeu.js"></script>
	</head>
	<body>
			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	                    	<span class="icon-bar"></span>
	                    	<span class="icon-bar"></span>
	                    	<span class="icon-bar"></span>
	                  	</a>
	                  	<a class="brand" href="/"><img src="img/logo.png" class="img-circle"> i-use-my-brain</a>
	                  	<ul class="nav">
	                      	<li><a href="/">Home</a></li>
	                    </ul>
	                  	<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav pull-right">
								<li><a href="/Werbung">Werbung</a></li>
								<li class="divider-vertical"></li>
								<li class="dropdown" id="user">
								<?php
									if (isset($_SESSION['nickname'])){
									?>
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
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