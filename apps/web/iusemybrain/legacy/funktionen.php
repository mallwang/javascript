<?php include 'connect.php';


/*********************************************************************************
 *********************************************************************************
 ***************************** Datenbank Funktionen ******************************
 *********************************************************************************
 ********************************************************************************/

// DB-Anfrage kommt rein
function anfrage($query){
// 	echo $query;
	// Query wird geprüft, ob Select oder anderes Statement
	$goodQuery = mysql_escape_string($query);
	$result = checkQuery ($query);
	if ($result){
		return $result;
	}
	else{
		return false;
	}
}

// Pruefung der Query ob Select oder anderes Statement
function checkQuery ($goodQuery){
	// echo $query;
	// Wenn ein Select darin zu finden ist
	if (preg_match("/^select/i", $goodQuery)){
// 		echo "inIfCheckQuery";
		// Die Query wird weitergeleitet zum Select
		$result = doSelect ($goodQuery);
		if ($result){
// 			echo "inTrue";
			return $result;
		}
		else{
// 			echo "inFalse";
			return false;
		}
	}
	else{
// 		echo "inElseCheckQuery";
		// Die Query wird weitergeleitet zum Bearbeiten
		$result = doQuery ($goodQuery);
// 		echo $goodQuery;
		if ($result){
			return $result;
		}
		else{
			//echo "Fehler bei der Anfrage! Keine Datensätze geändert";
		}
	}
}

// Das Statement wird verarbeitet
function doQuery ($goodQuery){
	//echo $query;
	if ($result = mysql_query ($goodQuery)){
		$aff_rows = mysql_affected_rows();
		if ($aff_rows){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		die (mysql_error());
	}
}

// Das Select wird verarbeitet
function doSelect($goodQuery){
// 	echo $goodQuery;
	$result = mysql_query($goodQuery);
	// echo $result;
	$a = mysql_fetch_array($result, MYSQL_NUM);
	if($result){
		return $a;
	}
	else{
		return false;
	}
}

?>