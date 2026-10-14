<?php $title="NEUGRUENDUNG";?>

<br>
<div align="center">
    <h3>Ihr Unternehmen hat noch keinen Standort!.<br>
    Bitte w&auml;hlen Sie einen Unternehmensstandort.</h3>
</div>
<br><br>
<script src="../js/stadtsuche.js"></script>
<div align="center">
    <div>
    <form action="../intern/firma_gruenden.php" method="post" name="firma_gruenden">
        <div class="span2 input-control text">
            <input type="text" placeholder="Suche" id="eingabe" name="q" onkeyup="suggest(this.value)"><br>
            Mit * finden Sie alle verf&uuml;gbaren St&auml;dte.<br><br>
            <div id="ausgabe" align="left">
            </div>
        </div>
        <div class="span3 input-control text">
            <input type="text" placeholder="Standort" name="standort" id="e" readonly="true">
        </div>
        <div class="span3 input-control text">
            <input type="text" placeholder="Firmenname" name="firmenname" onkeyup="firmensuche(this.value)"><br>
            <div id="hinweis" align="center">
            </div>
        </div>
        Sie k&ouml;nnen den Firmmennamen im Spielverlauf jederzeit &auml;ndern.<p>
        <div>
            <input class="span2" type="submit" value="Gewerbe anmelden">
        </div>
    </form>
    </div>
</div>
