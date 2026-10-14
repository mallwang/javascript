<?php
// DB-Abfrage Normal mit SELECT
function getProperty(){
    $args=func_get_args();
    $len=sizeof($args);

    $spalte=implode(",",array_slice($args, 0, $len-3));
    $tabelle=$args[$len-3];
    $suchspalte=$args[$len-2];
    $wert=$args[$len-1];

    $result = mysql_query("SELECT $spalte FROM $tabelle WHERE $suchspalte = '$wert'");
    return $abfrage = mysql_fetch_row($result);// OR die("Fehler: $abfrage <br>".mysql_error());
}

// DB-Abfrage mit LIKE
function getPropertyLike(){
    $args=func_get_args();
    $len=sizeof($args);

    $spalte=implode(",",array_slice($args, 0, $len-3));
    $tabelle=$args[$len-3];
    $suchspalte=$args[$len-2];
    $wert=$args[$len-1];

    $result = mysql_query("SELECT $spalte FROM $tabelle WHERE $suchspalte LIKE '$wert'");
    return $abfrage = mysql_fetch_row($result);// OR die("Fehler: $abfrage <br>".mysql_error());
}

// DB-Eintrag neu mit INSERT INTO
function setProperty(){
    $args=func_get_args();
    $len=sizeof($args);
    
    $tabelle=$args[0];
    $spalte=implode(",",array_slice($args, 1, (int)(($len-1)/2)));
    $wert=implode("','",array_slice($args, (int)(($len+1)/2), $len));
    
    mysql_query("INSERT INTO $tabelle ($spalte) VALUES ('$wert')") OR die("Fehler: ".mysql_error());
}

// DB-Eintrag bereits vorhanden mit UPDATE
function updateProperty(){
    $args=func_get_args();
    $len=sizeof($args);
    
    $tabelle=$args[0];
    $set="";
    for ($i=1;$i<$len-2;$i+=2)
    {
        $set=$set.$args[$i]."='".$args[$i+1]."',";
    }
    $set = substr($set, 0, -1);
    $spalte=$args[$len-2];
    $wert=$args[$len-1];
    
    mysql_query("UPDATE $tabelle SET $set WHERE $spalte = '$wert'") or die ("Kein Eintrag!");
}

// DB-Abfrage einfach
function anfrage ($spalte, $tabelle, $suchspalte, $wert){
    $result = mysql_query("SELECT $spalte FROM $tabelle WHERE $suchspalte = '$wert'");
    return $result;
}

// Zählen von Datensätzen einer DB-Abfrage
function numROWs ($result){
    return mysql_num_rows($result);  
}

// DB-Abfrage der id des zugehörigen Namens
function id ($name){
    $a = getProperty('id', 'user', 'name', $name);
    return $a[0];
}

// DB-Abfrage des Namens der zugehörigen id
function name ($id){
    $a = getProperty('name', 'user', 'id', $id);
    return $a[0];
}

// DB-Abfrage der E-Mail der zugehörigen id
function email ($id){
    $a = getProperty('email', 'user', 'id', $id);
    return $a[0];
}

// Prüfung der E-Mail auf Plausibilität
function check_email($email){
    // Nur ASCII-Charakters
    $nonascii      = "\x80-\xff";
    
    $nqtext        = "[^\\\\$nonascii\015\012\"]";
    $qchar         = "\\\\[^$nonascii]";
    
    $normuser      = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
    $quotedstring  = "\"(?:$nqtext|$qchar)+\"";
    $user_part     = "(?:$normuser|$quotedstring)";
    
    $dom_mainpart  = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
    $dom_subpart   = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
    $dom_tldpart   = '[a-zA-Z]{2,5}';
    $domain_part   = "$dom_subpart$dom_mainpart$dom_tldpart";
    
    $regex         = "$user_part\@$domain_part";
    // RegEx end
    
    return preg_match("/^$regex$/",$email);
}

// Prüfung des Passwortes auf seine Stärke
function pwPruefung ($passwort){
    $staerke = 0;
        // Prüfen ob Kleinuchstaben enthalten sind
	if (preg_match( "/[a-z]+/", $passwort )) {$staerke++;}
        
	// Prüfen ob Großbuchstaben enthalten sind
	if (preg_match( "/[A-Z]+/", $passwort )) {$staerke++;}
        
	// Prüfen ob Zahlen enthalten sind
	if (preg_match( "/\d+/", $passwort )) {$staerke++;}
        
	// Prüfen ob Sonderzeichen enthalten sind
	if (preg_match( "/\W+/", $passwort )) {$staerke++;}
    return $staerke;
}
?>