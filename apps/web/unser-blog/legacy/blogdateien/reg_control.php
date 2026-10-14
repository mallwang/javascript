<?php session_start(); include ("connect.php"); include ("functions.php");
// Werte von Formular übernehmen und weitere initialisieren
$nickname = htmlentities($_POST["nickname"]);
$email = htmlentities($_POST["email"]);
$passwort1 = htmlentities($_POST["passwort1"]);
$passwort2 = htmlentities($_POST["passwort2"]);
//// Prüfung ob alle Felder ausgefüllt sind
//if($passwort1 == "" OR $passwort2 == "" OR $nickname == "" OR $email == ""){
//	  // Mitteilung auf der Homepage
//	  $meldung = "
//		    <div align='center'>
//			      <table class='farbeTable'><br><br>
//					<td align='center'>
//						  <div>
//							    <h3 class='farbe_grau'><br>Eingabefehler. Bitte alle Felder korrekt ausf&uuml;llen!</h3><br><br>
//							    <a href='/www/index.php'>Zur&uuml;ck</a><br><br>
//						  </div>
//					</td>
//			      </table>
//		    </div>
//		    ";
//}// end if
//else{
	  // Prüfung, ob Passwörter identisch sind
	  if($passwort1 == $passwort2){
		    // Passwort verschlüsseln
		    $passwort = md5 ("$passwort1");
		    // Suche nach bereits vorhandenen Einträgen in tabelle 'user'
		    $abfrage = getProperty('name', 'user', 'name', $nickname);
		    $vorh_nickname = $abfrage[0];
		    $abfrage2 = getProperty('email', 'user', 'email', $email);
		    $vorh_email = $abfrage2[0];
		    // Wenn E-Mail Adresse gültig ist
		    if(check_email($email)){
			      // Bei keinen Übereinstimmungen mit den Daten aus der Datenbank
			      if  ($vorh_email != $email AND $vorh_nickname == null AND $vorh_email == null){
					// E-Mail Adresse in Kleinbuchstaben umwandeln
					$email_klein = strtolower($email);
					// id Session 'nickname' festlegen
					$_SESSION['nickname'] = $nickname;
					// Zufallskey generieren und diesen an Bestätigungslink anfügen
					$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
					$schluessel = md5($zufallskey);
					// Mitteilung auf der Homepage
					$meldung = "
					<div align='center'>
						  <table class='farbeTable'><br><br>
							    <td align='center'>
								      <div>
										<h3 class='farbe_grau'><br>Hallo $nickname, <br><br>
										herzlich willkommen im Blog.<br>
										Du hast eine E-Mail mit einem Best&auml;tigungslink erhalten.<br>
										Um deinen Account zu aktivieren, solltest du diesem folgen.</h3><br><br>
										<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
								      </div>
							    </td>
						  </table> 
					</div>
					";
					// neuen User anlegen
					setProperty('user', 'schluessel', 'name', 'email', 'passwort', $schluessel, $nickname, $email_klein, $passwort);
					// neue ID abfragen
					$id = id($nickname);
					//$id = mysql_query("SELECT id FROM user WHERE name = '$nickname'");
					// Startbild Kopieren und Umbenennen
					copy("images/Userbilder/Dummyuser.jpg", "images/Userbilder/$id.jpg");
					// Daten für E-Mail Nachricht festlegen
					$bestaetigungslink = "<a href='http://www.unser-source.co.de/blogdateien/bestaetigung.php?key=$schluessel'>Accont aktivieren</a>";
					$bestaetigungslink_alternativ = "http://www.unser-source.co.de/blogdateien/bestaetigung.php?key=$schluessel";
					$empfaenger = $email;
					$absender = "From: Unser Blog <MarkusAllwang@gmx.de>";
					$betreff = "Herzlich Willkommen in unserem Blog";
					$header  = "MIME-Version: 1.0\r\n";
					$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
					$header .= "From: $absender\r\n";
					$header .= "Reply-To: $absender\r\n";
					// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
					$header .= "X-Mailer: PHP ". phpversion();
					$text = "
					<html>							    
						  <body>
							    <div align='center'>
							    <table>Herzlich Willkommen $nickname,<br><br>
							    das Blogteam begr&uuml;ßt dich recht herzlich und w&uuml;nscht dir viel Spaß!<br>
							    Bitte betätige diesen Link, um deinen Account zu aktivieren: $bestaetigungslink
							    </div>
							    <br><br>
							    <div align='center'>
							    Alternativ kopiere bitte folgenden Link in die Adressleiste deines Browsers: <br>
							    $bestaetigungslink_alternativ
							    </table>
							    </div>
						  </body>
					</html>";
					// Mail versenden
					mail($empfaenger, $betreff, $text, $header);      
			      }// end if	
			      // Wenn Benutzername oder E-Mail bereits vorhanden
			      else{
					$meldung = "
					<div align='center'>
						  <table class='farbeTable'><br><br>
							    <td align='center'>
								      <div>
										<h3 class='farbe_grau'><br>Benutzername oder Email schon vorhanden!</h3><br><br>
										<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
								      </div>
							    </td>
						  </table> 
					</div>
					";
			      }// end else
		    }// end if
		    // Wenn E-Mail Adresse nicht gültig ist
		    else{
			      $meldung = "
					<div align='center'>
						  <table class='farbeTable'><br><br>
							    <td align='center'>
								      <div>
										<h3 class='farbe_grau'><br>Das ist keine g&uuml;ltige E-Mail Adresse!</h3><br><br>
										<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
								      </div>
							    </td>
						  </table> 
					</div>
			      ";
		    }// end else
	  }// end if
	  // Wenn Passwörter nicht identisch sind
	  else{
		    $meldung = "
			      <div align='center'>
					<table class='farbeTable'><br><br>
						  <td align='center'>
							    <div>
								      <h3 class='farbe_grau'><br>Eingabefehler. Die Passw&ouml;rter sind nicht identisch!<br>
								      Bitte korrekt ausf&uuml;llen!</h3><br><br>
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