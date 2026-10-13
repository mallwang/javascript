<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamID = mysql_real_escape_string($_POST['teamID']);
$dateFrom = date_format(date_create(mysql_real_escape_string($_POST['dateFrom'])), "Y-m-d");
$dateTo = date_format(date_create(mysql_real_escape_string($_POST['dateTo'])), "Y-m-d");
$appointmentType = mysql_real_escape_string($_POST['appointmentType']);
$email = $_SESSION['email'];
$userID = $_SESSION['userID'];
// DB Abfrage nach der eventID im gewählten Zeitraum
$EventDateTime = mysql_query("SELECT `eventID`,`eventStartDate`, `eventStartTime` FROM eventtab WHERE `teamID` = '$teamID' AND `eventType`= '$appointmentType' AND `eventStartDate` >='$dateFrom' AND `eventEndDate` <='$dateTo'");
$appIDdatetime = array();
$datetimecount=0;
$i=0;
while($eventRow=mysql_fetch_array($EventDateTime)){
	$date1_tmp = date_create($eventRow[1]);
	$date2_tmp = date_create($eventRow[2]);
	$date_tmp="".date_format($date1_tmp, 'd.m.Y') ." ".date_format($date2_tmp, 'H:i');
	$datetime =array("0"=>$eventRow[0],"1"=>$date_tmp);
	$appIDdatetime[$datetimecount] = $datetime;
	$datetimecount++;	
}
if (count($appIDdatetime)>0){
	$appID=array();
	while($i < count($appIDdatetime)){
		$appID[$i]=$appIDdatetime[$i][0];
		$i++;
	}

	$mySQL_IN="";
	foreach($appID as $eventID) {
		$mySQL_IN.=$eventID. "','";
	}

	$DatenEventPlayerValue = mysql_query("SELECT `eventID`, `playerID`, `attendanceValue` FROM attendancetab WHERE `eventID` IN ('".substr($mySQL_IN,0,strlen($mySQL_IN)-3)."')");
	$appointment = array();
	$eventCount = 0;
	$i=0;
	// Für jede gefundene eventID das dazugehörige Event abfragen
	while($eventRow=mysql_fetch_array($DatenEventPlayerValue)){
		$event = array("appID" => $eventRow[0], "playerID" => $eventRow[1], "attendance" => $eventRow[2]);
		$appointment[$eventCount] = $event;
		$eventCount++;
	}
	//	##########BEISPIEL- $APPOINTMENT
	// 	Anwensenheit 0 = nicht anwesend; 1 = anwesend; 2 = entschuldigt
	
	// 		$appointment[0]['appID'] = "1";
	// 		$appointment[0]['playerID'] = "11";
	// 		$appointment[0]['attendance'] = "1";
	
	// 		$appointment[1]['appID'] = "2";
	// 		$appointment[1]['playerID'] = "12";
	// 		$appointment[1]['attendance'] = "1";
	
	// 		$appointment[2]['appID'] = "2";
	// 		$appointment[2]['playerID'] = "11";
	// 		$appointment[2]['attendance'] = "2";
	
	// 		$appointment[3]['appID'] = "1";
	// 		$appointment[3]['playerID'] = "23";
	// 		$appointment[3]['attendance'] = "0";
	
	// 		$appointment[4]['appID'] = "2";
	// 		$appointment[4]['playerID'] = "123";
	// 		$appointment[4]['attendance'] = "2";
	
	// Array mappen
	$playerByID = array_map(function($player){return $player['playerID'];}, $appointment);
	// 		$playerByID:
	// 		Man erhaelt: Array ( [0] => 11 [1] => 12 [2] => 11 [3] => 23 [4] => 123 )
	
	$appointmentByID = array_map(function($app){return $app['appID']; }, $appointment);
	// 		$appointmentByID:
	// 		Man erhaelt: Array ( [0] => 1 [1] => 2 [2] => 2 [3] => 1 [4] => 2 )
	
	$fullTeam = $db->getPlayerByTeam($teamID);
	
	$return='';
	############################################
	$return .= 	'<div class="anwesenheit">
					<table id="myTable" class="table striped hovered">
						<thead>
							<tr>
								<th class="text-left">Name</th>';
	$i=0;
	while ($i < count($appID)){
		if ($appID[$i] != ""){
			// Die Uebergabewerte fuer das Bearbeiten vorbereiten. Hier: alle Eintraege fuer den entsprechenden Termin
			$appCollindex=0;
			$appointmentCollection=array();
			$j=0;
			while($j< count($appointmentByID)){
				if ($appointmentByID[$j]==$appID[$i]){
					$appointmentCollection[$appCollindex]=$appointment[$j];
					$appCollindex++;
				}
				$j++;
			}
			// Terminarray durchlaufen und jeweils eine Tabellenspalte einfuegen
			$return .='<th class="text-left">'. $appIDdatetime[$i][1] .'<button onclick=' . "'" . 'showDialogEditAttendance(&quot;'. $appIDdatetime[$i][1] . '&quot;, ' . $appID[$i] .', '. json_encode($fullTeam) .', '. $appointmentType .', '. json_encode($appointmentCollection) .')' . "'" . ' style="background-color: transparent;"><i title="Anwesenheit bearbeiten" class="icon-pencil"></i></button></th>';
		}
		$i++;
	}
	
	$return .= '</tr></thead><tbody>';
	
	$i=0;
	if (count($fullTeam)>0){
		// Jeden Spieler des gewaehlten Teams in der Tabelle auflisten
		while ($i < count($fullTeam)){
			$return .= '<tr><td class="right">' . $fullTeam[$i]['playerFirstName'] . ' ' . $fullTeam[$i]['playerSecondName'];
			$j=0;
			$tmp_numappID=count($appID);
			while ($j< $tmp_numappID){
				// herausfinden, in welchen Termindaten ueberall der entsprechende Spieler auftaucht (bspw. beim Termin mit der appID "2" und beim Termin mit der appID "3")
				$playerArrayPosition=array_keys($playerByID, $fullTeam[$i]['playerID']);
				// $tmp_attandanceValueExists = Boolean um zu sehen, ob die Tabellenzelle schon befuellt worden ist
				$tmp_attandanceValueExists=false;
				// die einzelnen Termine in denen der Spieler nach dem gewuenschten Termin durchsuchen
				$k=0;
				while ($k < count($playerArrayPosition)){
					// Ueberpruefen, dass der gefundene Eintrag mit appID und playerID uebereinstimmt
					if ($appointment[$playerArrayPosition[$k]]['appID']==$appID[$j] && $appointment[$playerArrayPosition[$k]]['playerID']==$fullTeam[$i]['playerID']){
						$attendance=$appointment[$playerArrayPosition[$k]]['attendance'];
						if ($attendance=='0'){
							// nicht anwesend
							$tmp_attendance='icon-cancel-2';
						}
						elseif ($attendance=='1'){
							// anwesend
							$tmp_attendance='icon-checkmark';
						}
						elseif ($attendance=='2'){
							// entschuldigt
							$tmp_attendance='icon-comments-3';
						}
						else{
							// nicht gesetzt
							$tmp_attendance='';
						}
						// Wert in die Tabelle eintragen
						$return .= '</td><td class="right"><div class=" ' . $tmp_attendance .'"></div></td>';
						$tmp_attandanceValueExists=true;
						$k=count($playerArrayPosition);
					}
					$k++;
				}
				// wenn die Tabellenzelle noch nicht befuellt worden ist
				if (!$tmp_attandanceValueExists){
					$return .= '</td><td class="right"></td>';
				}
				$j++;
			}
			$return .='</tr>';
			$i++;
		}
		// Ende der Tabelle erstellen
		$return .= 		'</tbody><tfoot></tfoot></table> Legende:<br>
						<div class="icon-checkmark"> = anwesend &nbsp;&nbsp;&nbsp;&nbsp;</div>
						<div class="icon-comments-3"> = entschuldigt &nbsp;&nbsp;&nbsp;&nbsp;</div>
						<div class="icon-cancel-2"> = nicht anwesend </div>
					</div>';
	}
	else{
		$return = "keineSpieler";
	}
}
else{
	$return = "keinTermin";
}
############################################

echo $return;