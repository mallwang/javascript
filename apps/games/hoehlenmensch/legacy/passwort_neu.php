<?php $title="Umgebung"; include ("header.php");
$id = id($_SESSION['nickname']);
$nickname = $_SESSION['nickname'];
$passwort_neu1 = $_POST["passwort_neu1"];
$passwort_neu2 = $_POST["passwort_neu2"];
$email = email($id);

if($passwort_neu1 == "" OR $passwort_neu2 == "")
{
	echo "Eingabefehler. Bitte alle Felder korrekt ausfüllen! <a href='profil.php'>Zurück</a>";
	exit;
}

if($passwort_neu1 == $passwort_neu2)
{
$passwort_neu = md5($passwort_neu1);

$eintragen = mysql_query("UPDATE login SET passwort ='$passwort_neu' WHERE id = '$id'") or die ("Kein Eintrag neue E-Mail!");

$empfaenger = $email;
$betreff = "Neues Passwort";
$from = "From: MAIL_FROM_NAME <MAIL_FROM_ADDRESS>";
$text = "Hallo $nickname, du hast erfolgreich dein Passwort geändert. Es lautet: $passwort_neu1";

mail($empfaenger, $betreff, $text, $from);

echo "Dein Passwort wurde erfolgreich geändert. Dir wurde soeben eine E-Mail an $email gesendet";
}

else
{echo "Die eingegebenen Passwörter stimmen nicht überein! <a href='profil.php'>Zurück</a>";}

include ("footer.php"); ?>