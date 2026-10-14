<?php $title="Account bestätigen"; session_start(); include("functions.php"); include("connect.php");

// Werte von Formular übernehmen
$schluessel = $_GET["key"];

// User abfragen, dessen Account aktiviert wurde
$abfrage = getProperty('name', 'user', 'schluessel', $schluessel);
$name = $abfrage[0];

// Wenn kein User diesen Key hat
if($name == ""){
	$meldung = "
		<div align='center'>
			  <table class='farbeTable'><br><br>
					<td align='center'>
						<div>
							<h3 class='farbe_grau'><br><br>Account wurde bereits aktiviert!</h3><br><br>
							<a href='http://www.unser-source.co.de/index.php'>Zur&uuml;ck</a><br><br>
						</div>
					</td>
			  </table> 
		</div>
		";
}
else{
	$schluessel = "";
	$schluesselloeschen = updateProperty('user', 'schluessel', $schluessel, 'name', $name);
	$meldung = "
		<div align='center'>
			<table class='farbeTable'><br><br>
				<td align='center'>
					<div>
						<h3 class='farbe_grau'><br><br>Herzlich Willkommen $name, dein Account wurde erfolgreich aktiviert!</h3><br><br>
						Du kannst dich nun <a href='http://www.unser-source.co.de/index.php'>einloggen.</a><br><br>
					</div>
				</td>
			</table> 
		</div>
	";
}
mysql_close();
include ("header_outside.php");
echo $meldung;
?>