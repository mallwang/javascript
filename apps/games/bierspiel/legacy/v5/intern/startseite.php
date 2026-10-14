<?php $title="HOME"; include("../huelle/header.php");
echo "<br><br><br>";

$nickname = $_SESSION['nickname'];

// Firmennamen abfragen
    $firmendaten_abfrage = mysql_query("SELECT firmenname, hauptsitz FROM firmendaten WHERE user_id =
                                       (SELECT id FROM user WHERE nickname = '$nickname')");
    $firmendaten = mysql_fetch_array($firmendaten_abfrage);
    $firmenname = $firmendaten[0];
    $hauptsitz = $firmendaten[1];
    // Wenn noch keine Firma gegründet wurde
    if ($firmenname == "" OR $hauptsitz == ""){
        include ("neugruendung.php");
        exit;
    }
    // Wenn schon eine Firma gegründet wurde
    else{
        include("../produktion.php");
        exit;
    }

echo "".$_SESSION['nickname'];

?>

<div align="center">
    <h1>IM AUFBAU... </h1><br>
    <img src="../images/bauarbeiter.png">
</div>

<br><br><br><br><br>
<?php include("../huelle/footer.php");?>