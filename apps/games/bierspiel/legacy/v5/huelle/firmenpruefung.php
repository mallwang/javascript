<?php

$nickname = $_SESSION['nickname'];

// Firmennamen abfragen
    $firmendaten_abfrage = mysql_query("SELECT firmenname, hauptsitz FROM firmendaten WHERE user_id =
                                       (SELECT id FROM user WHERE nickname = '$nickname')");
    $firmendaten = mysql_fetch_array($firmendaten_abfrage);
    $firmenname = $firmendaten[0];
    $hauptsitz = $firmendaten[1];
    // Wenn noch keine Firma gegründet wurde
    if ($firmenname == "" OR $hauptsitz == ""){
        include ("../intern/neugruendung.php");
        exit;
    }
?>