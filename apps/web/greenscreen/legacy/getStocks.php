<?php
// URL auslesen
$data = file_get_contents("http://download.finance.yahoo.com/d/quotes.csv?s=ADS.DE,ALV.DE,BAS.DE,BAYN.DE,BEI.DE,BMW.DE,CBK.DE,CON.DE,DAI.DE,DBK.DE,DB1.DE,DPW.DE,DTE.DE,EOAN.DE,FME.DE,FRE.DE,HEI.DE,HEN3.DE,IFX.DE,SDF.DE,LXS.DE,LIN.DE,LHA.DE,MRK.DE,MUV2.DE,RWE.DE,SAP.DE,SIE.DE,TKA.DE,VOW3.DE&f=nl1c&e=.csv");
$rows = explode("\n",$data);
$s = array();
foreach($rows as $row) {
    $s[] = str_getcsv($row);
}
$return = "";
for($i = 0; $i<count($s); $i++){
    for($j = 0; $j<count($s[$i]); $j++){
        // Buchstabe "N" am Ende des Strings entfernen
        if ($j == 0){
            if (strpos($s[$i][$j]," N") == true){
                $s[$i][$j] = trim($s[$i][$j], " N");
            }
        }
        // Veränderungen in Klammern setzen
        if ($j == 2){
            if (substr($s[$i][$j], 0, 1) == "+"){
                $return = $return . " (<green>" . $s[$i][$j] . "</green>) | ";
            }
            else{
                $return = $return . " (<red>" . $s[$i][$j] . "</red>) | ";
            }
        }
        // String zusammenbauen
        else{
            $return = $return . " " . $s[$i][$j];
        }
    }
}
// Punkt durch Kommma ersetzen
$return = str_replace(".", ",", $return);
// "-" durch "/" ersetzen
$return = str_replace(" - ", " / ", $return);
// Ende abschneiden
$return = trim($return, " |");
// "BOERSE" durch "BÖRSE" ersetzen
$return = str_replace("BOERSE", "BÖRSE", $return);
// "E,O" durch "EON" ersetzen
$return = str_replace("E,O", "E.ON", $return);
// "HEIDELBERGCEMENT" durch "HEIDELBERGER ZEMENT" ersetzen
$return = str_replace("HEIDELBERGCEMENT", "HEIDELBERGER ZEMENT", $return);
// "HENKEL VZ" durch "HENKEL" ersetzen
$return = str_replace("HENKEL VZ", "HENKEL", $return);
// "INFINEON TECHNOLO" durch "INFINEON" ersetzen
$return = str_replace("INFINEON TECHNOLO", "INFINEON", $return);
// "DT,LUFTHANSA" durch "LUFTHANSA" ersetzen
$return = str_replace("DT,LUFTHANSA", "LUFTHANSA", $return);
// "MUENCH RUECKVERS" durch "MUNICH RE" ersetzen
$return = str_replace("MUENCH RUECKVERS", "MUNICH RE", $return);
// "VOLKSWAGEN VZ" durch "VOLKSWAGEN" ersetzen
$return = str_replace("VOLKSWAGEN VZ", "VOLKSWAGEN", $return);

echo $return;
?>