<!DOCTYPE html>
<html>
	<head>
		<!-- Metadaten einfuegen -->
		<meta charset="UTF-8"/>
		<meta name="description" content="Dies ist ein Blog von mehreren Menschen" />
		<meta name="author" content="Markus Allwang" />
		<meta name="keywords" lang="de" content="Blog, DHBW" />
		<meta name="date" content="2012-10-24" />
		<title>Unser Blog - Startseite</title>
		<!-- JavaScript einbinden -->
		<script type="text/JavaScript" src="http://www.unser-source.co.de/blogdateien/pruef.js"></script>
		<script type="text/javascript" src="http://www.unser-source.co.de/blogdateien/jQuery.js"></script>
		<!-- Funktion zum Ausklappen -->
		<script type="text/javascript">
			$(function(){
				$("neuesPasswort").hide();
				$("h5 a").click(function(){
						$("neuesPasswort").show();
						$("h5 a").hide();
					       });
			});
		</script>
		<!-- Icon einbinden -->
		<link rel="icon" type="image/x-icon" href="http://www.unser-source.co.de/blogdateien/mail.ico">
		<!-- StyleSheet einbinden -->
		<link rel="stylesheet" type="text/css" href="http://www.unser-source.co.de/blogdateien/modern.css"/>
	</head>

	<body>
		<div align="center">
			<div>
				<!-- Ueberschrift -->
				<div>
					<h2 class="UEschrift"> HERZLICH WILLKOMMEN IN UNSEREM BLOG </h2>
				</div>