<?php session_start(); include("connect.php"); include("functions.php"); include("sessionpruefung.php");
// Variablen deklarieren und initialisieren
$name = $_SESSION['nickname'];
$name_gross = strtoupper($name);


?>
<html>
	<head>
		<!-- Metadaten einfuegen -->
		<meta charset="UTF-8"/>
		<meta name="description" content="Dies ist ein Blog von mehreren Menschen" />
		<meta name="author" content="Markus Allwang" />
		<meta name="keywords" lang="de" content="Blog, DHBW" />
		<meta name="date" content="2012-10-24" />
		<title> <?php echo "$title";?></title>
		<!-- JavaScript einbinden -->
		<script type="text/JavaScript" src="pruef.js"></script>
		<script type="text/javascript" src="jQuery.js"></script>
		<script type="text/javascript">
			$(function(){
				$("z").hide();
				$("zusatzBereich").hide();
				$("ant").hide();
				$("x").hide();
				$("pwOeffen")
				$("y a").click(function()
					       {
						event.preventDefault();
						$(this).hide();
						$(this).parent("y").next("x").show();
						$(this).parent("y").parent("div").parent("div").next("ant").show("normal");
						$("x a").show();
					       });
				$("x a").click(function()
					       {
						event.preventDefault();
						$(this).hide();
						$(this).parent("x").parent("div").parent("div").next("ant").slideUp("normal");
						$("y a").show();
						
					       });
				$("h5 a").click(function(){
					
					
					
				});
				
				$("abc a").click(function()
						{
						event.preventDefault();
						$("z").hide();
						$("a").show();
						$(this).hide();
						$(this).parent("abc").next("z").show();
						$("zusatzBereich").slideUp("normal");
						$(this).parent("abc").parent("div").parent("div").next("zusatzBereich").slideDown("normal");
						$("#5").get(0).focus();
						$(this).parent("abc").parent("div").next("div").parent("li").parent("ul").get(0).focus();
						$("x a").hide();
						$("m a").hide();
						$("x a").show();
						});
				$("z a").click(function()
						{
						event.preventDefault();
						$("abc").show();
						$("z").hide();
						$("zusatzBereich").slideUp("normal");
						$(this).parent("z").next("abc").show();
						$(this).hide();
						$("x a").hide();
						$("abc a").show();
						});
		    });
			function deleteMe(id, blog_id){
					var answer = confirm("Moechtest du deinen Eintrag wirklick loeschen?"); 
					if (answer == true)
					{
						window.location.href='delete_comment_confirm.php?comment_id=' + id;
					}
					else{
						nothing;
					}
				}
			
		</script>
	</head>
	<!-- Icon einbinden -->
	<link rel="icon" href="security.ico" type="image/x-icon">
	<!-- StyleSheet einbinden -->
	<link href="modern.css" rel="stylesheet" type="text/css" />
	
	<body>
	<div class="horizontal-menu zentral">
		<ul class="fg-color-white">
			<li>
				<a href="home.php?beginn=0">Home</a>
			</li>
			<li>
				<a href="blog.php?beginn=0">Blog</a>
			</li>
			<li>
				<a href="profil.php">Profil</a>
			</li>
			<li>
				<a href="suche.php">Suche</a>
			</li>
			<li>
				<a href="impressum.php">Impressum</a>
			</li>
			<li>
				<a class='ueberSchwarz'>Kontakt</a>
				<ul>
					<li><a href='#'>E-Mail: wi11116@lehre.dhbw-stuttgart.de </a></li>
					<li><a href='#'>Support-Ticket</a></li>
				</ul>
			</li>
			<li class="sub-menu place-right">
				<a class='ueberSchwarz'>Optionen</a>
				<ul>
					<li><a href="email_aendern.php">E-Mail &auml;ndern</a></li>
					<li><a href="pw_aendern.php">Passwort &auml;ndern</a></li>
					<li class="divider"></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
        </div>
        
	<!--
	<tr>
		<h1 align="center"><?php echo "HALLO $name_gross";?></h1>
                <?php
		// Variablen für die Klasse der Zeilen deklarieren und initialisieren
                $class_home="nav";
                $class_profil="nav";
                $class_blog="nav";
                $class_suche="nav";
                $class_logout="nav";
		// Zuweisung der Klasse je nach aktuellem Aufenthaltsort 
                if ($title=="Unser Blog"){$class_home=" aktuell";}
                elseif($title=="Profil"){$class_profil=" aktuell";}
                elseif($title=="Deine Blogeintr&auml;ge"){$class_blog=" aktuell";}
                elseif($title=="Suche"){$class_suche=" aktuell";}
		elseif($title=="Logout"){$class_logout=" aktuell";}
		?>
		<div id="header">
			<table align="center" width="1200">
				<tr Valign="top">
					<td class="menuebutton"><a href='geschuetzterBereich.php'><img <?php echo "class='$class_home'";?> title="Home" src='images/menu_home.jpg' alt='Home-Button'/></a></td>
					<td class="menuebutton"><a href='blog.php'><img <?php echo "class='$class_blog'";?> title="Blog" src='images/menu_blog.jpg' alt='Blog-Button'/></a></td>
					<td class="menuebutton"><a href='profil.php'><img <?php echo "class='$class_profil'";?> title="Profil" src='images/menu_profil.jpg' alt='Profil-Button'/></a></td>
					<td class="menuebutton"><a href='suche.php'><img <?php echo "class='$class_suche'";?> title="Suche" src='images/menu_suche.jpg' alt='Suche-Button'/></a></td>
					<td class="menuebutton"><a href='logout.php'><img <?php echo "class='$class_logout'";?> title="Logout" src='images/logout.jpg' alt='Logout-Button'/></a></td>
				</tr>
			</table>
		</div>
	</tr> -->
	<div align='center'><table align="center" width="1200">
                <tr>
			<td>