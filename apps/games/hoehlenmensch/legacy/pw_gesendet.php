<?php session_start();
include ("functions.php");
include ("connect.php");
$nickname = $_POST["nickname"];
$email = $_POST["email"];

if($nickname == "" OR $email == "")
	{
		echo "Eingabefehler. Bitte alle Felder korrekt ausfüllen! <a href='pw_vergessen.php'>Zurück</a>";
		exit;
	}
	
	$abfrage = "SELECT email FROM login WHERE nickname = '$nickname' LIMIT 1";
	$ergebnis = mysql_query($abfrage);
	$row = mysql_fetch_object($ergebnis);
	$email_kontrolle = $row->email;
	
	
if ($email_kontrolle == $email)
{
$random_pw = substr(md5(microtime()), mt_rand(0, 24), 8);
$neues_pw = md5($random_pw);

$empfaenger = $email;
$betreff = "Kennwort vergessen";
$from = "From: MAIL_FROM_NAME <MAIL_FROM_ADDRESS>";
$text = "Hallo $nickname, für dich wurde ein neues Kennwort gesetzt. Es lautet: $random_pw";

$eintragen = mysql_query("UPDATE login SET passwort ='$neues_pw' WHERE email = '$email'") or die ("Kein Eintrag neues PW!");

mail($empfaenger, $betreff, $text, $from);
echo $random_pw;
echo "Ein neues Passwort wurde an $email gesendet";
echo "<br> Zurück zur <a href='index.php'>Hauptseite</a>";
}

else
{
echo "Der Nickname stimmt nicht mit der angegebenen E-Mail Adresse überein! <a href='pw_vergessen.php'>Zurück</a>";

}

?>