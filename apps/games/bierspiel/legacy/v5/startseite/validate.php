<?php session_start(); include("../huelle/connect.php"); include '../funktionen/funktionen.php';

$art = $_POST["art"];

if($art == "login"){
    $nickname = $_POST["nickname"];
    $passwort = md5($_POST["passwort"]);
    
    //$query = mysql_query("SELECT nickname, passwort, aktiviert FROM user WHERE nickname = '$nickname'");
    //$res = anfrage("SELECT nickname, passwort, aktiviert FROM user WHERE nickname = '$nickname'");
    //$dbNickname = $res[0][0];
    //$dbPasswort = $res[0][1];
    //$dbAktiviert = $res[0][2];
    //echo $dbNickname;

    $daten = anfrage("SELECT nickname, passwort, aktiviert FROM user WHERE nickname = '$nickname'");
    $dbNickname = $daten[0];
    $dbPasswort = $daten[1];
    $dbAktiviert = $daten[2];   
    
    if($nickname == $dbNickname AND $passwort == $dbPasswort){
        if ($dbAktiviert == 1){
            // Session 'nickname' erzeugen
            $_SESSION['nickname'] = $nickname;
            // Status auf '1' setzen
            $eintragen = anfrage("UPDATE user SET status = '1' WHERE nickname = '$nickname'");
            // dbAbfrage -> update
            
            if ($eintragen){
                echo 'ok';
            }
        }// end if ($dbSchluessel == "")
        else{
            echo 'nichtAktiviert';
        }// end else ($dbSchluessel == "")
    }// else if($nickname == $dbNickname AND $passwort == $dbPasswort)
    else{
        echo 'no';
        //echo 'no ' . $nickname  . ' ' . $passwort;;
    }// end else($nickname == $dbNickname AND $passwort == $dbPasswort)
}// end if($art == "login")
elseif($art == "reg"){
    $passwort1 = $_POST["passwort1"];
    $passwort2 = $_POST["passwort2"];
    
    if($passwort1 != $passwort2){
        echo 'pwUngleich';
    }// end if($passwort1 != $passwort2)
    else{
        $nickname = $_POST["nickname"];
        // Email Adresse in Kleinbuchstaben um zu vergleichen
        $email = strtolower($_POST["email"]);
        // Nach bestehendem Nickname suchen (DB-Suche ist caseinsensitive)
        $user = anfrage("SELECT COUNT(nickname) as anzahl FROM user WHERE nickname = '$nickname'");
        // Nach bestehender Email-Adresse suchen
        $mail = anfrage("SELECT COUNT(email) as anzahl FROM user WHERE email = '$email'");
        // Wenn keine Übereinstimmungen mit den DB-Daten
        if($user[0] == 0 AND $mail[0] == 0){
            //Passwort md5-verschlüsseln
            $passwort = md5($_POST["passwort1"]);
            // Zufallskey generieren und diesen an Bestätigungslink anfügen
            $zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
            $schluessel = md5($zufallskey);
            //Neuen User in DB eintragen
            $dateneintrag = anfrage("INSERT INTO `user`(`schluessel`, `nickname`, `email`, `passwort`) VALUES ('$schluessel', '$nickname', '$email', '$passwort')");
            // dbAbfrage -> insert
            
            // Wenn neuer User erfolgreich eintgetragen wurden
            if($dateneintrag){
                // ID des neuen Users abfragen
                $id = anfrage("SELECT id FROM user WHERE nickname = '$nickname' AND email = '$email'");
                // Eintragen der Startrohstoffe
                $eintragen_firmendaten = anfrage("INSERT INTO firmendaten (user_id, hauptsitz, bargeld,
                                                 konto, kredit, mitarbeiter, bekanntheit, beliebtheit)
                                                 VALUES ('$id[0]', '', '100000', '0', '0', '0', '0', '0')");
                // Wenn Startrohstoffe erfolgreich eintgetragen wurden
                if($eintragen_firmendaten){
                    // Daten für E-Mail Nachricht festlegen
                    $bestaetigungslink = "<a href='http://example.com/startseite/bestaetigung.php?key=$schluessel'>Accont aktivieren</a>";
                    $bestaetigungslink_alternativ = "http://example.com/startseite/bestaetigung.php?key=$schluessel";
                    $empfaenger = $email;
                    $absender = "From: Das Bierspiel <MAIL_WEBMASTER@example.com>";
                    $betreff = "Herzliche Willkommen beim Bierspiel";
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
                                        Herzlich Willkommen $nickname,<br><br>
                                        das Team des Bierspiels begr&uuml;ßt dich recht herzlich und w&uuml;nscht dir viel Spaß!<br>
                                        Bitte betätige diesen Link, um deinen Account zu aktivieren: $bestaetigungslink<br>
                                        Im Anschluss daran erhältst du dein Passwort, mit dem du dich einloggen kannst.
                                        </div>
                                        <br><br>
                                        <div align='center'>
                                        Alternativ kannst du auch folgenden Link in die Adressleiste deines Browsers kopieren: <br>
                                        $bestaetigungslink_alternativ
                                        </div>
                              </body>
                    </html>";
                    // Mail versenden
                    mail($empfaenger, $betreff, $text, $header);
                    echo 'eintragOk';
                }// end if($eintragen_firmendaten)
                else{
                    echo 'eintragNichtOk';
                }// end else($eintragen_firmendaten)
            }// end if($dateneintrag)
            else{
                echo 'eintragNichtOk';
            }// end else($dateneintrag)
        }// end if($user->anzahl == 0 AND $mail->anzahl == 0)
        else{
            echo 'no';
        }// end else($user->anzahl == 0 AND $mail->anzahl == 0)
    }// end else($passwort1 != $passwort2)
}// end else($art == "login")
?>