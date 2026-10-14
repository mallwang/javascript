<?php $title="Stopp das Abo!"; include ("header.php");

$abbonierter_id = $_GET["id"];
$abbonierter_name = name($abbonierter_id);

$delete_abbo = mysql_query("DELETE FROM abbonnements WHERE abbonierter_id = $abbonierter_id");

echo "
            <div align='center'>
               <table class='farbeTable'>
                       <div>
                           <br>Du hast nun $abbonierter_name aus deinen Abbonnements gel&ouml;scht!<br><br>
                       </div>
                       <div>
                           <a href='profil.php'>Zur&uuml;ck</a><br>
                       </div>
               </table> 
            </div>
         ";
echo "<meta http-equiv='refresh' content='0; URL=profil.php'>";

include("footer.php"); ?>