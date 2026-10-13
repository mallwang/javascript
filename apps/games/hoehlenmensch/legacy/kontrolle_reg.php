<?php session_start();
	  include ("connect.php");
$nickname = $_POST["nickname"];
$email = $_POST["email"];
$passwort1 = $_POST["passwort1"];
$passwort2 = $_POST["passwort2"];

if($passwort1 != $passwort2 OR $nickname == "" OR $email == "")
	{
		echo "Eingabefehler. Bitte alle Felder korrekt ausfüllen! <a href='index.php'>Zurück</a>";
		exit;
	}
$passwort = md5 ("$passwort1");

$result_nickname = mysql_query("SELECT nickname FROM login WHERE nickname = '$nickname'");
$abfrage_nickname = mysql_num_rows($result_nickname);
$result_email = mysql_query("SELECT email FROM login WHERE email = '$email'");
$abfrage_email = mysql_num_rows($result_email);

if  ($abfrage_nickname == 0 AND $abfrage_email == 0)
	{
		$werte = "INSERT INTO login (nickname, email, passwort)
						VALUES('$nickname', '$email', '$passwort')";
		$eintragen = mysql_query($werte);
		
		if($eintragen == true)
		{
			$id = mysql_insert_id();
			$_SESSION['nickname'] = $nickname;
			$date = date('Y-m-d H:i:s');
			$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 0 minutes");
			$_SESSION['time'] = date('Y-m-d H:i:s', $timestamp);
			echo "Hallo $nickname, Herzlich Willkommen im Spiel.<br><a href='Hauptseite.php'>Start</a>";
			$eintragen_rohstoffe = mysql_query("INSERT INTO rohstoffe (login_id, pflanzliche_nahrung, tierische_nahrung, wasser, holz, stein)
			VALUES ('$id', '10', '10', '10', '0', '0')");
			$eintragen_charakter = mysql_query("INSERT INTO `charakter`(`login_id`, `erfahrungspunkte`, `charakterpunkte`, `kraft`, `intelligenz`, 
			`schnelligkeit`, `geschicklichkeit`, `ausdauer`, `arbeitsende`, `taetigkeit`, `status`) VALUES ('$id','0','0','1','1','1','1','1','9999-00-00 00:00:00','0', '1')");
			$eintragen_inventar = mysql_query("INSERT INTO `inventar`(`login_id`, `axt`, `jagdspeer`, `wasserschlauch`, `korb`, `lendenschurz`, `schuhe`)
			VALUES ('$id','0','0','0','0','0','0')");
			$eintragen_skills = mysql_query("INSERT INTO `benutzer`.`skills` (`login_id`, `jagen`, `sammeln`, `fischen`, `pilzsuchen`, `holzfällen`, `steinmetzen`,
			`wassersuchen`, `axt`, `jagdspeer`, `wasserschlauch`, `korb`, `lendenschurz`, `schuhe`) VALUES ('$id', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");
$empfaenger = $email;
$betreff = "Test der Mail-Funktion";
$from = "From: MAIL_FROM_NAME <MAIL_FROM_ADDRESS>";
$text = "Herzlich Willkommen blablabla..";
mail($empfaenger, $betreff, $text, $from);
		}
		else
		{
			echo "Fehler beim Speichern des Benutzernamens.";
			echo "<br>";
			echo "<a href='index.php'>Zurück</a>";
		}
	}

else
	{
		echo "Benutzername oder Email schon vorhanden!";
		echo "<br>";
		echo "<a href='index.php'>Zurück</a>";
	}

mysql_close();

/*if($nickname==""){echo "Keinen Nickname eingetragen";}
elseif ($email==""){echo "Keine Email eingetragen";}
elseif ($passwort==""){echo "Kein Passwort eingetragen";}
else
	{
	$abfrage_nickname = mysql_query("SELECT * FROM login WHERE nickname = '".$nickname."'");
	$abfrage_email = mysql_query("SELECT * FROM login WHERE email = '".$email."'");
	$abfragearray_nickname = mysql_fetch_array($abfrage_nickname);
	$abfragearray_email = mysql_fetch_array($abfrage_email);

	if(!empty($abfragearray_nickname))
		{
			if(!empty($abfragearray_email))
			{
			echo "Nickname und Email schon vorhanden!";
			echo "<br>";
			echo "<br>";
			echo "<a href='index.php'>Zurück zur Registrierung</a>";
			}
			else
			{
			echo "Nickname schon vorhanden!";
			}
		}
		
	elseif(!empty($abfragearray_email))
		{
		echo "Email schon vorhanden!";
		}
		
	else
		{
		$eintragen = "INSERT INTO login (nickname, email, passwort)
						VALUES('$nickname', '$email', '$pwverschl')";
		$result = mysql_query($eintragen) or die ("'".$eintragen."':".mysql_error());
		echo "Registrierung erfolgreich!";
		echo "<br>";
		echo "<br>";
		echo "<a href='Start.html'>Zum Spiel</a>";
		}
		
	mysql_close();
	}*/
?>