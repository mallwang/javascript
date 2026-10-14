<?php $title= "Abbonieren"; include ("header.php");
// Variablen deklarieren und initialisieren
$user_id = id($_SESSION['nickname']);
$abbonierter_id = $_GET["user_id"];
$abbonierter_name = name($abbonierter_id);
// DB-Abfrage über vorhandenes Abbonnement und zählen dieses
$a = "SELECT * FROM abbonnements WHERE user_id = $user_id AND abbonierter_id = $abbonierter_id";
$b = mysql_query($a);
$c = mysql_num_rows($b);
// Wenn User sich selbst abbonieren möchte
if ($user_id == $abbonierter_id){
    echo "
            <div align='center'>
               <table class='farbeTable'><br><br>
                        <td align='center'>
                            <div>
                                <br>Du kannst dich nicht selbst abbonieren!<br><br>
                            </div>
                            <div>
                                <a href='suche.php'>Zur&uuml;ck zur Suche</a><br><br>
                            </div>
                        </td>
               </table> 
            </div>
         ";
}// end if
// Wenn User einen anderen User abbonieren möchte
else{
    // Wenn kein Abbonnement besteht
    if ($c == 0){
        // DB-Eintrag über neues Abbonnement
        $d = setProperty('abbonnements', 'user_id', 'abbonierter_id', $user_id, $abbonierter_id);
        // Nachricht auf Homepage
        echo "
            <div align='center'>
               <table class='farbeTable'><br><br>
                        <td align='center'>
                            <div>
                                <br>Herzlichen Gl&uuml;ckwunsch, du abbonierst nun $abbonierter_name.<br><br>
                            </div>
                            <div>
                                Neuigkeiten dieses Users findest du unter <a href='home.php?beginn=0'>Home</a><br><br>
                            </div>
                        </td>
               </table> 
            </div>
         ";
        }// end if
    // Wenn Abbonnement bereits besteht
    else{
        // Nachricht auf Homepage
        echo "
            <div align='center'>
               <table class='farbeTable'><br><br>
                        <td align='center'>
                            <div>
                                <br>Du abbonierst diesen User bereits!<br><br>
                            </div>
                            <div>
                                <a href='suche.php'>Zur&uuml;ck zur Suche</a><br><br>
                            </div>
                        </td>
               </table> 
            </div>
         ";
    }// end else
}// end else
include("footer.php");
?>