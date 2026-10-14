<?php $title= "Suche"; include ("header.php"); ?>
<div align="center">
<h2>SUCHE</h3>
        <table class='farbeTable'>
            <form name="usersuche" method="post" action="usersuche.php" onsubmit='return checkSuche()'>
                    <tr>
                        <td align="center"><h4 class='farbeTable'>USERSUCHE:</h4></td>
                    </tr>
                    <tr>
                        <td align="center"><p>Bitte geben Sie hier einen Suchbegriff ein.<br>
                        Die Suchfunktion sucht nach allen Usern,<br>
                        in denen dieser Begriff enthalten ist.<br>
                        (Eine Suche mit * liefert eine Komplett&uuml;bersicht)<br></p></td>
                    </tr>
                    <tr>
                        <td align='center'><input autocomplete="on" placeholder="Nickname" name="nickname" type="text" size="30" maxlength="20"></td>
                    </tr>
                    <tr>
                        <td align='center'> <input name="reset" type="reset">
                        <input name="submit" type="submit"> </td>
                    </tr>
            </form>
        </table>
</div>
<?php include("footer.php"); ?>