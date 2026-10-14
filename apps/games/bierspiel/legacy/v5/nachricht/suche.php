<?php include("../huelle/connect.php");
//Array mit Daten beladen
$keywords[] = "";
$a = mysql_query("SELECT nickname FROM user");
while($row=mysql_fetch_object($a)){
    $keywords[] = $row->nickname;
}
//Suchbegriff aus der URL per GET filtern
$q=$_GET["q"];
//String-Länge ermitteln und falls groesser 0 -> weiter
if (strlen($q) > 0){
	$hint="";
	foreach($keywords as $keyword_aktuell) {	
  		if (strtolower($q)==strtolower(substr($keyword_aktuell,0,strlen($q)))) {
                    $hint=$hint.$keyword_aktuell;
                    ?>
                    <div class="ergebnis"><a onclick href="javascript:add_user('<?php echo $keyword_aktuell;?>')"><?php echo $keyword_aktuell;?></a></div>
                    <?php      		
    	}
  	}
}

//Checken, ob Eintraege gefunden wurden. Wenn nicht, Fehler ausgeben
if (empty($hint)) {
	$response="Leider keine Eintr&auml;ge";
}
else{
	$response="";
}

//Und nur noch das Ganze ausgeben
echo $response;
?>