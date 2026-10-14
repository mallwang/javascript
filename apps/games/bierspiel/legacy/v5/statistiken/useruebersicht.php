<?php $title="STATISTIK"; include("../huelle/header.php");

$a = $_GET['a'];
$b = $_GET['b'];
$suche = $_GET['suche'];
$richtung = $_GET['richtung'];

$rangliste = "SELECT user.id, user.nickname, firmendaten.bargeld, firmendaten.konto FROM user INNER JOIN firmendaten ON user.id = firmendaten.user_id ORDER BY $suche $richtung LIMIT $a, $b";
// echo $rangliste;
$rangliste2 = mysql_query($rangliste);
$h = mysql_query("SELECT * FROM user");
$gesamt = mysql_num_rows($h);

?>
<br>
<div align="center">
            <h2>User&uuml;bersicht</h2>
            <h3>Gesamtzahl: <?php echo $gesamt;?></h3>
</div>
<br><br>

<table class="bordered">
            <thead class="span12" align="center">
                
                <tr>
                    <th class="right">Nr</th>
                    <th>
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=nickname&richtung=ASC'>
                            <img title='Aufw&auml;rts sortieren' src='../images/Pfeil_oben.jpg' alt='Aufw&auml;rts sortieren'/>
                        </a> Name
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=nickname&richtung=DESC'>
                        <img title='Abw&auml;rts sortieren' src='../images/Pfeil_unten.jpg' alt='Abw&auml;rts sortieren'/>
                        </a>
                    </th>
                    <th class="right">
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=id&richtung=ASC'>
                            <img title='Aufw&auml;rts sortieren' src='../images/Pfeil_oben.jpg' alt='Aufw&auml;rts sortieren'/>
                        </a> ID
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=id&richtung=DESC'>
                        <img title='Abw&auml;rts sortieren' src='../images/Pfeil_unten.jpg' alt='Abw&auml;rts sortieren'/>
                        </a>
                    </th>
                    <th class="right">
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=bargeld&richtung=ASC'>
                            <img title='Aufw&auml;rts sortieren' src='../images/Pfeil_oben.jpg' alt='Aufw&auml;rts sortieren'/>
                        </a> Bargeld
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=bargeld&richtung=DESC'>
                        <img title='Abw&auml;rts sortieren' src='../images/Pfeil_unten.jpg' alt='Abw&auml;rts sortieren'/>
                        </a>
                    </th>
                    <th class="right">
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=konto&richtung=ASC'>
                            <img title='Aufw&auml;rts sortieren' src='../images/Pfeil_oben.jpg' alt='Aufw&auml;rts sortieren'/>
                        </a> Kontoguthaben
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=konto&richtung=DESC'>
                        <img title='Abw&auml;rts sortieren' src='../images/Pfeil_unten.jpg' alt='Abw&auml;rts sortieren'/>
                        </a>
                    </th>
                    <th class="right">
                        Nachricht
                    </th>
                </tr>
            </thead>
            <tbody class="span12" >
                
<?php
$nr = $a + 1;
while($row = mysql_fetch_object($rangliste2))
{
$nickname = $row->nickname;
$id = $row->id;
$bargeld = $row->bargeld;
$konto = $row->konto;

echo "<tr>
    <td>$nr</td>
    <td>$nickname</td>
    <td class='right'>$id</td>
    <td class='right'>$bargeld</td>
    <td class='right'>$konto</td>
    <td class='right'><a href='new_mail.php?nickname=$nickname&betreff=&text='>Nachricht verfassen</a></td>
    </tr>";
$nr = $nr + 1;
}

if ($a == 0){
    $zurueck = "";
}
else{
    $az = $a - 10;
    $zurueck = "<a href='useruebersicht.php?a=$az&b=$b&suche=$suche&richtung=$richtung'>Zur&uuml;ck</a>";
    
}

if (mysql_num_rows($rangliste2) == 10){
    $aw = $a + 10;
    $weiter = "<a href='useruebersicht.php?a=$aw&b=$b&suche=$suche&richtung=$richtung'>Weiter</a>";
}
else{
    $weiter = "";
}

echo "<tr><td>$zurueck</td><td>$weiter</td></tr>";
echo "</tbody><tfoot></tfoot></table>";
include("../huelle/footer.php");
?>