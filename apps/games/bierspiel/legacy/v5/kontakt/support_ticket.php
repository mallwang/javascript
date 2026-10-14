<?php $title="SUPPORT TICKET"; include("../huelle/header.php");?>
<br>
<div align="center">
    <h3>Hier k&ouml;nnen Sie dem Team des Bierspiels <br>
    eine Nachricht zukommen lassen.</h3>
</div>
<br><br>

<div align="center">
    <div>
    <form action="support_ticket_eintragen.php" method="post">
        <div class="span8 input-control text">
            <input type="text" placeholder="Betreff" name="betreff">
        </div>
        <div class="span8 input-control textarea">
            <textarea placeholder="Bitte ihre Nachricht" name="inhalt"></textarea>
        </div>
        <div>
            <input class="span4" type="submit" value="Senden">
        </div>
    </form>
    </div>
</div>

<br><br><br><br><br>
<?php include("../huelle/footer.php");?>