<?php session_start();
require_once ('DB.php');

// z.b. url: "../inc/ajaxTest.php?class=db&function=getCoachByClub&id=" + int_ClubID,

if(isset($_GET['class'])){
	$class=$_GET['class'];
	if(isset($_GET['function'])){
		$function=$_GET['function'];
		if(isset($_GET['id']))
			$id=$_GET['id'];
		else
			$id=false;
	}	
	else
		$function=false;
}
else if (isset($_POST['class'])){
	$class=$_POST['class'];
	if(isset($_POST['function'])){
		$function=$_POST['function'];
		if(isset($_GET['id']))
			$id=$_GET['id'];
		else
			$id=false;
	}
	else
		$function=false;
}
else
	$class=false;

if($class!=false&&$function!=false){
	$class=strtolower($class);
	switch($class){
		case 'db':	
			$result = DB($function, $id);		
			echo $result;
	}
}

function DB($function, $id){
	switch($function){
		case 'getCoachByClub':
			$db=new DB();
			if ($id == 0)
				$id = $_SESSION['clubID'];
			$result=$db->getCoachByClub($id);
			echo json_encode($result);
			exit();
		case 'getTeamByID':
			$db=new DB();
			if ($id == 0){
				$allCoaches = $db->getCoachByID($_SESSION['coachID']);
				// Wenn Mannschaft zugewiesen
				if (isset($allCoaches['team'][0]))
					$id = $allCoaches['team'][0];
			}
			$result=$db->getTeamByID($id);
			echo json_encode($result);
			exit();
		case 'getUserByID':
			$db=new DB();
			$result=$db->getUserByID($id);
			echo json_encode($result);
			exit();
		case 'getTeamByClub':
			$db=new DB();
			if ($id == 0)
				$id = $_SESSION['clubID'];
			$result=$db->getTeamByClub($id);
			echo json_encode($result);
			exit();
		case 'getCoachByTeam':
			$db=new DB();
			$result=$db->getCoachByTeam($id);
			echo json_encode($result[0]);
			exit();
		case 'getCoachByID':
			$db=new DB();
			$result=$db->getCoachByID($id);
			echo json_encode($result);
			exit();
		case 'getPlayerByID':
			$db=new DB();
			$result=$db->getPlayerByID($id);
			echo json_encode($result);
			exit();
		case 'saveLineup':
			$aktiveSpieler=$_POST['aktiveSpieler'];
			$taktik=$_POST['taktik'];
			$teamid=$_POST['teamid'];
			$db=new DB();
			$result=$db->saveLineup($teamid, $taktik, $aktiveSpieler);
			echo $result;
			exit();
	}
}
?>