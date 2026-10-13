<html>
	<head>
        <title> Herzlich Willkommen - Home </title>
    </head>

    <link href="main.css" rel="stylesheet" type="text/css" />

	<body>

	<center>

		<table border = "1" width="1000">
			<td align = "center" ><h2><br> ! ! !  HERZLICH WILLKOMMEN  ! ! ! <br></h2></td>
		</table>
		
		<table border = "1" width="1000">
					
			<td width="500">
				<form name="login" method="post" action="kontrolle_reg.php">
					<table border = "0" width="480">
						<tr>
							<td align = "left" > <h3> Registrierung: </h3></td>
						</tr>
						<tr>
							<td width="250">Nickname: </td><td> <input name="nickname" type="text" size="30" maxlength="30"></td>
						</tr>
						<tr>
							<td width="250">E-Mail: </td><td> <input name="email" type="text" size="30" maxlength="60"></td>
						</tr>
						<tr>
							<td width="250">Kennwort: </td><td> <input name="passwort1" type="password" size="20" maxlength="20"></td>
						</tr>
						<tr>
							<td width="250" height="40" valign="top">Kennwort wiederholen: </td><td valign="top"> <input name="passwort2" type="password" size="20" maxlength="20"></td>
						</tr>
						<tr>
							<td> <input name="reset" type="reset"> </td>
							<td> <input name="submit" type="submit"> </td>
						</tr>
					</table> 
				</form>
			</td>
			
			<td valign="bottom" width="500">
				<form name="login" method="post" action="kontrolle_login.php">
					<table border = "0" width="480">
						<tr>
							<td align = "left" ><h3> Login: </h3></td>
						</tr>
						<tr>
							<td width="250">Nickname oder E-Mail: </td><td> <input name="nickname" type="text" size="30" maxlength="60"></td>
						</tr>
						<tr>
							<td width="250">Kennwort: </td><td valign="top"> <input name="passwort" type="password" size="20" maxlength="20"></td>
						</tr>
						<tr>
							<td><h5><a href='pw_vergessen.php'>Passwort vergessen</a></h5></td>
						</tr>
						<tr>
							<td> <input name="reset" type="reset"> </td>
							<td> <input name="submit" type="submit"> </td>
						</tr>
					</table> 
				</form>
			</td>
			
		</table>
		
		<table border = "1" width="1000">
			<td align = "left" width="500">
			<b>Impressum:</b><br>
			IMPRESSUM_NAME<br>
			IMPRESSUM_STREET<br>
			IMPRESSUM_CITY<br>
			</td>
			<td align="right" valign = "bottom" width="500">
			<b>Stand:</b> 27.08.2012
			<br>
			<b>Version:</b> 0.2
			</td>
		</table>
		
	</center>

	</body>
	
</html>