<?php $title="NACHRICHT"; include("../huelle/header.php");
if(isset($_GET['user'])){
    $user = $_GET['user'];
}
else{
    $user = "";
}
?>
<br>
<div align="center">
    <h3>Hier k&ouml;nnen Sie einem Mitspieler <br>
    eine Nachricht senden.</h3>
</div>
<br><br>
<script src="../js/search.js"></script> 
<!--<link rel="stylesheet" type="text/css" media="screen" href="style.css" />-->
<script src="../js/nachricht.js" type="text/javascript"></script>
<div align="center">
    <div>
    <form action="nachricht_senden.php" method="post" name="nachricht_senden">
        <div class="span2 input-control text">
            <input type="text" placeholder="Suche" id="eingabe" name="q" onkeyup="suggest(this.value)"><br><br>
            <div id="ausgabe" align="left">
            </div>
        </div>
        <div class="span8 input-control text">
            <input type="text" placeholder="Empf&auml;nger" name="empfaenger" id="e" value="<?php echo $user;?>">
        </div>
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