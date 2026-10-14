<?php include("header.php");
// Variablen deklarieren und initialisieren
$id = id($_SESSION['nickname']);
// Bildformat feststellen
$dateityp = GetImageSize($_FILES['userfile']['tmp_name']);
// Wenn Bildtyp ein .jpg oder .jpeg ist
if($dateityp[2] == 2){
   // Wenn Bild nicht groesser als 100 kb ist
   if($_FILES['userfile']['size'] <  102400){
      move_uploaded_file($_FILES['userfile']['tmp_name'], "images/Userbilder/$id.jpg");
      // size[0] = Breite / size[1] = Hoehe  
      $size=getimagesize("images/Userbilder/$id.jpg");  
      $breite=$size[0];  
      $hoehe=$size[1];  
      // Maximale Dimensionen festlegen
      $hoehe_max = 100;
      $breite_max = 100;
      // Wenn bild zu breit ist
      if($breite > $breite_max){
         echo "
            <div align='center'>
               <table class='farbeTable'>
                       <div align='center'>
                           <br>Dein Bild ist zu breit!<br><br>
                       </div>
                       <div>
                           <a href='fotoHochladen.php'>Zur&uuml;ck</a><br>
                       </div>
               </table> 
            </div>
            ";
         unlink("images/Userbilder/$id.jpg");
      }// end if
      // Wenn bild zu hoch ist
      elseif ($hoehe > $hoehe_max){ 
         echo "
            <div align='center'>
               <table class='farbeTable'>
                       <div align='center'>
                           <br>Dein Bild ist zu hoch!<br><br>
                       </div>
                       <div>
                           <a href='fotoHochladen.php'>Zur&uuml;ck</a><br>
                       </div>
               </table> 
            </div>
            ";
         unlink("images/Userbilder/$id.jpg");
      }
      // Wenn Bildmaﬂe in Ordnung sind
      else{ 
         echo "
               <div align='center' class='farbeTable'>
                       <div>
                           <br>Das Bild wurde erfolgreich hochgeladen<br><br>
                       </div>
                       <div>
                           <a href='profil.php'>Zur&uuml;ck</a><br>
                       </div>
               </div> 
         ";
         echo "<meta http-equiv='refresh' content='0; URL=profil.php'>";
      }// end else
   }// end if
   // Wenn Bild groesser als 100 kb ist
   else
      {
         echo "
            <div class='farbeTable' align='center'>
               <div align='center'>
                  <br>Das Bild darf nicht gr&ouml;sser als 100 kb sein<br><br>
               </div>
               <div>
                     <a href='profil.php'>Zur&uuml;ck</a><br>
               </div>
            </div> 
         ";
      }// end else
   }// end if
// Wenn anderes Format hochgeladen wurde
else{
      echo "
            <div class='farbeTable' align='center'>
                  <div align='center'>
                     <br>Bitte nur Bilder im .jpg Format hochladen<br><br>
                  </div>
                  <div>
                     <a href='profil.php'>Zur&uuml;ck</a><br>
                  </div>
            </div> 
         ";
    }// end else
include("footer.php");
?>