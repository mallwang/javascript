<?php

if(isset($_GET['class']))
{
	$class=$_GET['class'];
	if(isset($_GET['function']))
	{
		$function=$_GET['function'];
	}	
	else 
	{
		$function=false;
	}
}
else if (isset($_POST['class']))
{
	$class=$_POST['class'];
	if(isset($_POST['function']))
	{
		$function=$_POST['function'];
	}
	else
	{
		$function=false;
	}
}
else
{
	$class=false;
}

if($class!=false&&$function!=false)
{
	$class=strtolower($class);
	switch($class)
	{
		case db:	
			$result=DB($function);		
			return $result;
	}
}
else 
{
	return false;
}

function DB($function){
	$function=strtolower($function);
	$db = new DB();
	switch($function)
	{
		//####Player####
		case "getplayerbyid":
			if(isset($_GET['id'])&&$_GET['id']!='')
			{
				$content = $db -> getPlayerByID($id);
			}
			else 
			{
				$content='false';
			}			
			break;
		case "getplayerbyteam":
			if(isset($_GET['id'])&&$_GET['id']!='')
			{
				$content = $db -> getPlayerByTeam($id);
			}
			else
			{
				$content='false';
			}
			break;
 		case "getplayerbyclub":
 			if(isset($_GET['id'])&&$_GET['id']!='')
 			{
				$content = $db -> getPlayerByClub($id);
			}
			else
			{
				$content='false';
			}
 			break;
 		//####Coach####
 		case "getcoachbyid":
 			if(isset($_GET['id'])&&$_GET['id']!='')
 			{
				$content = $db -> getCoachByID($id);
			}
			else
			{
				$content='false';
			}
 			break;
 		case "getcoachbyteam":
 			if(isset($_GET['id'])&&$_GET['id']!='')
 			{
				$content = $db -> getCoachByTeam($id);
			}
			else
			{
				$content='false';
			}
 			break;
 		case "getcoachbyclub":
 			if(isset($_GET['id'])&&$_GET['id']!='')
 			{
				$content = $db -> getCoachByClub($id);
			}
			else
			{
				$content='false';
			}
 			break;
 		//####Team####
 		case "getteambyid":
 			if(isset($_GET['id'])&&$_GET['id']!='')
 			{			
				$content = $db -> getTeamByID($id);
			}
			else
			{
				$content='false';
			}			
 			break;
 		case "getteambyclub":
 			if(isset($_GET['id'])&&$_GET['id']!='')
 			{ 			
 				$content = $db -> getTeamByClub($id);
 			}
 			else
 			{
 				$content='false';
 			} 			
 			break;
 		//####User####
 		case "createuser": 
 			if(isset($_GET['userEmail'])&&$_GET['userEmail']!=''&&isset($_GET['userPassword'])&&$_GET['userPassword']!=''&&isset($_GET['userNickname'])&&$_GET['userNickname']!=''&&isset($_GET['userRights'])&&$_GET['userRights']!='')
 			{			
 				$content = $db -> createUser($_GET['userEmail'],$_GET['userPassword'],$_GET['userNickname'],$_GET['userRights']);
 			}
 			else
 			{
 				$content='false';
 			} 			
 			break;
		default:
			$content = 'false';
			break;
	}
	return $content;
	
}

?>