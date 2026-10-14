<?php $title="Passwort &auml;ndern"; include("header.php");?>
<div align="center">
    <h2>PASSWORT &Auml;NDERN</h3>
    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
</div>
<div align='center'>
    <table class='farbeTable'>
            <td align='center'>
                <form name="passwort" method="post" action="pw_neu.php" onsubmit="return checkPasswort()">
                    <table class='farbeTable'>
			<div>
			    <br>Altes Passwort: <br>
			</div>
                        <div>
                            <input name="passwort_alt" type="password" size="30" maxlength="30"><br><br>
                        </div>
			<div>
			    Neues Passwort: <br>
			</div>
                        <div>
                            <input name="passwort_neu1" type="password" size="30" maxlength="30"><br><br>
                        </div>
			<div>
			    Passwort wiederholen: <br>
			</div>
                        <div>
                            <input name="passwort_neu2" type="password" size="30" maxlength="30"><br><br>
                        </div>
			<div>
			    <input name="reset" type="reset"><input name="submit" type="submit">
			</div>
                    </table>
                </form>
            </td>
    </table> 
</div>
<?php include("footer.php"); ?>