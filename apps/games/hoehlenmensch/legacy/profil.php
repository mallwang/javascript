<?php $title="Profil"; include ("header.php");
$id = id($_SESSION['nickname']);
$abfrage_profil = mysql_query("SELECT email, nickname FROM login WHERE id = '$id'") or die ("Fehler! profil.php");
$profil = mysql_fetch_array($abfrage_profil);
?>

<table border = "1">
Deine Daten:
		<tr>
			<td width="150"> <?php echo "Nickname: " ?> </td>
			<td align="center" > <?php echo $profil["nickname"];?> </td>
		</tr>
		<tr>
			<td width="150"> <?php echo "E-Mail: " ?> </td>
			<td align="center" > <?php echo $profil["email"];?> </td>
		</tr>
</table>

<table border = "1">
	<td>
		<form name="email" method="post" action="email_neu.php">
			<table border = "0">
				<tr>
					<td align = "left" > <h3> E-Mail Adresse ändern: </h3></td>
				</tr>
				<tr>
					<td width="250">Neue E-Mail Adresse: </td><td> <input name="email_neu1" type="text" size="30" maxlength="30"></td>
				</tr>
				<tr>
					<td width="250" height="40">E-Mail wiederholen: </td><td> <input name="email_neu2" type="text" size="30" maxlength="30"></td>
				</tr>
				<tr>
					<td> <input name="reset" type="reset"> </td>
					<td> <input name="submit" type="submit"> </td>
				</tr>
			</table> 
		</form>
	</td>
	
	<td>
		<form name="passwort" method="post" action="passwort_neu.php">
			<table border = "0">
				<tr>
					<td align = "left" ><h3> Passwort ändern: </h3></td>
				</tr>
				<tr>
					<td width="250">Neues Passwort: </td><td> <input name="passwort_neu1" type="password" size="30" maxlength="30"></td>
				</tr>
				<tr>
					<td width="250" height="40">Passwort wiederholen: </td><td> <input name="passwort_neu2" type="password" size="30" maxlength="30"></td>
				</tr>
				<tr>
					<td> <input name="reset" type="reset"> </td>
					<td> <input name="submit" type="submit"> </td>
				</tr>
			</table> 
		</form>	
	</td>
</table>

<?php include ("footer.php"); ?>