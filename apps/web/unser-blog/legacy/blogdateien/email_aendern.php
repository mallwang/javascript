<?php $title="E-Mail Adresse &auml;ndern"; include("header.php");?>
<div align="center">
    <h2>E-MAIL ADRESSE &Auml;NDERN</h3>
    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
</div>
<div align='center'>
    <table class='farbeTable'>
            <td align='center'>
                <form name="email" method="post" action="email_neu.php" onsubmit="return checkNeueEmail()">
                    <table class='farbeTable'>
			<div>
			    <br>Neue E-Mail Adresse:<br>
			</div>
                        <div>
                            <input name="email_neu1" type="text" size="30" maxlength="50"><br><br>
                        </div>
			<div>
			    E-Mail wiederholen:<br>
			</div>
                        <div>
                            <input name="email_neu2" type="text" size="30" maxlength="50"><br><br>
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