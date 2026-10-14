<?php $title="Login"; session_start(); include("functions.php"); include("connect.php");
// Werte von Formular übernehmen
$loginname = htmlentities($_POST["loginname"]);
$passwort = htmlentities($_POST["passwort"]);
// Prüfung ob alle Felder ausgefüllt sind (mit JavaScript erledigt)
//if($loginname == "" OR $passwort == ""){
//	$meldung = "
//		<div align='center'>
//			  <table class='farbeTable'><br><br>
//				    <td align='center'>
//					      <div>
//							<h3 class='farbe_grau'><br>Eingabefehler. Bitte alle Felder korrekt ausf&uuml;llen!</h3><br><br>
//							<a href='/www/index.php'>Zur&uuml;ck</a><br><br>
//					      </div>
//				    </td>
//			  </table> 
//		</div>
//	";
//}// end if
//else{
	// Wenn E-Mail Adresse eingegeben wurde
	if (check_email($loginname)==true){
		// Eingegebene E-Mail Adresse in Kleinbuchstaben umwandeln
		// und suche nach bereits vorhandenen Einträgen in DB
		$loginname = strtolower($_POST["loginname"]);
		$abfrage = getProperty('name', 'passwort', 'user', 'email', $loginname);
	}// end if
	// Wenn Nickname eingegeben wurde
	else{
		// Suche nach bereits vorhandenen Einträgen in DB
		$abfrage = getProperty('name', 'passwort', 'user', 'name', $loginname);
	}// end else
	// Variable name zuweisen
	$name = $abfrage[0];
	// DB-Abfrage nach Schlüssel
	$aktiviert = getProperty('schluessel', 'user', 'name', $name);
	$schluessel = $aktiviert[0];
	// Wenn Account schon aktiviert wurde
	if($schluessel == ""){
		// Passwort md5-verschlüsseln
		$passwort1 = md5($passwort);
		// Wenn Passwort übereinstimmt
		if($abfrage[1] == $passwort1){
			// Session 'nickname' festlegen
			$_SESSION['nickname'] = $name;
			// automatische weiterleitung zum BLOG-BEREICH
			echo "<meta http-equiv='refresh' content='0; URL=blog.php?beginn=0'>"; exit;
		}// end if
		// Wenn Passwort nicht übereinstimmt
		else{
			$meldung = "
				<div align='center'>
					<table class='farbeTable'><br><br>
						<td align='center'>
							<div>
								<h3 class='farbe_grau'><br>Benutzername und/oder Passwort waren falsch.</h3><br><br>
								<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
							</div>
						</td>
					</table> 
				</div>
			";
		}// end else
	}// end if
	// Wenn Account noch nicht aktiviert wurde
	else{
		$meldung = "
				<div align='center'>
					<table class='farbeTable'><br><br>
						<td align='center'>
							<div>
								<h3 class='farbe_grau'><br>Du hast in Anschluss an deine Registrierung eine Best&auml;tigungsmail erhalten.<br>
								Bitte best&auml;tige diese zuerst.</h3><br><br>
								<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
							</div>
						</td>
					</table> 
				</div>
			";
	}// end else
//}// end else
mysql_close();
include ("header_outside.php");
echo $meldung;
include ("footer_outside.php");
?>