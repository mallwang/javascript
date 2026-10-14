<?php $title="Kommentar"; include ("header.php");
// Session starten und id abfragen
$von_id = id($_SESSION['nickname']);
$blog_id = $_GET["blog_id"];
echo $blog_id;
// Werte von Formular übernehmen und weitere initialisieren
$betreff = htmlentities($_POST["betreff"]);
$text = htmlentities($_POST["text"]);
$datum = date('Y-m-d H:i:s');
// Prüfung ob alle Felder ausgefüllt sind
//if($betreff == "" OR $text == ""){
//    echo "
//            <div align='center'>
//               <table class='farbeTable'><br><br>
//                        <td align='center'>
//                            <div>
//                                Eingabefehler. Bitte alle Felder korrekt ausfüllen!<br><br>
//                            </div>
//                            <div>
//                                <a href='home.php?beginn=0'>Zurück</a><br><br>
//                            </div>
//                        </td>
//               </table> 
//            </div>
//        ";
//}// end if
//else{
    // Zeilenumbruch in Eintrag durch ein "<br>" ersetzen (Wichtig für DB-Eintrag)
    $text=ereg_replace ("\n", "<br>", $text);
    // Kommentar eintragen
    setProperty('kommentare', 'blog_id', 'user_id', 'betreff', 'inhalt', 'datum',
		$blog_id, $von_id, $betreff, $text, $datum);
    echo "
            <div align='center'>
               <table class='farbeTable'><br><br>
                        <td align='center'>
                            <div>
                                Dein Kommentar wurde erfolgreich eingetragen.<br><br>
                            </div>
                            <div>
                                <a href='home.php?beginn=0'>Zur&uuml;ck zur &Uuml;bersicht</a><br><br>
                            </div>
                        </td>
               </table> 
            </div>
        ";
//}// end else
echo "<meta http-equiv='refresh' content='0; URL=home.php?beginn=0'>";
include ("footer.php");
?>