<?php $title="DIE FIRMA"; include("../huelle/header.php");

// aktueller User
$nickname = $_SESSION['nickname'];
// id aktueller User
$abfrage_der_id = mysql_query("SELECT id FROM user WHERE nickname = '$nickname'");
$der_id = mysql_fetch_array($abfrage_der_id);
$id = $der_id[0];
// Grundstuecke des Users
$abfrage_anzahl_grundstuecke = mysql_query("SELECT COUNT(id) AS anzahl FROM grundstuecke WHERE user_id = '$id'");
$anzahl_grundstuecke = mysql_fetch_array($abfrage_anzahl_grundstuecke);
$grundstuecke = $anzahl_grundstuecke[0];
// Wenn User kein Grundstueck besitzt
if($grundstuecke==0){
    //echo "Du besitzt momentan kein Grundst&uuml;ck!<br>";
    echo "<div style='width: 100%; float: left;'><a href='new_land.php'>Neues Grundst&uuml;ck kaufen</a></div>";    
}
// Wenn User mindestens ein Grundstueck besitzt
else{
    $a = mysql_query("SELECT id, art, parkplatz, spielplatz FROM grundstuecke WHERE user_id ='$id'");
    $i = 1;
    if($grundstuecke==1){
        $anzahl = "Grundst&uuml;ck";
    }
    elseif($grundstuecke>1){
        $anzahl = "Grundst&uuml;cke";
    }
    //echo "Du besitzt momentan $grundstuecke $anzahl!<br>";
    echo "<div align='center'>";
    while($c = mysql_fetch_object($a))
    {
        $art_nr = $c->art;
        if ($art_nr == 0) {$art = "Brachland "; $img = "brachland";}
        elseif($art_nr == 1) {$art = "Kneipe "; $img = "kneipe";}
        elseif($art_nr == 2) {$art = "Gasthaus "; $img = "gaststaette";}
        
        $parkplatz_nr = $c->parkplatz;
        if ($parkplatz_nr == 0) {$parkplatz = "ohne Parkplatz ";}
        elseif($parkplatz_nr == 1) {$parkplatz = "mit Parkplatz ";}
        
        $spielplatz_nr = $c->spielplatz;
        if ($spielplatz_nr == 0) {$spielplatz = "ohne Spielplatz";}
        elseif($spielplatz_nr == 1) {$spielplatz = "mit Spielplatz";}
        
        $grundstueck_id = $c->id;
        //echo "<div style='width: 100%; float: left;'>$i: $art $parkplatz $spielplatz (ID:$grundstueck_id) <a href='ausbau.php?id=$grundstueck_id&art=$art_nr&pp=$parkplatz_nr&sp=$spielplatz_nr'>Ausbau und Infos</a></div><br>";
        $i=$i+1;
    ?><br>
        <ul class="listview gross">
            <li class="bg-color-blue fg-color-white">
                <div class="icon">
                    <img src="../images/<?php echo $img;?>.jpg" />
                </div>
                <div class="data">
                    <h4><?php echo $art . " (ID:$grundstueck_id)";?></h4>
                    <p><?php echo $parkplatz;?><br>
                    <?php echo $spielplatz;?></p>
                </div>
            </li>
        </ul>
    <?php        
    }
    
    echo "</div><div align='center'><a href='new_land.php'>Neues Grundst&uuml;ck kaufen</a></div>"; 
}






















include ("../huelle/footer.php");?>