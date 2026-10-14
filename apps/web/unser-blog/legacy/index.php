<?php include("blogdateien/header_outside.php");?>
<!-- Tabelle Registrierung -->
<table width="800">
	<br><br><td class="hero-unit">
		<!-- Form Registrierung -->
		<form name="login" method="post" action="blogdateien/reg_control.php" onsubmit="return checkRegistrierung()">
			<table>
				<div>
					<h3 class="Ueberschrift">Registrierung:</h3>
				</div>
				<div>
					<input autocomplete="on" placeholder="Nickname" name="nickname" type="text" size="30" maxlength="20">
				</div>
				<div>
					<input autocomplete="on" placeholder="E-Mail Adresse" name="email" type="email" size="30" maxlength="50">
				</div>
				<div>
					<input placeholder="Passwort" name="passwort1" type="password" size="30" maxlength="15">
				</div>
				<div>
					<input placeholder="Passwort wiederholen" name="passwort2" type="password" size="30" maxlength="15">
				</div>
			
					<br>
			
				<div>
					<input name="reset" type="reset"><input name="submit" type="submit">
				</div>
			</table>
		</form>
	</td>
	<td class="hero-unit" align="left">
		<!-- Form Login -->
		<form name="login" method="post" action="blogdateien/log_control.php" onsubmit="return checkLogin()">
			<table>
				<div>
					<h3 class="Ueberschrift">Login:</h3>
				</div>
				<div>
					<input autocomplete="on" placeholder="Nickname oder E-Mail Adresse" name="loginname" type="text" size="30" maxlength="50">
				</div>
				<div>
					<input placeholder="Passwort" name="passwort" type="password" size="30" maxlength="20">
				</div>
					<br>
				<div>
					<input name="reset" type="reset"><input name="submit" type="submit">
				</div>
				<div>
					<h5><a class="farbe_weiss pointer">Passwort vergessen</a></h5>
				</div>
			</table> 
		</form>
		<!-- Form Neues Passwort (zum ausklappen) -->
		<neuesPasswort align="left">
				<form name="neuesPW" method="post" action="blogdateien/pw_gesendet.php" onsubmit="return checkNeuesPasswort()">
					<table>
						<div>
							<h3 class='farbe_weiß'>Neues Passwort:</h3>
						</div>
						<div>
							<input autocomplete="on" placeholder="Nickname" name="nickname" type="text" size="30" maxlength="60">
						</div>
						<div>
							<input placeholder="E-Mail" name="email" type="email" size="30" maxlength="50">
						</div>
							<br>
						<div>
							<input name="reset" type="reset"><input value="anfordern" name="submit" type="submit">
						</div>
					</table> 
				</form>
		</neuesPasswort>
	</td>
</table><br><br>
<!-- Tabelle Impressum -->
<table width="800">
	<td align="left" width="500">
	<b>Impressum:</b><br>
	Markus Allwang / Sophia Harland / Lena Pinkerneil<br>
	</td>
	<td align="right" valign="bottom" width="300">
	<b>Stand:</b> 23.10.2012
	<br>
	<b>Version:</b> 1.0
	</td>
</table>
<?php include("blogdateien/footer_outside.php");?>