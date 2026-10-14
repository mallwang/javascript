<?php $title="Neuer Blogeintrag"; include ("header.php");
// Session starten und id abfragen
$von_id = id($_SESSION['nickname']);
// Werte von Formular übernehmen und weitere initialisieren
$betreff = htmlentities($_POST["betreff"]);
$text = htmlentities($_POST["text"]);
//$datum = date('Y-m-d H:i:s');
// Prüfung ob alle Felder ausgefüllt sind
//if($betreff == "" OR $text == ""){
//	echo "
//		<div align='center'>
//			  <table class='farbeTable'><br><br>
//					<td align='center'>
//						<div>
//							<br><br>Eingabefehler. Bitte alle Felder korrekt ausf&uuml;llen!<br><br>
//							<a href='new_blog.php?betreff=$betreff&text=$text'>Zurück</a>
//						</div>
//					</td>
//			  </table> 
//		</div>
//	";
//    }// end if
//else{
    // Zeilenumbruch in Eintrag durch ein "<br>" ersetzen (Wichtig für DB-Eintrag)
    $text = ereg_replace ("\n", "<br>", $text);
    // Blog eintragen
    setProperty('blogs', 'user_id', 'betreff', 'inhalt', $von_id, $betreff, $text);
	echo "
	<div align='center'>
		<table class='farbeTable'>
			<td align='center'>
				<br>Dein Beitrag wurde erfolgreich erstellt.<br><br>
			<a href='blog.php?beginn=0'>Zur&uuml;ck zum Blog</a><br><br>
			</td>
		</table>
	</div>
	";
	echo "<meta http-equiv='refresh' content='0; URL=blog.php?beginn=0'>";
//}// end else
include ("footer.php");
?>