<?php $title="BANK"; include("../huelle/header.php"); 

// aktueller User - Nickname
$nickname = $_SESSION['nickname'];
// aktueller User - ID
// id aktueller User
$abfrage_der_id = mysql_query("SELECT id FROM user WHERE nickname = '$nickname'");
$der_id = mysql_fetch_array($abfrage_der_id);
$id = $der_id[0];
// Grundstuecke des Users
$abfrage_finanzdaten = mysql_query("SELECT bargeld, konto, kredit FROM firmendaten WHERE user_id = '$id'");
$finanzdaten = mysql_fetch_array($abfrage_finanzdaten);
$bargeld = $finanzdaten[0];
$kontoguthaben = $finanzdaten[1];
$kredit = $finanzdaten[2];





?>
<ul class="listview image">
    <li class="bg-color-white fg-color-white">
        <div class="icon">
            <img src="../images/bank.jpg" />
        </div>
        <div class="data">
            <tr class="fg-color-green">
                <td class="fg-color-green">
                    FIRST_NAME
                </td>
                <td class="fg-color-green">
                    LAST_NAME
                </td>
            </tr>
            
            
            
            <div class="fg-color-black">Bargeld: <a align="right"><?php echo $bargeld;?></a></div>
            <div class="fg-color-black">Kontoguthaben: <a class="fg-color-green"><?php echo $kontoguthaben;?></a></div>
            <div class="fg-color-red">Kredit: <?php echo $kredit;?></div>
        </div>
    </li>
</ul>



<div align="center">
    <h1>IM AUFBAU... </h1><br>
    <img src="../images/bauarbeiter.png">
</div>

<br><br><br><br><br>
<?php include("../huelle/footer.php");?>