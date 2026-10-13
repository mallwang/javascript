<html>
	<head>
        <title> Passwort vergessen </title>
    </head>
    <link href="main.css" rel="stylesheet" type="text/css" />
	<body>
	<center>
		
		<table border = "1" width="500">
			<td>
				<form name="login" method="post" action="pw_gesendet.php">
					<table border = "0" width="480">
						<tr>
							<td align = "left" > <h3> Deine Daten: </h3></td>
						</tr>
						<tr>
							<td width="250">Nickname: </td><td> <input name="nickname" type="text" size="30" maxlength="30"></td>
						</tr>
						<tr>
							<td width="250">E-Mail: </td><td> <input name="email" type="text" size="30" maxlength="60"></td>
						</tr>
						<tr>
							<td> <input name="reset" type="reset"> </td>
							<td> <input name="submit" type="submit"> </td>
						</tr>
					</table> 
				</form>
			</td>
			
		</table>
		
		<table border = "1" width="500">
			<td align = "left" width="300">
			<b>Impressum:</b><br>
			IMPRESSUM_NAME<br>
			IMPRESSUM_STREET<br>
			IMPRESSUM_CITY<br>
			</td>
			<td align="right" valign = "bottom" width="200">
			<b>Stand:</b> 27.08.2012
			<br>
			<b>Version:</b> 0.2
			</td>
		</table>
		
	</center>

	</body>
	
</html>