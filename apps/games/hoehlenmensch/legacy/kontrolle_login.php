<?php session_start();
include ("functions.php");
include ("connect.php");
$loginname = $_POST["nickname"];
$passwort = $_POST["passwort"];

if (strpos($loginname, "@")!==false) //wenn E-Mail Adresse eingegeben wurde
{$abfrage = "SELECT id, nickname, passwort FROM login WHERE email = '$loginname' LIMIT 1";}
else
{$abfrage = "SELECT id, nickname, passwort FROM login WHERE nickname = '$loginname' LIMIT 1";}

$ergebnis = mysql_query($abfrage);
$row = mysql_fetch_object($ergebnis);
$nickname = $row->nickname;

$passwort1 = md5($passwort);

if($row->passwort == $passwort1)
	{
		$_SESSION['nickname'] = $nickname;
		$date = date('Y-m-d H:i:s');
		$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 60 minutes");
		$_SESSION['time'] = date('Y-m-d H:i:s', $timestamp);
        echo "Hallo $nickname, dein Login war erfolgreich. <a href='Hauptseite.php'>Los gehts</a>"; 
		$id = id($nickname);
		$eintragen_charakter = mysql_query("UPDATE charakter SET status = '1' WHERE login_id = '$id'");
		exit;
	}
	
else
	{
		echo "Benutzername und/oder Passwort waren falsch.";
		echo "<br>";
		echo "<a href='index.php'>Zurück</a>";
	}

mysql_close();
	
?>
