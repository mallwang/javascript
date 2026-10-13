<?php $title="Umgebung"; include ("header.php");
$id = id($_SESSION['nickname']);
$nickname = $_SESSION['nickname'];
$email_neu1 = $_POST["email_neu1"];
$email_neu2 = $_POST["email_neu2"];
$email = email($id);

$result_email = mysql_query("SELECT email FROM login WHERE email = '$email'");
$abfrage_email = mysql_num_rows($result_email);

if($email_neu1 == "" OR $email_neu2 == "")
{
	echo "Eingabefehler. Bitte alle Felder korrekt ausfüllen! <a href='profil.php'>Zurück</a>";
	exit;
}

if($email_neu1 == $email_neu2 AND $abfrage_email == 0)
{$eintragen = mysql_query("UPDATE login SET email ='$email_neu1' WHERE id = '$id'") or die ("Kein Eintrag neue E-Mail!");

$empfaenger = $email_neu1;
$betreff = "Neue E-Mail Adresse";
$from = "From: MAIL_FROM_NAME <MAIL_FROM_ADDRESS>";
$text = "Hallo $nickname, du hast erfolgreich deine E-Mail Adresse von $email auf $email_neu1 geändert.";

mail($empfaenger, $betreff, $text, $from);

echo "Deine E-Mail Adresse wurde erfolgreich von $email auf $email_neu1 geändert. Dir wurde soeben eine Bestätigungsmail gesendet";
}

else
{echo "Die beiden E-Mail Adressen stimmen nicht überein oder die E-Mail Adresse ist bereits vorhanden! <a href='profil.php'>Zurück</a>";}

include ("footer.php"); ?>