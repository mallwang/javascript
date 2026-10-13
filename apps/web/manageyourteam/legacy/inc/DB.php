<?php
// ----------------Datenbankklasse----------------
class DB {
	
	// Verbindungsvariablen
	var $host = 'DB_HOST';
	// var $host = 'DB_HOST';
	var $uid = 'DB_USER';
	var $pwd = 'DB_PASSWORD';
	var $db = 'DB_NAME';
	
	// sonstige Variablen
	private $clubTable = 'clubtab';
	private $coachTable = 'coachtab';
	private $eventTable = 'eventtab';
	private $locationTable = 'locationtab';
	private $matchTable = 'matchtab';
	private $parentTable = 'parenttab';
	private $playerMatchTable = 'playermatchtab';
	private $playerPositionTable = 'playerpositiontab';
	private $playerTable = 'playertab';
	private $playerTrainingTable = 'attendancetab';
	private $positionTable = 'positiontab';
	private $teamCoachTable = 'teamcoachtab';
	private $teamMatchTable = 'teammatchtab';
	private $teamTable = 'teamtab';
	private $teamTypeTable = 'teamtypetab';
	private $tournamentTable = 'tournamenttab';
	private $trainingTable = 'trainingtab';
	private $userTable = 'usertab';
	
	// ####Help functions####
	// Connection
	function getConnection() {
		$connection = mysql_connect ( $this->host, $this->uid, $this->pwd );
		if ($connection) {
			// Charakterset "utf8" definieren fï¿½r kommende Datenbankanfragen
			mysql_query ( "SET NAMES 'utf8'" );
			mysql_query ( "SET CHARACTER SET 'utf8'" );
			mysql_select_db ( $this->db );
			return true;
		} else {
			return false;
		}
	}
	
	// Select
	function createSelectQuery($column, $table, $where, $sort, $sortdirection) {
		$query = 'SELECT ';
		if ($column != '' && $table != '') {
			$query .= $column . ' FROM ' . $table;
			if ($where != '') {
				$query .= ' WHERE ' . $where;
			}
			if ($sort != '') {
				$query .= ' ORDER BY ' . $sort;
				if ($sortdirection != '') {
					$query .= ' ' . $sortdirection;
				}
			}
		} else {
			$query = false;
		}
		return $query;
	}
	
	// Insert
	function createInsertQuery($table, $columns, $values) {
		$query = 'INSERT INTO ';
		
		if ($table != '' && $columns != '' && $values != '' && count ( $columns ) == count ( $values )) {
			$queryColumns = '(';
			$queryValues = '(';
			$query .= $table . ' ';
			for($i = 0; $i < count ( $columns ); $i ++) {
				if ($i == count ( $columns ) - 1) {
					$queryColumns .= '`' . $columns [$i] . '`)';
					if (is_string ( $values [$i] )) {
						$queryValues .= '\'' . $values [$i] . '\')';
					} else {
						$queryValues .= $values [$i] . ')';
					}
				} else {
					$queryColumns .= '`' . $columns [$i] . '`,';
					if (is_string ( $values [$i] )) {
						$queryValues .= '\'' . $values [$i] . '\',';
					} else {
						$queryValues .= $values [$i] . ',';
					}
				}
			}
			$query .= $queryColumns . ' VALUES ' . $queryValues;
		} else {
			$query = false;
		}
		return $query;
	}
	
	// Update
	function createUpdateQuery($table, $columns, $values, $where) {
		$set = '';
		$query = 'UPDATE ';
		if ($table != '' && $columns != '' && $values != '' && count ( $columns ) == count ( $values ) && $where != '') {
			$query .= $table . ' SET ';
			for($i = 0; $i < count ( $columns ); $i ++) {
				$set .= '`' . $columns [$i] . '` = ';
				if ($i == count ( $columns ) - 1) {
					if (is_string ( $values [$i] )) {
						$set .= '\'' . $values [$i] . '\'';
					} else {
						$set .= '' . $values [$i] . '';
					}
				} else {
					if (is_string ( $values [$i] )) {
						$set .= '\'' . $values [$i] . '\',';
					} else {
						$set .= '' . $values [$i] . ',';
					}
				}
			}
			$query .= $set . ' WHERE ' . $where;
		}
		return $query;
	}
	
	// Delete
	function createDeleteQuery($table, $where) {
		$query = 'DELETE FROM ';
		if ($table != '' && $where != '') {
			$query .= $table . ' WHERE ' . $where;
		}
		return $query;
	}
	
	// ####Player functions####
	// Get functions
	function getPlayerByID($id) {
		if (is_numeric ( $id )) {
			$player = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerSelect = $this->createSelectQuery ( '*', $this->playerTable, 'playerID = ' . $id, '', '' );
				if ($playerSelect) {
					$playerResult = mysql_query ( $playerSelect );
					
					if ($playerResult) {
						while ( $playerRow = mysql_fetch_array ( $playerResult ) ) {
							$playerID = $playerRow ['playerID'];
							$userID = $playerRow ['userID'];
							$teamID = $playerRow ['teamID'];
							$clubID = $playerRow ['clubID'];
							$playerFirstName = $playerRow ['playerFirstName'];
							$playerSecondName = $playerRow ['playerSecondName'];
							$positionID = $playerRow ['positionID'];
							$playerBirthdate = $playerRow ['playerBirthdate'];
							$player = array (
									"playerID" => $playerID,
									"userID" => $userID,
									"teamID" => $teamID,
									"clubID" => $clubID,
									"playerFirstName" => $playerFirstName,
									"playerSecondName" => $playerSecondName,
									"positionID" => $positionID,
									"playerBirthdate" => $playerBirthdate 
							);
						}
					}
				}
			}
		} else {
			$player = false;
		}
		return $player;
	}
	function getPlayerByTeam($id) {
		if (is_numeric ( $id )) {
			$teamPlayer = false;
			$teamPlayer = array ();
			$playerCount = 0;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerSelect = $this->createSelectQuery ( '*', $this->playerTable, 'teamID = ' . $id, '', '' );
				if ($playerSelect) {
					$playerResult = mysql_query ( $playerSelect );
					
					if ($playerResult) {
						while ( $playerRow = mysql_fetch_array ( $playerResult ) ) {
							$player = '';
							$playerID = $playerRow ['playerID'];
							$userID = $playerRow ['userID'];
							$teamID = $playerRow ['teamID'];
							$clubID = $playerRow ['clubID'];
							$playerFirstName = $playerRow ['playerFirstName'];
							$playerSecondName = $playerRow ['playerSecondName'];
							$positionID = $playerRow ['positionID'];
							$playerBirthdate = $playerRow ['playerBirthdate'];
							$player = array (
									"playerID" => $playerID,
									"userID" => $userID,
									"teamID" => $teamID,
									"clubID" => $clubID,
									"playerFirstName" => $playerFirstName,
									"playerSecondName" => $playerSecondName,
									"positionID" => $positionID,
									"playerBirthdate" => $playerBirthdate 
							);
							$teamPlayer [$playerCount] = $player;
							$playerCount ++;
						}
					}
				}
			}
		} else {
			$teamPlayer = false;
		}
		return $teamPlayer;
	}
	function getPlayerByClub($id) {
		if (is_numeric ( $id )) {
			$clubPlayer = false;
			$clubPlayer = array ();
			$playerCount = 0;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerSelect = $this->createSelectQuery ( '*', $this->playerTable, 'clubID = ' . $id, '', '' );
				if ($playerSelect) {
					$playerResult = mysql_query ( $playerSelect );
					
					if ($playerResult) {
						while ( $playerRow = mysql_fetch_array ( $playerResult ) ) {
							$player = '';
							$playerID = $playerRow ['playerID'];
							$userID = $playerRow ['userID'];
							$teamID = $playerRow ['teamID'];
							$clubID = $playerRow ['clubID'];
							$playerFirstName = $playerRow ['playerFirstName'];
							$playerSecondName = $playerRow ['playerSecondName'];
							$positionID = $playerRow ['positionID'];
							$playerBirthdate = $playerRow ['playerBirthdate'];
							$player = array (
									"playerID" => $playerID,
									"userID" => $userID,
									"teamID" => $teamID,
									"clubID" => $clubID,
									"playerFirstName" => $playerFirstName,
									"playerSecondName" => $playerSecondName,
									"positionID" => $positionID,
									"playerBirthdate" => $playerBirthdate 
							);
							$clubPlayer [$playerCount] = $player;
							$playerCount ++;
						}
					}
				}
			}
		} else {
			$clubPlayer = false;
		}
		return $clubPlayer;
	}
	function getPlayerByUser($id) {
		if (is_numeric ( $id )) {
			$player = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerSelect = $this->createSelectQuery ( '*', $this->playerTable, 'userID = ' . $id, '', '' );
				if ($playerSelect) {
					$playerResult = mysql_query ( $playerSelect );
					
					if ($playerResult) {
						while ( $playerRow = mysql_fetch_array ( $playerResult ) ) {
							$playerID = $playerRow ['playerID'];
							$userID = $playerRow ['userID'];
							$teamID = $playerRow ['teamID'];
							$clubID = $playerRow ['clubID'];
							$playerFirstName = $playerRow ['playerFirstName'];
							$playerSecondName = $playerRow ['playerSecondName'];
							$positionID = $playerRow ['positionID'];
							$playerBirthdate = $playerRow ['playerBirthdate'];
							$player = array (
									"playerID" => $playerID,
									"userID" => $userID,
									"teamID" => $teamID,
									"clubID" => $clubID,
									"playerFirstName" => $playerFirstName,
									"playerSecondName" => $playerSecondName,
									"positionID" => $positionID,
									"playerBirthdate" => $playerBirthdate 
							);
						}
					}
				}
			}
		} else {
			$player = false;
		}
		return $player;
	}
	
	// Auf der Spielerseite -->Uebersicht ueber die eigene Mannschaft
	/*
	 * TODO: Diese Funtion beibehalten oder entfernen (kann durch die Kombination von getPlayerByID und getPlayerByTeam ersetzt werden)
	 */
	function getPlayersTeam($id) {
		if (is_numeric ( $id )) {
			$teamPlayer = array ();
			$playerCount = 0;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerSelect = "SELECT * FROM playertab WHERE teamID = (SELECT teamID FROM playertab WHERE userID = " . $id . ")";
				$playerResult = mysql_query ( $playerSelect );
				
				if ($playerResult) {
					while ( $playerRow = mysql_fetch_array ( $playerResult ) ) {
						$player = array ();
						$playerID = $playerRow ['playerID'];
						$userID = $playerRow ['userID'];
						$teamID = $playerRow ['teamID'];
						$clubID = $playerRow ['clubID'];
						$playerFirstName = $playerRow ['playerFirstName'];
						$playerSecondName = $playerRow ['playerSecondName'];
						$playerBirthdate = $playerRow ['playerBirthdate'];
						$player = array (
								"playerID" => $playerID,
								"userID" => $userID,
								"teamID" => $teamID,
								"clubID" => $clubID,
								"playerFirstName" => $playerFirstName,
								"playerSecondName" => $playerSecondName,
								"playerBirthdate" => $playerBirthdate 
						);
						$teamPlayer [$playerCount] = $player;
						$playerCount ++;
					}
				}
			}
		} else {
			$teamPlayer = false;
		}
		return $teamPlayer;
	}
	
	// Set functions
	function updatePlayer($id, $teamID, $clubID, $playerFirstName, $playerSecondName, $positionID, $userID, $userEmail, $userPassword, $userNickname, $userRights) {
		$updatePlayer = false;
		$updateUser = $this->updateUser ( $userID, $userEmail, $userPassword, $userNickname, $userRights );
		if ($updateUser) {
			if (is_numeric ( $id )) {
				$table = $this->playerTable;
				
				$i = 0;
				if ($teamID != '') {
					$columns [$i] = 'teamID';
					$values [$i] = $teamID;
					$i ++;
				}
				if ($clubID != '') {
					$columns [$i] = 'clubID';
					$values [$i] = $clubID;
					$i ++;
				}
				if ($playerFirstName != '') {
					$columns [$i] = 'playerFirstName';
					$values [$i] = $playerFirstName;
					$i ++;
				}
				if ($playerSecondName != '') {
					$columns [$i] = 'playerSecondName';
					$values [$i] = $playerSecondName;
					$i ++;
				}
				if ($positionID != '') {
					$columns [$i] = 'positionID';
					$values [$i] = $positionID;
				}
				$where = 'playerID = ' . $id;
				$updatePlayer = false;
				$con = $this->getConnection ();
				if ($con == true) {
					$playerUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
					if ($playerUpdate) {
						$updatePlayerResult = mysql_query ( $playerUpdate );
						if ($updatePlayerResult) {
							$updatePlayer = true;
						}
					}
				}
			} else {
				$updatePlayer = false;
			}
		}
		return $updatePlayer;
	}
	
	// Create functions
	function createPlayer($userEmail, $userPassword, $userNickname, $teamID, $clubID, $playerFirstName, $playerSecondName, $playerBirthdate, $positionID) {
		$createPlayer ['result'] = false;
		$userRights = 2;
		$user = $this->createUser ( $userEmail, $userPassword, $userNickname, $userRights );
		if ($user ['result']) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'userID',
						1 => 'teamID',
						2 => 'clubID',
						3 => 'playerFirstName',
						4 => 'playerSecondName',
						5 => 'playerBirthdate',
						6 => 'positionID' 
				);
				$values = array (
						0 => $user ['userID'],
						1 => $teamID,
						2 => $clubID,
						3 => $playerFirstName,
						4 => $playerSecondName,
						5 => $playerBirthdate,
						6 => $positionID 
				);
				$playerInsert = $this->createInsertQuery ( $this->playerTable, $columns, $values );
				if ($playerInsert) {
					$playerResult = mysql_query ( $playerInsert );
					if ($playerResult) {
						$playerID = mysql_insert_id ();
						$playerSelect = $this->getPlayerByID ( $playerID );
						if ($playerSelect) {
							$clubSelect = $this->getClubByID ( $playerSelect ['clubID'] );
							$clubName = $clubSelect ['clubName'];
							$createPlayer ['result'] = true;
							// Userdaten
							$createPlayer ['userID'] = $user ['userID'];
							$createPlayer ['userEmail'] = $user ['userEmail'];
							$createPlayer ['userNickname'] = $user ['userNickname'];
							$createPlayer ['userKey'] = $user ['userKey'];
							$createPlayer ['userRights'] = $user ['userRights'];
							$createPlayer ['userActive'] = $user ['userActive'];
							$createPlayer ['userTimestamp'] = $user ['userTimestamp'];
							// Playerdaten
							$createPlayer ['playerID'] = $playerSelect ['playerID'];
							$createPlayer ['teamID'] = $playerSelect ['teamID'];
							$createPlayer ['clubID'] = $playerSelect ['clubID'];
							$createPlayer ['playerFirstName'] = $playerSelect ['playerFirstName'];
							$createPlayer ['playerSecondName'] = $playerSelect ['playerSecondName'];
							$createPlayer ['positionID'] = $playerSelect ['positionID'];
							$createPlayer ['playerBirthdate'] = $playerSelect ['playerBirthdate'];
							// Mail generieren und senden
							$this->generateMail ( $clubName, $user ['userKey'], $user ['userEmail'] );
						} else {
							// falls Club nicht eingefï¿½gt User wieder lï¿½schen
							$this->deleteUserByID ( $user ['userID'] );
						}
					} else {
						// falls Club nicht eingefï¿½gt User wieder lï¿½schen
						$this->deleteUserByID ( $user ['userID'] );
					}
					// mysql_close();
				}
			}
		} else {
			$createPlayer ['result'] = false;
		}
		return $createPlayer;
	}
	
	// Delete functions
	function deletePlayerByID($id) {
		$deletePlayer = false;
		if (is_numeric ( $id )) {
			$table = $this->playerTable;
			$where = 'playerID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$oldPlayer = $this->getPlayerByID ( $id );
				if ($oldPlayer != false) {
					$userID = $oldPlayer ['userID'];
					$userDelete = $this->deleteUserByID ( $userID );
					$playerDelete = $this->createDeleteQuery ( $table, $where );
					if ($playerDelete) {
						$deletePlayerResult = mysql_query ( $playerDelete );
						if ($deletePlayerResult) {
							$deletePlayer = true;
						}
					}
				}
			}
		} 

		else {
			$deletePlayer = false;
		}
		return $deletePlayer;
	}
	
	// ####Coach functions####
	// Get functions
	function getCoachByID($id) {
		if (is_numeric ( $id )) {
			$coach = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$coachSelect = $this->createSelectQuery ( '*', $this->coachTable, 'coachID = ' . $id, '', '' );
				if ($coachSelect) {
					$coachResult = mysql_query ( $coachSelect );
					if ($coachResult) {
						while ( $coachRow = mysql_fetch_array ( $coachResult ) ) {
							$coachID = $coachRow ['coachID'];
							$userID = $coachRow ['userID'];
							$clubID = $coachRow ['clubID'];
							// Teams auslesen
							$team = '';
							$teamSelect = $this->createSelectQuery ( '*', $this->teamCoachTable, 'coachID = ' . $id, '', '' );
							if ($teamSelect) {
								$teamResult = mysql_query ( $teamSelect );
								
								if ($teamResult) {
									$teamCount = 0;
									while ( $teamRow = mysql_fetch_array ( $teamResult ) ) {
										$team [$teamCount] = $teamRow ['teamID'];
										$teamCount ++;
									}
								}
							}
							$coachFirstName = $coachRow ['coachFirstName'];
							$coachSecondName = $coachRow ['coachSecondName'];
							$coach = array (
									"coachID" => $coachID,
									"userID" => $userID,
									"clubID" => $clubID,
									"coachFirstName" => $coachFirstName,
									"coachSecondName" => $coachSecondName,
									"team" => $team 
							);
						}
					}
				}
			}
		} else {
			$coach = false;
		}
		return $coach;
	}
	function getCoachByTeam($id) {
		if (is_numeric ( $id )) {
			$teamCoach = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$coachSelect = $this->createSelectQuery ( '*', $this->coachTable, 'coachID IN (' . $this->createSelectQuery ( 'coachID', $this->teamCoachTable, 'teamID = ' . $id, '', '' ) . ')', '', '' );
				if ($coachSelect) {
					$coachResult = mysql_query ( $coachSelect );
					if ($coachResult) {
						$coachCount = 0;
						while ( $coachRow = mysql_fetch_array ( $coachResult ) ) {
							$coach = '';
							$coachID = $coachRow ['coachID'];
							$userID = $coachRow ['userID'];
							$clubID = $coachRow ['clubID'];
							// Teams auslesen
							$team = '';
							$teamSelect = $this->createSelectQuery ( '*', $this->teamCoachTable, 'coachID = ' . $coachID, '', '' );
							if ($teamSelect) {
								$teamResult = mysql_query ( $teamSelect );
								
								if ($teamResult) {
									$teamCount = 0;
									while ( $teamRow = mysql_fetch_array ( $teamResult ) ) {
										$team [$teamCount] = $teamRow ['teamID'];
										$teamCount ++;
									}
								}
							}
							$coachFirstName = $coachRow ['coachFirstName'];
							$coachSecondName = $coachRow ['coachSecondName'];
							$coach = array (
									"coachID" => $coachID,
									"userID" => $userID,
									"clubID" => $clubID,
									"coachFirstName" => $coachFirstName,
									"coachSecondName" => $coachSecondName,
									"team" => $team 
							);
							$teamCoach [$coachCount] = $coach;
							$coachCount ++;
						}
					}
				}
			}
		} else {
			$teamCoach = false;
		}
		return $teamCoach;
	}
	function getCoachByClub($id) {
		if (is_numeric ( $id )) {
			$clubCoach = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$coachSelect = $this->createSelectQuery ( '*', $this->coachTable, 'clubID = ' . $id, '', '' );
				if ($coachSelect) {
					$coachResult = mysql_query ( $coachSelect );
					if ($coachResult) {
						$coachCount = 0;
						while ( $coachRow = mysql_fetch_array ( $coachResult ) ) {
							$coach = '';
							$coachID = $coachRow ['coachID'];
							$userID = $coachRow ['userID'];
							$clubID = $coachRow ['clubID'];
							// Teams auslesen
							$team = '';
							$teamSelect = $this->createSelectQuery ( '*', $this->teamCoachTable, 'coachID = ' . $coachID, '', '' );
							if ($teamSelect) {
								$teamResult = mysql_query ( $teamSelect );
								if ($teamResult) {
									$teamCount = 0;
									while ( $teamRow = mysql_fetch_array ( $teamResult ) ) {
										$team [$teamCount] = $teamRow ['teamID'];
										$teamCount ++;
									}
								}
							}
							$coachFirstName = $coachRow ['coachFirstName'];
							$coachSecondName = $coachRow ['coachSecondName'];
							$coach = array (
									"coachID" => $coachID,
									"userID" => $userID,
									"clubID" => $clubID,
									"coachFirstName" => $coachFirstName,
									"coachSecondName" => $coachSecondName,
									"team" => $team 
							);
							$clubCoach [$coachCount] = $coach;
							$coachCount ++;
						}
					}
				}
			}
		} else {
			$clubCoach = false;
		}
		return $clubCoach;
	}
	function getCoachByUser($id) {
		if (is_numeric ( $id )) {
			$coach = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$coachSelect = $this->createSelectQuery ( '*', $this->coachTable, 'userID = ' . $id, '', '' );
				if ($coachSelect) {
					$coachResult = mysql_query ( $coachSelect );
					if ($coachResult) {
						while ( $coachRow = mysql_fetch_array ( $coachResult ) ) {
							$coachID = $coachRow ['coachID'];
							$userID = $coachRow ['userID'];
							$clubID = $coachRow ['clubID'];
							// Teams auslesen
							$team = '';
							$teamSelect = $this->createSelectQuery ( '*', $this->teamCoachTable, 'coachID = ' . $coachID, '', '' );
							if ($teamSelect) {
								$teamResult = mysql_query ( $teamSelect );
								
								if ($teamResult) {
									$teamCount = 0;
									while ( $teamRow = mysql_fetch_array ( $teamResult ) ) {
										$team [$teamCount] = $teamRow ['teamID'];
										$teamCount ++;
									}
								}
							}
							$coachFirstName = $coachRow ['coachFirstName'];
							$coachSecondName = $coachRow ['coachSecondName'];
							$coach = array (
									"coachID" => $coachID,
									"userID" => $userID,
									"clubID" => $clubID,
									"coachFirstName" => $coachFirstName,
									"coachSecondName" => $coachSecondName,
									"team" => $team 
							);
						}
					}
				}
			}
		} else {
			$coach = false;
		}
		return $coach;
	}
	
	// Set functions
	function updateCoach($id, $clubID, $coachFirstName, $coachSecondName, $userID, $userEmail, $userPassword, $userNickname, $userRights) {
		$updateCoach = false;
		$updateUser = $this->updateUser ( $userID, $userEmail, $userPassword, $userNickname, $userRights );
		if ($updateUser) {
			if (is_numeric ( $id )) {
				$table = $this->coachTable;
				
				$i = 0;
				if ($clubID != '') {
					$columns [$i] = 'clubID';
					$values [$i] = $clubID;
					$i ++;
				}
				if ($coachFirstName != '') {
					$columns [$i] = 'coachFirstName';
					$values [$i] = $coachFirstName;
					$i ++;
				}
				if ($coachSecondName != '') {
					$columns [$i] = 'coachSecondName';
					$values [$i] = $coachSecondName;
					$i ++;
				}
				$where = 'coachID = ' . $id;
				$con = $this->getConnection ();
				if ($con == true) {
					$coachUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
					if ($coachUpdate) {
						$updateCoachResult = mysql_query ( $coachUpdate );
						if ($updateCoachResult) {
							$updateCoach = true;
						}
					}
				}
			} else {
				$updateCoach = false;
			}
		}
		return $updateCoach;
	}
	
	// Create functions
	function createCoach($userEmail, $userPassword, $userNickname, $clubID, $coachFirstName, $coachSecondName) {
		$createCoach ['result'] = false;
		$userRights = 1;
		$user = $this->createUser ( $userEmail, $userPassword, $userNickname, $userRights );
		if ($user ['result']) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'userID',
						1 => 'clubID',
						2 => 'coachFirstName',
						3 => 'coachSecondName' 
				);
				$values = array (
						0 => $user ['userID'],
						1 => $clubID,
						2 => $coachFirstName,
						3 => $coachSecondName 
				);
				$coachInsert = $this->createInsertQuery ( $this->coachTable, $columns, $values );
				if ($coachInsert) {
					$coachResult = mysql_query ( $coachInsert );
					if ($coachResult) {
						$coachID = mysql_insert_id ();
						$coachSelect = $this->getCoachByID ( $coachID );
						if ($coachSelect) {
							$clubSelect = $this->getClubByID ( $coachSelect ['clubID'] );
							$clubName = $clubSelect ['clubName'];
							$createCoach ['result'] = true;
							// Userdaten
							$createCoach ['userID'] = $user ['userID'];
							$createCoach ['userEmail'] = $user ['userEmail'];
							$createCoach ['userNickname'] = $user ['userNickname'];
							$createCoach ['userKey'] = $user ['userKey'];
							$createCoach ['userRights'] = $user ['userRights'];
							$createCoach ['userActive'] = $user ['userActive'];
							$createCoach ['userTimestamp'] = $user ['userTimestamp'];
							// Coachdaten
							$createCoach ['coachID'] = $coachSelect ['coachID'];
							$createCoach ['userID'] = $coachSelect ['userID'];
							$createCoach ['clubID'] = $coachSelect ['clubID'];
							$createCoach ['coachFirstName'] = $coachSelect ['coachFirstName'];
							$createCoach ['coachSecondName'] = $coachSelect ['coachSecondName'];
							// Mail generieren und senden
							$this->generateMail ( $clubName, $user ['userKey'], $user ['userEmail'] );
						} else {
							// falls Club nicht eingefï¿½gt User wieder lï¿½schen
							$this->deleteUserByID ( $user ['userID'] );
						}
					} else {
						// falls Club nicht eingefï¿½gt User wieder lï¿½schen
						$this->deleteUserByID ( $user ['userID'] );
					}
					// mysql_close();
				}
			}
		} else {
			$createCoach ['result'] = false;
		}
		return $createCoach;
	}
	
	// Delete functions
	function deleteCoachByID($id) {
		$deleteCoach = false;
		if (is_numeric ( $id )) {
			$table = $this->coachTable;
			$where = 'coachID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$oldCoach = $this->getCoachByID ( $id );
				if ($oldCoach != false) {
					$userID = $oldCoach ['userID'];
					$userDelete = $this->deleteUserByID ( $userID );
					$coachDelete = $this->createDeleteQuery ( $table, $where );
					if ($coachDelete) {
						$deleteCoachResult = mysql_query ( $coachDelete );
						if ($deleteCoachResult) {
							$deleteCoach = true;
						}
					}
				}
			}
		} 

		else {
			$deleteCoach = false;
		}
		return $deleteCoach;
	}
	
	// ####Team functions####
	// Get functions
	function getTeamByID($id) {
		if (is_numeric ( $id )) {
			$team = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamSelect = $this->createSelectQuery ( '*', $this->teamTable, 'teamID = ' . $id, '', '' );
				if ($teamSelect) {
					$teamResult = mysql_query ( $teamSelect );
					
					if ($teamResult) {
						while ( $teamRow = mysql_fetch_array ( $teamResult ) ) {
							$teamID = $teamRow ['teamID'];
							$clubID = $teamRow ['clubID'];
							if ($teamRow ['teamName'] != '') {
								$teamName = $teamRow ['teamName'];
							} else {
								$teamName = - 1;
							}
							$teamTypeID = $teamRow ['teamTypeID'];
							$lineupID = $teamRow ['lineupID'];
							$team = array (
									"teamID" => $teamID,
									"clubID" => $clubID,
									"teamName" => $teamName,
									"teamTypeID" => $teamTypeID,
									"lineupID" => $lineupID 
							);
						}
					}
				}
			}
		} else {
			$team = false;
		}
		return $team;
		;
	}
	function getTeamByClub($id) {
		if (is_numeric ( $id )) {
			$clubTeam = false;
			$teamCount = 0;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamSelect = $this->createSelectQuery ( '*', $this->teamTable, 'clubID = ' . $id, '', '' );
				if ($teamSelect) {
					$teamResult = mysql_query ( $teamSelect );
					
					if ($teamResult) {
						while ( $teamRow = mysql_fetch_array ( $teamResult ) ) {
							$team = '';
							$teamID = $teamRow ['teamID'];
							$clubID = $teamRow ['clubID'];
							if ($teamRow ['teamName'] != '') {
								$teamName = $teamRow ['teamName'];
							} else {
								$teamName = - 1;
							}
							$teamTypeID = $teamRow ['teamTypeID'];
							$lineupID = $teamRow ['lineupID'];
							$team = array (
									"teamID" => $teamID,
									"clubID" => $clubID,
									"teamName" => $teamName,
									"teamTypeID" => $teamTypeID,
									"lineupID" => $lineupID 
							);
							$clubTeam [$teamCount] = $team;
							$teamCount ++;
						}
					}
				}
			}
		} else {
			$clubTeam = false;
		}
		return $clubTeam;
	}
	
	// Set functions
	function updateTeam($id, $clubID, $teamName, $teamTypeID, $lineupID) {
		$updateTeam = false;
		
		if (is_numeric ( $id )) {
			$table = $this->teamTable;
			
			$i = 0;
			if ($clubID != '') {
				$columns [$i] = 'clubID';
				$values [$i] = $clubID;
				$i ++;
			}
			if ($teamName != '') {
				$columns [$i] = 'teamName';
				$values [$i] = $teamName;
				$i ++;
			}
			if ($teamTypeID != '') {
				$columns [$i] = 'teamTypeID';
				$values [$i] = $teamTypeID;
				$i ++;
			}
			if ($lineupID != '') {
				$columns [$i] = 'lineupID';
				$values [$i] = $lineupID;
				$i ++;
			}
			$where = 'teamID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($teamUpdate) {
					$updateTeamResult = mysql_query ( $teamUpdate );
					if ($updateTeamResult) {
						$updateTeam = true;
					}
				}
			}
		} else {
			$updateTeam = false;
		}
		
		return $updateTeam;
	}
	
	// Create functions
	function createTeam($clubID, $teamName, $teamTypeID, $lineupID) {
		$createTeam ['result'] = false;
		if ($clubID != '' && is_numeric ( $clubID ) && $clubID > 0 && $teamName != '' && $teamTypeID != '' && is_numeric ( $teamTypeID ) && $teamTypeID > 0 && $lineupID != '' && is_numeric ( $lineupID ) && $lineupID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'clubID',
						1 => 'teamName',
						2 => 'teamTypeID',
						3 => 'lineupID' 
				);
				$values = array (
						0 => $clubID,
						1 => $teamName,
						2 => $teamTypeID,
						3 => $lineupID 
				);
				$teamInsert = $this->createInsertQuery ( $this->teamTable, $columns, $values );
				if ($teamInsert) {
					$teamResult = mysql_query ( $teamInsert );
					if ($teamResult) {
						$teamID = mysql_insert_id ();
						$teamSelect = $this->getTeamByID ( $teamID );
						if ($teamSelect) {
							$createTeam ['result'] = true;
							// Teamdaten
							$createTeam ['teamID'] = $teamSelect ['teamID'];
							$createTeam ['clubID'] = $teamSelect ['clubID'];
							$createTeam ['teamName'] = $teamSelect ['teamName'];
							$createTeam ['teamTypeID'] = $teamSelect ['teamTypeID'];
							$createTeam ['lineupID'] = $teamSelect ['lineupID'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createTeam;
	}
	
	// Delete functions
	function deleteTeamByID($id) {
		$deleteTeam = false;
		if (is_numeric ( $id )) {
			$table = $this->teamTable;
			$where = 'teamID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$oldTeam = $this->getTeamByID ( $id );
				if ($oldTeam != false) {
					$teamDelete = $this->createDeleteQuery ( $table, $where );
					if ($teamDelete) {
						$deleteTeamResult = mysql_query ( $teamDelete );
						if ($deleteTeamResult) {
							$deleteTeam = true;
						}
					}
				}
			}
		} 

		else {
			$deleteTeam = false;
		}
		return $deleteTeam;
	}
	
	// ####Club functions####
	// Get functions
	function getClubByID($id) {
		if (is_numeric ( $id )) {
			$club = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$clubSelect = $this->createSelectQuery ( '*', $this->clubTable, 'clubID = ' . $id, '', '' );
				if ($clubSelect) {
					$clubResult = mysql_query ( $clubSelect );
					
					if ($clubResult) {
						while ( $clubRow = mysql_fetch_array ( $clubResult ) ) {
							$clubID = $clubRow ['clubID'];
							$userID = $clubRow ['userID'];
							$clubName = $clubRow ['clubName'];
							$clubAdminFirstName = $clubRow ['clubAdminFirstName'];
							$clubAdminSecondName = $clubRow ['clubAdminSecondName'];
							$locationID = $clubRow ['locationID'];
							$clubShort = $clubRow ['clubShort'];
							$clubLong = $clubRow ['clubLong'];
							$clubHistory = $clubRow ['clubHistory'];
							$club = array (
									"clubID" => $clubID,
									"userID" => $userID,
									"clubName" => $clubName,
									"clubAdminFirstName" => $clubAdminFirstName,
									"clubAdminSecondName" => $clubAdminSecondName,
									"locationID" => $locationID,
									"clubShort" => $clubShort,
									"clubLong" => $clubLong,
									"clubHistory" => $clubHistory 
							);
						}
					}
				}
			}
		} else {
			$club = false;
		}
		return $club;
	}
	function getClubByUser($id) {
		if (is_numeric ( $id )) {
			$club = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$clubSelect = $this->createSelectQuery ( '*', $this->clubTable, 'userID = ' . $id, '', '' );
				if ($clubSelect) {
					$clubResult = mysql_query ( $clubSelect );
					
					if ($clubResult) {
						while ( $clubRow = mysql_fetch_array ( $clubResult ) ) {
							$clubID = $clubRow ['clubID'];
							$userID = $clubRow ['userID'];
							$clubName = $clubRow ['clubName'];
							$clubAdminFirstName = $clubRow ['clubAdminFirstName'];
							$clubAdminSecondName = $clubRow ['clubAdminSecondName'];
							$locationID = $clubRow ['locationID'];
							// $clubShort = $clubRow['clubShort'];
							// $clubLong = $clubRow['clubLong'];
							// $clubHistory = $clubRow['clubHistory'];
							// $club = array("clubID" => $clubID, "userID" => $userID, "clubName" => $clubName, "clubAdminFirstName" => $clubAdminFirstName, "clubAdminSecondName" => $clubAdminSecondName, "locationID" => $locationID, "clubShort" => $clubShort, "clubLong" => $clubLong, "clubHistory" => $clubHistory);
							$club = array (
									"clubID" => $clubID,
									"userID" => $userID,
									"clubName" => $clubName,
									"clubAdminFirstName" => $clubAdminFirstName,
									"clubAdminSecondName" => $clubAdminSecondName,
									"locationID" => $locationID 
							);
						}
					}
				}
			}
		} else {
			$club = false;
		}
		return $club;
	}
	
	// Set functions
	function updateClub($id, $clubName, $clubAdminFirstName, $clubAdminSecondName, $locationID, $clubShort, $clubLong, $clubHistory, $userID, $userEmail, $userPassword, $userNickname, $userRights) {
		$updateClub = false;
		$updateUser = $this->updateUser ( $userID, $userEmail, $userPassword, $userNickname, $userRights );
		if ($updateUser) {
			if (is_numeric ( $id )) {
				$table = $this->clubTable;
				
				$i = 0;
				if ($clubName != '') {
					$columns [$i] = 'clubName';
					$values [$i] = $clubName;
					$i ++;
				}
				if ($clubAdminFirstName != '') {
					$columns [$i] = 'clubAdminFirstName';
					$values [$i] = $clubAdminFirstName;
					$i ++;
				}
				if ($clubAdminSecondName != '') {
					$columns [$i] = 'clubAdminSecondName';
					$values [$i] = $clubAdminSecondName;
					$i ++;
				}
				if ($locationID != '') {
					$columns [$i] = 'locationID';
					$values [$i] = $locationID;
					$i ++;
				}
				if ($clubShort != '') {
					$columns [$i] = 'clubShort';
					$values [$i] = $clubShort;
					$i ++;
				}
				if ($clubLong != '') {
					$columns [$i] = 'clubLong';
					$values [$i] = $clubLong;
					$i ++;
				}
				if ($clubHistory != '') {
					$columns [$i] = 'clubHistory';
					$values [$i] = $clubHistory;
					$i ++;
				}
				$where = 'clubID = ' . $id;
				$con = $this->getConnection ();
				if ($con == true) {
					$lubUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
					if ($clubUpdate) {
						$updateClubResult = mysql_query ( $clubUpdate );
						if ($updateClubResult) {
							$updateClub = true;
						}
					}
				}
			} else {
				$updateClub = false;
			}
		}
		return $updateClub;
	}
	
	// Create functions
	function createClub($userEmail, $userPassword, $userNickname, $clubName, $clubAdminFirstName, $clubAdminSecondName, $locationID, $clubShort, $clubLong, $clubHistory) {
		$createClub ['result'] = false;
		$userRights = 0;
		$user = $this->createUser ( $userEmail, $userPassword, $userNickname, $userRights );
		if ($user ['result']) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'userID',
						1 => 'clubName',
						2 => 'clubAdminFirstName',
						3 => 'clubAdminSecondName',
						4 => 'locationID',
						5 => 'clubShort',
						6 => 'clubLong',
						7 => 'clubHistory' 
				);
				$values = array (
						0 => $user ['userID'],
						1 => $clubName,
						2 => $clubAdminFirstName,
						3 => $clubAdminSecondName,
						4 => $locationID,
						5 => $clubShort,
						6 => $clubLong,
						7 => $clubHistory 
				);
				$clubInsert = $this->createInsertQuery ( $this->clubTable, $columns, $values );
				if ($clubInsert) {
					$clubResult = mysql_query ( $clubInsert );
					if ($clubResult) {
						$clubID = mysql_insert_id ();
						$clubSelect = $this->getClubByID ( $clubID );
						if ($clubSelect) {
							$createClub ['result'] = true;
							// Userdaten
							$createClub ['userID'] = $user ['userID'];
							$createClub ['userEmail'] = $user ['userEmail'];
							$createClub ['userNickname'] = $user ['userNickname'];
							$createClub ['userKey'] = $user ['userKey'];
							$createClub ['userRights'] = $user ['userRights'];
							$createClub ['userActive'] = $user ['userActive'];
							$createClub ['userTimestamp'] = $user ['userTimestamp'];
							// Clubdaten
							$createClub ['clubID'] = $clubSelect ['clubID'];
							$createClub ['clubName'] = $clubSelect ['clubName'];
							$createClub ['clubAdminFirstName'] = $clubSelect ['clubAdminFirstName'];
							$createClub ['clubAdminSecondName'] = $clubSelect ['clubAdminSecondName'];
							$createClub ['locationID'] = $clubSelect ['locationID'];
							$createClub ['clubShort'] = $clubSelect ['clubShort'];
							$createClub ['clubLong'] = $clubSelect ['clubLong'];
							$createClub ['clubHistory'] = $clubSelect ['clubHistory'];
							// Mail generieren und senden
							$this->generateMail ( $clubName, $user ['userKey'], $user ['userEmail'] );
						} else {
							// falls Club nicht eingefï¿½gt User wieder lï¿½schen
							$this->deleteUserByID ( $user ['userID'] );
						}
					} else {
						// falls Club nicht eingefï¿½gt User wieder lï¿½schen
						$this->deleteUserByID ( $user ['userID'] );
					}
					// mysql_close();
				}
			}
		} else {
			$createClub ['result'] = false;
		}
		return $createClub;
	}
	
	// Delete functions
	function deleteClubByID($id) {
		$deleteClub = false;
		if (is_numeric ( $id )) {
			$table = $this->clubTable;
			$where = 'clubID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$oldClub = $this->getClubByID ( $id );
				if ($oldClub != false) {
					$userID = $oldClub ['userID'];
					$userDelete = $this->deleteUserByID ( $userID );
					$clubDelete = $this->createDeleteQuery ( $table, $where );
					if ($clubDelete) {
						$deleteClubResult = mysql_query ( $clubDelete );
						if ($deleteClubResult) {
							$deleteClub = true;
						}
					}
				}
			}
		} 

		else {
			$deleteClub = false;
		}
		return $deleteClub;
	}
	
	// ####User functions####
	// Get functions
	function getUserByID($id) {
		if (is_numeric ( $id )) {
			$user = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$userSelect = $this->createSelectQuery ( '*', $this->userTable, 'userID = ' . $id, '', '' );
				if ($userSelect) {
					$userResult = mysql_query ( $userSelect );
					
					if ($userResult) {
						while ( $userRow = mysql_fetch_array ( $userResult ) ) {
							$userID = $userRow ['userID'];
							$userEmail = $userRow ['userEmail'];
							$userNickname = $userRow ['userNickname'];
							$userKey = $userRow ['userKey'];
							$userTimestamp = $userRow ['userTimestamp'];
							$userRights = $userRow ['userRights'];
							$userActive = $userRow ['userActive'];
							$user = array (
									"userID" => $userID,
									"userEmail" => $userEmail,
									"userNickname" => $userNickname,
									"userKey" => $userKey,
									"userTimestamp" => $userTimestamp,
									"userRights" => $userRights,
									"userActive" => $userActive 
							);
						}
					}
				}
			}
		} else {
			$user = false;
		}
		return $user;
		;
	}
	function getUserLogin($userNickname, $userPassword) {
		$login ['result'] = false;
		if ($userNickname != '' && is_string ( $userNickname ) && $userPassword != '' && is_string ( $userPassword )) {
			$con = $this->getConnection ();
			if ($con == true) {
				$userSelect = $this->createSelectQuery ( '*', $this->userTable, 'userEmail = \'' . $userNickname . '\'', '', '' );
				if ($userSelect) {
					$userResult = mysql_query ( $userSelect );
					
					if ($userResult) {
						while ( $userRow = mysql_fetch_array ( $userResult ) ) {
							$userRowPassword = $userRow ['userPassword'];
							$userCryptPassword = crypt ( $userPassword, $userRowPassword );
							$login ['userType'] = false;
							echo "<br>" . $userRowPassword . "<br>" . $userCryptPassword . "<br>";
							if ($userRowPassword == $userCryptPassword) {
								echo "<br>pwStimmt<br>";
								$userActive = $userRow ['userActive'];
								if ($userActive) {
									$login ['result'] = true;
									// Variablen setzen
									$login ['userID'] = $userRow ['userID'];
									$login ['userEmail'] = $userRow ['userEmail'];
									$login ['userRights'] = $userRow ['userRights'];
									
									// ï¿½berprï¿½fen ob User ein Player
									$player = $this->getPlayerByUser ( $login ['userID'] );
									if ($player) {
										$login ['userType'] = 'player';
										$login ['playerID'] = $player ['playerID'];
										$login ['teamID'] = $player ['teamID'];
										$login ['clubID'] = $player ['clubID'];
										$login ['firstName'] = $player ['playerFirstName'];
										$login ['secondName'] = $player ['playerSecondName'];
										$login ['positionID'] = $player ['positionID'];
										$login ['playerBirthdate'] = $player ['playerBirthdate'];
									}
									// ï¿½berprï¿½fen ob User ein Coach
									$coach = $this->getCoachByUser ( $login ['userID'] );
									if ($coach) {
										$login ['userType'] = 'coach';
										$login ['coachID'] = $coach ['coachID'];
										$login ['clubID'] = $coach ['clubID'];
										$login ['team'] = $coach ['team'];
										$login ['firstName'] = $coach ['coachFirstName'];
										$login ['secondName'] = $coach ['coachSecondName'];
									}
									// ï¿½berprï¿½fen ob User ein Club
									$club = $this->getClubByUser ( $login ['userID'] );
									if ($club) {
										$login ['userType'] = 'club';
										$login ['clubID'] = $club ['clubID'];
										$login ['userID'] = $club ['userID'];
										$login ['clubName'] = $club ['clubName'];
										$login ['firstName'] = $club ['clubAdminFirstName'];
										$login ['secondName'] = $club ['clubAdminSecondName'];
										$login ['locationID'] = $club ['locationID'];
									}
									
									if ($login ['userType'] == false) {
										echo "<br>false<br>";
										$login ['result'] = false;
									}
								} else {
									$login ['result'] = 'nichtAktiviert';
								}
							} else {
								$login ['result'] = 'pwFalsch';
							}
						}
					}
				}
			}
		}
		return $login;
	}
	
	// Set functions
	function setUserActive($id, $userActive) {
		$updateUser = false;
		if (is_numeric ( $id ) && $userActive >= 0 && is_numeric ( $userActive ) && $userActive <= 1) {
			$table = $this->userTable;
			$columns [0] = 'userActive';
			$values [0] = $userActive;
			$where = 'userID = ' . $id;
			$updateUser = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$userUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($userUpdate) {
					$updateUserResult = mysql_query ( $userUpdate );
					if ($updateUserResult) {
						$updateUser = true;
					}
				}
			}
		} else {
			$updateUser = false;
		}
		return $updateUser;
	}
	function updateUser($id, $userEmail, $userPassword, $userNickname, $userRights) {
		$updateUser = false;
		if (is_numeric ( $id )) {
			$table = $this->userTable;
			
			$i = 0;
			if ($userEmail != '') {
				$columns [$i] = 'userEmail';
				$values [$i] = $userEmail;
				$i ++;
			}
			if ($userPassword != '') {
				$columns [$i] = 'userPassword';
				$cryptPW = $this->cryptPassword ( $userPassword );
				$values [$i] = $cryptPW;
				$i ++;
			}
			if ($userNickname != '') {
				$columns [$i] = 'userNickname';
				$values [$i] = $userNickname;
				$i ++;
			}
			if ($userRights != '' || $userRights == 0) {
				$columns [$i] = 'userRights';
				$values [$i] = $userRights;
				$i ++;
			}
			$where = 'userID = ' . $id;
			$updateUser = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$userUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($userUpdate) {
					$updateUserResult = mysql_query ( $userUpdate );
					if ($updateUserResult) {
						$updateUser = true;
					}
					// mysql_close();
				}
			}
		} else {
			$updateUser = false;
		}
		return $updateUser;
	}
	
	// Create functions
	function createUser($userEmail, $userPassword, $userNickname, $userRights) {
		$createUser ['result'] = false;
		if ($userEmail != '' && strpos ( $userEmail, '@' ) && strpos ( $userEmail, '@' ) > 0 && strpos ( $userEmail, '.' ) && strpos ( $userEmail, '@' ) < strpos ( $userEmail, '.' ) && strpos ( $userEmail, '.' ) < strlen ( $userEmail ) - 1 && $userPassword != '' && is_numeric ( $userRights ) && $userRights >= 0 && $userRights < 4) {
			if ($userNickname == '') {
				$userNickname = $userEmail;
			}
			$cryptPW = $this->cryptPassword ( $userPassword );
			// Zufallskey generieren
			$randomKey = substr ( md5 ( microtime () ), mt_rand ( 0, 24 ), 8 );
			$userKey = md5 ( $randomKey );
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'userEmail',
						1 => 'userPassword',
						2 => 'userKey',
						3 => 'userActive',
						4 => 'userNickname',
						5 => 'userRights' 
				);
				$values = array (
						0 => $userEmail,
						1 => $cryptPW,
						2 => $userKey,
						3 => 0,
						4 => $userNickname,
						5 => $userRights 
				);
				$userInsert = $this->createInsertQuery ( $this->userTable, $columns, $values );
				if ($userInsert) {
					$userResult = mysql_query ( $userInsert );
					if ($userResult) {
						$userID = mysql_insert_id ();
						$userSelect = $this->getUserByID ( $userID );
						if ($userSelect) {
							$createUser ['result'] = true;
							$createUser ['userID'] = $userID;
							$createUser ['userEmail'] = $userSelect ['userEmail'];
							$createUser ['userNickname'] = $userSelect ['userNickname'];
							$createUser ['userKey'] = $userSelect ['userKey'];
							$createUser ['userRights'] = $userSelect ['userRights'];
							$createUser ['userActive'] = $userSelect ['userActive'];
							$createUser ['userTimestamp'] = $userSelect ['userTimestamp'];
						} else {
							$createUser ['result'] = false;
						}
					}
					// mysql_close();
				}
			}
		} else {
			$createUser ['result'] = false;
		}
		return $createUser;
	}
	
	// Delete functions
	function deleteUserByID($id) {
		$deleteUser = false;
		if (is_numeric ( $id )) {
			$table = $this->userTable;
			$where = 'userID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$userDelete = $this->createDeleteQuery ( $table, $where );
				if ($userDelete) {
					$deleteUserResult = mysql_query ( $userDelete );
					if ($deleteUserResult) {
						$deleteUser = true;
					}
					// mysql_close();
				}
			}
		} 

		else {
			$deleteUser = false;
		}
		return $deleteUser;
	}
	
	// ####Event functions####
	// Get functions
	function getEventByID($id) {
		if (is_numeric ( $id )) {
			$event = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$eventSelect = $this->createSelectQuery ( '*', $this->eventTable, 'eventID = ' . $id, '', '' );
				if ($eventSelect) {
					$eventResult = mysql_query ( $eventSelect );
					
					if ($eventResult) {
						while ( $eventRow = mysql_fetch_array ( $eventResult ) ) {
							$eventID = $eventRow ['eventID'];
							$eventDate = $eventRow ['eventDate'];
							$eventTime = $eventRow ['eventTime'];
							$locationID = $eventRow ['locationID'];
							$event = array (
									"eventID" => $eventID,
									"eventDate" => $eventDate,
									"eventTime" => $eventTime,
									"locationID" => $locationID 
							);
						}
					}
				}
			}
		} else {
			$event = false;
		}
		return $event;
	}
	
	// Set functions
	function updateEvent($id, $eventDate, $eventTime, $locationID) {
		$updateEvent ['result'] = false;
		$existCount = 0;
		$eventSelect = $this->getEventByID ( $id );
		if ($eventSelect) {
			
			if (is_numeric ( $id )) {
				$table = $this->eventTable;
				$i = 0;
				if ($eventDate != '') {
					$columns [$i] = 'eventDate';
					$values [$i] = $eventDate;
					$i ++;
				} else {
					$existCount ++;
					$columns [$i] = 'eventDate';
					$values [$i] = $eventDate;
					$i ++;
				}
				if ($eventTime != '') {
					$columns [$i] = 'eventTime';
					$values [$i] = $eventTime;
					$i ++;
				} else {
					$existCount ++;
					$columns [$i] = 'eventTime';
					$values [$i] = $eventTime;
					$i ++;
				}
				if ($locationID != '') {
					$columns [$i] = 'locationID';
					$values [$i] = $locationID;
					$i ++;
				} else {
					$existCount ++;
					$columns [$i] = 'locationID';
					$values [$i] = $locationID;
					$i ++;
				}
				if ($existCount < 3) {
					$eventInsert = $this->createEvent ( $eventDate, $eventTime, $locationID );
					if ($eventInsert ['result'] != false) {
						$updateEvent ['eventID'] = $eventInsert ['eventID'];
						$updateEvent ['result'] = true;
					} else {
						$updateEvent ['eventID'] = $eventSelect ['$eventID'];
						$updateEvent ['result'] = true;
					}
				} else {
					$updateEvent ['result'] = true;
				}
			} else {
				$updateEvent ['result'] = false;
			}
		}
		return $updateEvent ['result'];
	}
	
	// Create function
	function createEvent($eventDate, $eventTime, $locationID) {
		$createEvent ['result'] = false;
		$eventExisting = false;
		if ($eventDate != '' && $eventTime != '' && $locationID != '' && is_numeric ( $locationID ) && $locationID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$eventExistingSelect = $this->createSelectQuery ( '*', $this->eventTable, 'eventDate = \'' . $eventDate . '\' AND eventTime = \'' . $eventTime . '\' AND locationID = ' . $locationID, '', '' );
				if ($eventExistingSelect) {
					$eventExistingResult = mysql_query ( $eventExistingSelect );
					if ($eventExistingResult) {
						while ( $eventExistingRow = mysql_fetch_array ( $eventExistingResult ) ) {
							$eventExisting = true;
							$createEvent ['result'] = true;
							$createEvent ['eventID'] = $eventExistingRow ['eventID'];
							$createEvent ['eventDate'] = $eventExistingRow ['eventDate'];
							$createEvent ['eventTime'] = $eventExistingRow ['eventTime'];
							$createEvent ['locationID'] = $eventExistingRow ['locationID'];
						}
					}
				}
				if ($eventExisting != true) {
					$columns = array (
							0 => 'eventDate',
							1 => 'eventTime',
							2 => 'locationID' 
					);
					$values = array (
							0 => $eventDate,
							1 => $eventTime,
							2 => $locationID 
					);
					$eventInsert = $this->createInsertQuery ( $this->eventTable, $columns, $values );
					if ($eventInsert) {
						$eventResult = mysql_query ( $eventInsert );
						if ($eventResult) {
							$eventID = mysql_insert_id ();
							$eventSelect = $this->getEventByID ( $eventID );
							if ($eventSelect) {
								$createEvent ['result'] = true;
								// Eventdaten
								$createEvent ['eventID'] = $eventSelect ['eventID'];
								$createEvent ['eventDate'] = $eventSelect ['eventDate'];
								$createEvent ['eventTime'] = $eventSelect ['eventTime'];
								$createEvent ['locationID'] = $eventSelect ['locationID'];
							}
						}
						// mysql_close();
					}
				}
			}
		}
		return $createEvent;
	}
	
	// Delete functions
	
	// ####Location####
	// Get functions
	function getLocationByID($id) {
		if (is_numeric ( $id )) {
			$location = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$locationSelect = $this->createSelectQuery ( '*', $this->locationTable, 'locationID = ' . $id, '', '' );
				if ($locationSelect) {
					$locationResult = mysql_query ( $locationSelect );
					
					if ($locationResult) {
						while ( $locationRow = mysql_fetch_array ( $locationResult ) ) {
							$locationID = $locationRow ['locationID'];
							$locationName = $locationRow ['locationName'];
							$locationWayDescription = $locationRow ['locationWayDescription'];
							$location = array (
									"locationID" => $locationID,
									"locationName" => $locationName,
									"locationWayDescription" => $locationWayDescription 
							);
						}
					}
				}
			}
		} else {
			$location = false;
		}
		return $location;
	}
	
	// Set functions
	function updateLocation($id, $locationName, $locationWayDescription) {
		$updateLocation = false;
		
		if (is_numeric ( $id )) {
			$table = $this->locationTable;
			
			$i = 0;
			if ($locationName != '') {
				$columns [$i] = 'locationName';
				$values [$i] = $locationName;
				$i ++;
			}
			if ($locationWayDescription != '') {
				$columns [$i] = 'locationWayDescription';
				$values [$i] = $locationWayDescription;
				$i ++;
			}
			$where = 'locationID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$locationUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($locationUpdate) {
					$updateLocationResult = mysql_query ( $locationUpdate );
					if ($updateLocationResult) {
						$updateLocation = true;
					}
				}
			}
		} else {
			$updateLocation = false;
		}
		
		return $updateLocation;
	}
	
	// Create function
	function createLocation($locationName, $locationWayDescription) {
		$createLocation ['result'] = false;
		if ($locationName != '' && $locationWayDescription != '') {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'locationName',
						1 => 'locationWayDescription' 
				);
				$values = array (
						0 => $locationName,
						1 => $locationWayDescription 
				);
				$locationInsert = $this->createInsertQuery ( $this->locationTable, $columns, $values );
				if ($locationInsert) {
					$locationResult = mysql_query ( $locationInsert );
					if ($locationResult) {
						$locationID = mysql_insert_id ();
						$locationSelect = $this->getLocationByID ( $locationID );
						if ($locationSelect) {
							$createLocation ['result'] = true;
							// Teamdaten
							$createLocation ['locationID'] = $locationSelect ['locationID'];
							$createLocation ['locationName'] = $locationSelect ['locationName'];
							$createLocation ['locationWayDescription'] = $locationSelect ['locationWayDescription'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createLocation;
	}
	
	// Delete functions
	
	// ####Match####
	// Get functions
	function getMatchByID($id) {
		if (is_numeric ( $id )) {
			$match = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$matchSelect = $this->createSelectQuery ( '*', $this->matchTable, 'matchID = ' . $id, '', '' );
				if ($matchSelect) {
					$matchResult = mysql_query ( $matchSelect );
					
					if ($matchResult) {
						while ( $matchRow = mysql_fetch_array ( $matchResult ) ) {
							$matchID = $matchRow ['matchID'];
							$tournamentID = $matchRow ['tournamentID'];
							$eventID = $matchRow ['eventID'];
							$match = array (
									"matchID" => $matchID,
									"tournamentID" => $tournamentID,
									"eventID" => $eventID 
							);
						}
					}
				}
			}
		} else {
			$match = false;
		}
		return $match;
	}
	
	// Set functions
	function updateMatch($id, $tournamentID, $event, $eventDate, $eventTime, $locationID) {
		$updateMatch = false;
		$updateEvent = $this->updateEvent ( $id, $eventDate, $eventTime, $locationID );
		if ($updateEvent) {
			if (is_numeric ( $id )) {
				$table = $this->matchTable;
				
				$i = 0;
				if ($tournamentID != '') {
					$columns [$i] = 'tournamentID';
					$values [$i] = $tournamentID;
					$i ++;
				}
				if ($eventID != '') {
					$columns [$i] = 'eventID';
					$values [$i] = $updateEvent ['eventID'];
					$i ++;
				}
				$where = 'matchID = ' . $id;
				$con = $this->getConnection ();
				if ($con == true) {
					$matchUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
					if ($matchUpdate) {
						$updateMatchResult = mysql_query ( $matchUpdate );
						if ($updateMatchResult) {
							$updateMatch = true;
						}
					}
				}
			} else {
				$updateMatch = false;
			}
		}
		return $updateMatch;
	}
	
	// Create function
	function createMatch($tournamentID, $eventDate, $eventTime, $locationID) {
		$createMatch ['result'] = false;
		if ($eventDate != '' && $eventTime != '' && $locationID != '' && is_numeric ( $locationID ) && $locationID > 0) {
			if ($tournamentID == '') {
				$tournamentID = - 1;
			}
			$con = $this->getConnection ();
			if ($con == true) {
				$event = $this->createEvent ( $eventDate, $eventTime, $locationID );
				$columns = array (
						0 => 'tournamentID',
						1 => 'eventID' 
				);
				$values = array (
						0 => $tournamentID,
						1 => $event ['eventID'] 
				);
				$matchInsert = $this->createInsertQuery ( $this->matchTable, $columns, $values );
				if ($matchInsert) {
					$matchResult = mysql_query ( $matchInsert );
					if ($matchResult) {
						$matchID = mysql_insert_id ();
						$matchSelect = $this->getMatchByID ( $matchID );
						if ($matchSelect) {
							$createMatch ['result'] = true;
							// Matchdaten
							$createMatch ['matchID'] = $matchSelect ['matchID'];
							$createMatch ['tournamentID'] = $matchSelect ['tournamentID'];
							$createMatch ['eventID'] = $event ['eventID'];
							$createMatch ['eventDate'] = $event ['eventDate'];
							$createMatch ['eventTime'] = $event ['eventTime'];
							$createMatch ['locationID'] = $event ['locationID'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createMatch;
	}
	
	// Delete functions
	
	// ####Parent####
	// Get functions
	function getParentByID($id) {
		if (is_numeric ( $id )) {
			$parent = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$parentSelect = $this->createSelectQuery ( '*', $this->parentTable, 'parentID = ' . $id, '', '' );
				if ($parentSelect) {
					$parentResult = mysql_query ( $parentSelect );
					
					if ($parentResult) {
						while ( $parentRow = mysql_fetch_array ( $parentResult ) ) {
							$parentID = $parentRow ['parentID'];
							$parentFirstName = $parentRow ['parentFirstName'];
							$parentSecondName = $parentRow ['parentSecondName'];
							$parentPhone = $parentRow ['parentPhone'];
							$parentMobile = $parentRow ['parentMobile'];
							$playerID = $parentRow ['playerID'];
							$parentEmail = $parentRow ['parentEmail'];
							$parent = array (
									"parentID" => $parentID,
									"parentFirstName" => $parentFirstName,
									"parentSecondName" => $parentSecondName,
									"parentPhone" => $parentPhone,
									"parentMobile" => $parentMobile,
									"playerID" => $playerID,
									"parentEmail" => $parentEmail 
							);
						}
					}
				}
			}
		} else {
			$parent = false;
		}
		return $parent;
	}
	
	// Set functions
	function updateParent($id, $parentFirstName, $parentSecondName, $parentPhone, $parentMobile, $parentEmail) {
		$updateLocation = false;
		
		if (is_numeric ( $id )) {
			$table = $this->parentTable;
			
			$i = 0;
			if ($parentFirstName != '') {
				$columns [$i] = 'parentFirstName';
				$values [$i] = $parentFirstName;
				$i ++;
			}
			if ($parentSecondName != '') {
				$columns [$i] = 'parentSecondName';
				$values [$i] = $parentSecondName;
				$i ++;
			}
			if ($parentPhone != '') {
				$columns [$i] = 'parentPhone';
				$values [$i] = $parentPhone;
				$i ++;
			}
			if ($parentMobile != '') {
				$columns [$i] = 'parentMobile';
				$values [$i] = $parentMobile;
				$i ++;
			}
			if ($parentEmail != '') {
				$columns [$i] = 'parentEmail';
				$values [$i] = $parentEmail;
				$i ++;
			}
			$where = 'parentID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$parentUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($parentUpdate) {
					$updateParentResult = mysql_query ( $parentUpdate );
					if ($updateParentResult) {
						$updateParent = true;
					}
				}
			}
		} else {
			$updateParent = false;
		}
		
		return $updateParent;
	}
	
	// Create function
	function createParent($parentFirstName, $parentSecondName, $parentPhone, $parentMobile, $playerID, $parentEmail) {
		$createParent ['result'] = false;
		if ($parentFirstName != '' && $parentSecondName != '' && $playerID != '' && is_numeric ( $playerID ) && $playerID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'parentFirstName',
						1 => 'parentSecondName',
						2 => 'parentPhone',
						3 => 'parentMobile',
						4 => 'playerID',
						5 => 'parentEmail' 
				);
				$values = array (
						0 => $parentFirstName,
						1 => $parentSecondName,
						2 => $parentPhone,
						3 => $parentMobile,
						4 => $playerID,
						5 => $parentEmail 
				);
				$parentInsert = $this->createInsertQuery ( $this->parentTable, $columns, $values );
				if ($parentInsert) {
					$parentResult = mysql_query ( $parentInsert );
					if ($parentResult) {
						$parentID = mysql_insert_id ();
						$parentSelect = $this->getParentByID ( $parentID );
						if ($parentSelect) {
							$createParent ['result'] = true;
							// Parentdaten
							$createParent ['parentID'] = $parentSelect ['parentID'];
							$createParent ['parentFirstName'] = $parentSelect ['parentFirstName'];
							$createParent ['parentSecondName'] = $parentSelect ['parentSecondName'];
							$createParent ['parentPhone'] = $parentSelect ['parentPhone'];
							$createParent ['parentMobile'] = $parentSelect ['parentMobile'];
							$createParent ['playerID'] = $parentSelect ['playerID'];
							$createParent ['parentEmail'] = $parentSelect ['parentEmail'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createParent;
	}
	
	// Delete functions
	
	// ####PlayerMatch####
	// Get functions
	function getPlayerMatchByID($id) {
		if (is_numeric ( $id )) {
			$playerMatch = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerMatchSelect = $this->createSelectQuery ( '*', $this->playerMatchTable, 'playerMatchID = ' . $id, '', '' );
				if ($playerMatchSelect) {
					$playerMatchResult = mysql_query ( $playerMatchSelect );
					
					if ($playerMatchResult) {
						while ( $playerMatchRow = mysql_fetch_array ( $playerMatchResult ) ) {
							$playerMatchID = $playerMatchRow ['playerMatchID'];
							$playerID = $playerMatchRow ['playerID'];
							$matchID = $playerMatchRow ['matchID'];
							$playerMatchGoals = $playerMatchRow ['playerMatchGoals'];
							$playerMatchYellowCards = $playerMatchRow ['playerMatchYellowCards'];
							$playerMatchRedCards = $playerMatchRow ['playerMatchRedCards'];
							$playerMatchOperation = $playerMatchRow ['playerMatchOperation'];
							$playerMatchStartLineup = $playerMatchRow ['playerMatchStartLineup'];
							$playerMatch = array (
									"playerMatchID" => $playerMatchID,
									"playerID" => $playerID,
									"matchID" => $matchID,
									"playerMatchGoals" => $playerMatchGoals,
									"playerMatchYellowCards" => $playerMatchYellowCards,
									"playerMatchRedCards" => $playerMatchRedCards,
									"playerMatchOperation" => $playerMatchOperation,
									"playerMatchStartLineup" => $playerMatchStartLineup 
							);
						}
					}
				}
			}
		} else {
			$playerMatch = false;
		}
		return $playerMatch;
	}
	
	// Set functions
	function updatePlayerMatch($id, $playerID, $matchID, $playerMatchGoals, $playerMatchYellowCards, $playerMatchRedCards, $playerMatchOperation, $playerMatchStartLineup) {
		$updatePlayerMatch = false;
		
		if (is_numeric ( $id )) {
			$table = $this->playerMatchTable;
			
			$i = 0;
			if ($playerID = '') {
				$columns [$i] = 'playerID';
				$values [$i] = $playerID;
				$i ++;
			}
			if ($matchID != '') {
				$columns [$i] = 'matchID';
				$values [$i] = $matchID;
				$i ++;
			}
			if ($playerMatchGoals != '') {
				$columns [$i] = 'playerMatchGoals';
				$values [$i] = $playerMatchGoals;
				$i ++;
			}
			if ($playerMatchYellowCards != '') {
				$columns [$i] = 'playerMatchYellowCards';
				$values [$i] = $playerMatchYellowCards;
				$i ++;
			}
			if ($playerMatchRedCards != '') {
				$columns [$i] = 'playerMatchRedCards';
				$values [$i] = $playerMatchRedCards;
				$i ++;
			}
			if ($playerMatchOperation != '') {
				$columns [$i] = 'playerMatchOperation';
				$values [$i] = $playerMatchOperation;
				$i ++;
			}
			if ($playerMatchStartLineup != '') {
				$columns [$i] = 'playerMatchStartLineup';
				$values [$i] = $playerMatchStartLineup;
				$i ++;
			}
			$where = 'playerMatchID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerMatchUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($playerMatchUpdate) {
					$updatePlayerMatchResult = mysql_query ( $playerMatchUpdate );
					if ($updatePlayerMatchResult) {
						$updatePlayerMatch = true;
					}
				}
			}
		} else {
			$updatePlayerMatch = false;
		}
		
		return $updatePlayerMatch;
	}
	
	// Create function
	function createPlayerMatch($playerID, $matchID, $playerMatchGoals, $playerMatchYellowCards, $playerMatchRedCards, $playerMatchOperation, $playerMatchStartLineup) {
		$createPlayerMatch ['result'] = false;
		if ($playerID != '' && is_numeric ( $playerID ) && $playerID > 0 && $matchID != '' && is_numeric ( $matchID ) && $matchID > 0 && $playerMatchGoals != '' && is_numeric ( $playerMatchGoals ) && $playerMatchYellowCards != '' && is_numeric ( $playerMatchYellowCards ) && $playerMatchRedCards != '' && is_numeric ( $playerMatchRedCards ) && $playerMatchOperation != '' && is_numeric ( $playerMatchOperation ) && $playerMatchStartLineup != '' && is_numeric ( $playerMatchStartLineup )) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'playerID',
						1 => 'matchID',
						2 => 'playerMatchGoals',
						3 => 'playerMatchYellowCards',
						4 => 'playerMatchRedCards',
						5 => 'playerMatchOperation',
						6 => 'playerMatchStartLineup' 
				);
				$values = array (
						0 => $playerID,
						1 => $matchID,
						2 => $playerMatchGoals,
						3 => $playerMatchYellowCards,
						4 => $playerMatchRedCards,
						5 => $playerMatchOperation,
						6 => $playerMatchStartLineup 
				);
				$playerMatchInsert = $this->createInsertQuery ( $this->playerMatchTable, $columns, $values );
				if ($playerMatchInsert) {
					$playerMatchResult = mysql_query ( $playerMatchInsert );
					if ($playerMatchResult) {
						$playerMatchID = mysql_insert_id ();
						$playerMatchSelect = $this->getPlayerMatchByID ( $playerMatchID );
						if ($playerMatchSelect) {
							$createPlayerMatch ['result'] = true;
							// PlayerMatchdaten
							$createPlayerMatch ['playerMatchID'] = $playerMatchSelect ['playerMatchID'];
							$createPlayerMatch ['playerID'] = $playerMatchSelect ['playerID'];
							$createPlayerMatch ['matchID'] = $playerMatchSelect ['matchID'];
							$createPlayerMatch ['playerMatchGoals'] = $playerMatchSelect ['playerMatchGoals'];
							$createPlayerMatch ['playerMatchYellowCards'] = $playerMatchSelect ['playerMatchYellowCards'];
							$createPlayerMatch ['playerMatchRedCards'] = $playerMatchSelect ['playerMatchRedCards'];
							$createPlayerMatch ['playerMatchOperation'] = $playerMatchSelect ['playerMatchOperation'];
							$createPlayerMatch ['playerMatchStartLineup'] = $playerMatchSelect ['playerMatchStartLineup'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createPlayerMatch;
	}
	
	// Delete functions
	
	// ####PlayerPosition####
	// Get functions
	function getPlayerPositionByID($id) {
		if (is_numeric ( $id )) {
			$playerPosition = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerPositionSelect = $this->createSelectQuery ( '*', $this->playerPositionTable, 'playerPositionID = ' . $id, '', '' );
				if ($playerPositionSelect) {
					$playerPositionResult = mysql_query ( $playerPositionSelect );
					
					if ($playerPositionResult) {
						while ( $playerPositionRow = mysql_fetch_array ( $playerPositionResult ) ) {
							$playerPositionID = $playerPositionRow ['playerPositionID'];
							$playerID = $playerPositionRow ['playerID'];
							$positionID = $playerPositionRow ['positionID'];
							$playerPosition = array (
									"playerPositionID" => $playerPositionID,
									"playerID" => $playerID,
									"positionID" => $positionID 
							);
						}
					}
				}
			}
		} else {
			$playerPosition = false;
		}
		return $playerPosition;
	}
	
	// Set functions
	function updatePlayerPosition($id, $playerID, $positionID) {
		$updatePlayerPosition = false;
		
		if (is_numeric ( $id )) {
			$table = $this->playerPositionTable;
			
			$i = 0;
			if ($playerID = '') {
				$columns [$i] = 'playerID';
				$values [$i] = $playerID;
				$i ++;
			}
			if ($positionID != '') {
				$columns [$i] = 'positionID';
				$values [$i] = $positionID;
				$i ++;
			}
			$where = 'playerPositionID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerPositionUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($playerPositionUpdate) {
					$updatePlayerPositionResult = mysql_query ( $playerPositionUpdate );
					if ($updatePlayerPositionResult) {
						$updatePlayerPosition = true;
					}
				}
			}
		} else {
			$updatePlayerPosition = false;
		}
		
		return $updatePlayerPosition;
	}
	
	// Create function
	function createPlayerPosition($playerID, $positionID) {
		$createPlayerPosition ['result'] = false;
		if ($playerID != '' && is_numeric ( $playerID ) && $playerID > 0 && $positionID != '' && is_numeric ( $positionID ) && $positionID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'playerID',
						1 => 'positionID' 
				);
				$values = array (
						0 => $playerID,
						1 => $positionID 
				);
				$playerPositionInsert = $this->createInsertQuery ( $this->playerPositionTable, $columns, $values );
				if ($playerPositionInsert) {
					$playerPositionResult = mysql_query ( $playerPositionInsert );
					if ($playerPositionResult) {
						$playerPositionID = mysql_insert_id ();
						$playerPositionSelect = $this->getPlayerPositionByID ( $playerPositionID );
						if ($playerPositionSelect) {
							$createPlayerPosition ['result'] = true;
							// PlayerPositiondaten
							$createPlayerPosition ['playerPositionID'] = $playerPositionSelect ['playerPositionID'];
							$createPlayerPosition ['playerID'] = $playerPositionSelect ['playerID'];
							$createPlayerPosition ['positionID'] = $playerPositionSelect ['positionID'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createPlayerPosition;
	}
	
	// Delete functions
	
	// ####PlayerTraining####
	// Get functions
	function getPlayerTrainingByID($id) {
		if (is_numeric ( $id )) {
			$playerTraining = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerTrainingSelect = $this->createSelectQuery ( '*', $this->playerTrainingTable, 'playerTrainingID = ' . $id, '', '' );
				if ($playerTrainingSelect) {
					$playerTrainingResult = mysql_query ( $playerTrainingSelect );
					
					if ($playerTrainingResult) {
						while ( $playerTrainingRow = mysql_fetch_array ( $playerTrainingResult ) ) {
							$playerTrainingID = $playerTrainingRow ['playerTrainingID'];
							$playerID = $playerTrainingRow ['playerID'];
							$trainingID = $playerTrainingRow ['trainingID'];
							$playerTrainingPresence = $playerTrainingRow ['playerTrainingPresence'];
							$playerTraining = array (
									"playerTrainingID" => $playerTrainingID,
									"playerID" => $playerID,
									"trainingID" => $trainingID,
									"playerTrainingPresence" => $playerTrainingPresence 
							);
						}
					}
				}
			}
		} else {
			$playerTraining = false;
		}
		return $playerTraining;
	}
	
	// Set functions
	function updatePlayerTraining($id, $playerID, $trainingID, $playerTrainingPresence) {
		$id=1;		
		$updatePlayerTraining = false;
		if (is_numeric ( $id )) {
			$table = $this->playerTrainingTable;
			
			$i = 0;
			if ($playerID = '') {
				$columns [$i] = 'playerID';
				$values [$i] = $playerID;
				$i ++;
			}
			if ($trainingID != '') {
				$columns [$i] = 'eventID';
				$values [$i] = $trainingID;
				$i ++;
			}
			if ($playerTrainingPresence != '') {
				$columns [$i] = 'attendanceValue';
				$values [$i] = $playerTrainingPresence;
				$i ++;
			}
			$where = 'playerID = '. $playerID.' AND eventID = '.$trainingID;
			$con = $this->getConnection ();
			if ($con == true) {
				$playerTrainingUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($playerTrainingUpdate) {
					$updatePlayerTrainingResult = mysql_query ( $playerTrainingUpdate );
					if ($updatePlayerTrainingResult) {
						$updatePlayerTraining = true;
					}
				}
			}
		} else {
			$updatePlayerTraining = false;
		}
		
		return $updatePlayerTraining;
	}
	
	// Create function
	function createPlayerTraining($playerID, $trainingID, $playerTrainingPresence) {
		$createPlayerTraining ['result'] = false;
		if ($playerID != '' && is_numeric ( $playerID ) && $playerID > 0 && $trainingID != '' && is_numeric ( $trainingID ) && $trainingID > 0 && $playerTrainingPresence != '' && is_numeric ( $playerTrainingPresence )) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'playerID',
						1 => 'eventID',
						2 => 'attendanceValue' 
				);
				$values = array (
						0 => $playerID,
						1 => $trainingID,
						2 => $playerTrainingPresence 
				);
				$playerTrainingCheckSelect = $this->createSelectQuery ( '*', $this->playerTrainingTable, 'playerID = ' . $playerID . ' AND eventDate = ' . $trainingID, '', '' );
				if ($playerTrainingCheckSelect) {
					$playerTrainingResult = mysql_query ( $playerTrainingCheckSelect );
					if (! $playerTrainingResult) {
						$playerTrainingInsert = $this->createInsertQuery ( $this->playerTrainingTable, $columns, $values );
						if ($playerTrainingInsert) {
							$playerTrainingResult = mysql_query ( $playerTrainingInsert );
							if ($playerTrainingResult) {
								$playerTrainingID = mysql_insert_id ();
								$playerTrainingSelect = $this->getPlayerTrainingByID ( $playerTrainingID );
								if ($playerTrainingSelect) {
									$createPlayerTraining ['result'] = true;
									// PlayerTrainingdaten
									$createPlayerTraining ['playerTrainingID'] = $playerTrainingSelect ['playerTrainingID'];
									$createPlayerTraining ['playerID'] = $playerTrainingSelect ['playerID'];
									$createPlayerTraining ['trainingID'] = $playerTrainingSelect ['trainingID'];
									$createPlayerTraining ['playerTrainingPresence'] = $playerTrainingSelect ['playerTrainingPresence'];
								}
							}
							// mysql_close();
						}
					} else {
						while ( $playerTrainingRow = mysql_fetch_array ( $playerTrainingResult ) ) {
							$playerTrainingUpdate = $this->updatePlayerTraining ( $playerTrainingRow ['playerTrainingID'], $playerID, $trainingID, $playerTrainingPresence );
							if ($playerTrainingUpdate) {
								$createPlayerTraining ['result'] = true;
								$createPlayerTraining ['playerTrainingID'] = $playerTrainingCheckSelect ['playerTrainingID'];
								$createPlayerTraining ['playerID'] = $playerTrainingCheckSelect ['playerID'];
								$createPlayerTraining ['trainingID'] = $playerTrainingCheckSelect ['trainingID'];
								$createPlayerTraining ['playerTrainingPresence'] = $playerTrainingPresence;
							}
						}
					}
				}
			}
		}
		return $createPlayerTraining;
	}
	
	// Delete functions
	
	// ####Position####
	// Get functions
	function getPositionByID($id) {
		if (is_numeric ( $id )) {
			$position = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$positionSelect = $this->createSelectQuery ( '*', $this->positionTable, 'positionID = ' . $id, '', '' );
				if ($positionSelect) {
					$positionResult = mysql_query ( $positionSelect );
					
					if ($positionResult) {
						while ( $positionRow = mysql_fetch_array ( $positionResult ) ) {
							$positionID = $positionRow ['positionID'];
							$positionLong = $positionRow ['positionLong'];
							$positionShort = $positionRow ['positionShort'];
							$position = array (
									"positionID" => $positionID,
									"positionLong" => $positionLong,
									"positionShort" => $positionShort 
							);
						}
					}
				}
			}
		} else {
			$position = false;
		}
		return $position;
	}
	
	// Set functions
	function updatePosition($id, $positionLong, $positionShort) {
		$updatePosition = false;
		
		if (is_numeric ( $id )) {
			$table = $this->positionTable;
			
			$i = 0;
			if ($positionLong = '') {
				$columns [$i] = 'positionLong';
				$values [$i] = $positionLong;
				$i ++;
			}
			if ($positionShort != '') {
				$columns [$i] = 'positionShort';
				$values [$i] = $positionShort;
				$i ++;
			}
			$where = 'positionID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$positionUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($positionUpdate) {
					$updatePositionResult = mysql_query ( $positionUpdate );
					if ($updatePositionResult) {
						$updatePosition = true;
					}
				}
			}
		} else {
			$updatePosition = false;
		}
		
		return $updatePosition;
	}
	
	// Create function
	function createPosition($positionLong, $positionShort) {
		$createPosition ['result'] = false;
		$con = $this->getConnection ();
		if ($con == true) {
			$columns = array (
					0 => 'positionLong',
					1 => 'positionShort' 
			);
			$values = array (
					0 => $positionLong,
					1 => $positionShort 
			);
			$positionInsert = $this->createInsertQuery ( $this->positionTable, $columns, $values );
			if ($positionInsert) {
				$positionResult = mysql_query ( $positionInsert );
				if ($positionResult) {
					$positionID = mysql_insert_id ();
					$positionSelect = $this->getPositionByID ( $positionID );
					if ($positionSelect) {
						$createPosition ['result'] = true;
						// Positiondaten
						$createPosition ['positionID'] = $positionSelect ['positionID'];
						$createPosition ['positionLong'] = $positionSelect ['positionLong'];
						$createPosition ['positionShort'] = $positionSelect ['positionShort'];
					}
				}
				// mysql_close();
			}
		}
		return $createPosition;
	}
	
	// Delete functions
	
	// ####TeamCoach####
	// Get functions
	function getTeamCoachByID($id) {
		if (is_numeric ( $id )) {
			$teamCoach = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamCoachSelect = $this->createSelectQuery ( '*', $this->teamCoachTable, 'teamCoachID = ' . $id, '', '' );
				if ($teamCoachSelect) {
					$teamCoachResult = mysql_query ( $teamCoachSelect );
					
					if ($teamCoachResult) {
						while ( $teamCoachRow = mysql_fetch_array ( $teamCoachResult ) ) {
							$teamCoachID = $teamCoachRow ['teamCoachID'];
							$coachID = $teamCoachRow ['coachID'];
							$teamID = $teamCoachRow ['teamID'];
							$teamCoach = array (
									"teamCoachID" => $teamCoachID,
									"coachID" => $coachID,
									"teamID" => $teamID 
							);
						}
					}
				}
			}
		} else {
			$teamCoach = false;
		}
		return $teamCoach;
	}
	function getTeamCoachByCoach($id) {
		if (is_numeric ( $id )) {
			$teamCoach = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamCoachSelect = $this->createSelectQuery ( '*', $this->teamCoachTable, 'coachID = ' . $id, '', '' );
				if ($teamCoachSelect) {
					$teamCoachResult = mysql_query ( $teamCoachSelect );
					
					if ($teamCoachResult) {
						while ( $teamCoachRow = mysql_fetch_array ( $teamCoachResult ) ) {
							$teamCoachID = $teamCoachRow ['teamCoachID'];
							$coachID = $teamCoachRow ['coachID'];
							$teamID = $teamCoachRow ['teamID'];
							$teamCoach = array (
									"teamCoachID" => $teamCoachID,
									"coachID" => $coachID,
									"teamID" => $teamID 
							);
						}
					}
				}
			}
		} else {
			$teamCoach = false;
		}
		return $teamCoach;
	}
	
	// Set functions
	function updateTeamCoach($id, $coachID, $teamID) {
		$updateTeamCoach = false;
		
		if (is_numeric ( $id )) {
			$table = $this->teamCoachTable;
			
			$i = 0;
			if ($coachID = '') {
				$columns [$i] = 'coachID';
				$values [$i] = $coachID;
				$i ++;
			}
			if ($teamID != '') {
				$columns [$i] = 'teamID';
				$values [$i] = $teamID;
				$i ++;
			}
			$where = 'teamID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamCoachUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($teamCoachUpdate) {
					$updateTeamCoachResult = mysql_query ( $teamCoachUpdate );
					if ($updateTeamCoachResult) {
						$updateTeamCoach = true;
					}
				}
			}
		} else {
			$updateTeamCoach = false;
		}
		
		return $updateTeamCoach;
	}
	
	// Create function
	function createTeamCoach($coachID, $teamID) {
		$createTeamCoach ['result'] = false;
		if ($coachID != '' && is_numeric ( $coachID ) && $coachID > 0 && $teamID != '' && is_numeric ( $teamID ) && $teamID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'coachID',
						1 => 'teamID' 
				);
				$values = array (
						0 => $coachID,
						1 => $teamID 
				);
				$teamCoachInsert = $this->createInsertQuery ( $this->teamCoachTable, $columns, $values );
				if ($teamCoachInsert) {
					$teamCoachResult = mysql_query ( $teamCoachInsert );
					if ($teamCoachResult) {
						$teamCoachID = mysql_insert_id ();
						$teamCoachSelect = $this->getTeamCoachByID ( $teamCoachID );
						if ($teamCoachSelect) {
							$createTeamCoach ['result'] = true;
							// TeamCoachdaten
							$createTeamCoach ['teamCoachID'] = $teamCoachSelect ['teamCoachID'];
							$createTeamCoach ['coachID'] = $teamCoachSelect ['coachID'];
							$createTeamCoach ['teamID'] = $teamCoachSelect ['teamID'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createTeamCoach;
	}
	
	// Delete functions
	
	// ####TeamMatch####
	// Get functions
	function getTeamMatchByID($id) {
		if (is_numeric ( $id )) {
			$teamMatch = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamMatchSelect = $this->createSelectQuery ( '*', $this->teamMatchTable, 'teamMatchID = ' . $id, '', '' );
				if ($teamMatchSelect) {
					$teamMatchResult = mysql_query ( $teamMatchSelect );
					
					if ($teamMatchResult) {
						while ( $teamMatchRow = mysql_fetch_array ( $teamMatchResult ) ) {
							$teamMatchID = $teamMatchRow ['teamMatchID'];
							$teamID = $teamMatchRow ['teamID'];
							$matchID = $teamMatchRow ['matchID'];
							$teamMatchGoals = $teamMatchRow ['teamMatchGoals'];
							$teamMatchPoints = $teamMatchRow ['teamMatchPoints'];
							$teamMatchYellowCards = $teamMatchRow ['teamMatchYellowCards'];
							$teamMatchRedCards = $teamMatchRow ['teamMatchRedCards'];
							$teamMatch = array (
									"teamMatchID" => $teamMatchID,
									"teamID" => $teamID,
									"matchID" => $matchID,
									"teamMatchGoals" => $teamMatchGoals,
									"teamMatchPoints" => $teamMatchPoints,
									"teamMatchYellowCards" => $teamMatchYellowCards,
									"teamMatchRedCards" => $teamMatchRedCards 
							);
						}
					}
				}
			}
		} else {
			$teamMatch = false;
		}
		return $teamMatch;
	}
	
	// Set functions
	function updateTeamMatch($id, $teamID, $matchID, $teamMatchGoals, $teamMatchYellowCards, $teamMatchRedCards, $teamMatchPoints) {
		$updateTeamMatch = false;
		
		if (is_numeric ( $id )) {
			$table = $this->teamMatchTable;
			
			$i = 0;
			if ($teamID = '') {
				$columns [$i] = 'teamID';
				$values [$i] = $teamID;
				$i ++;
			}
			if ($matchID != '') {
				$columns [$i] = 'matchID';
				$values [$i] = $matchID;
				$i ++;
			}
			if ($teamMatchGoals != '') {
				$columns [$i] = 'teamMatchGoals';
				$values [$i] = $teamMatchGoals;
				$i ++;
			}
			if ($teamMatchYellowCards != '') {
				$columns [$i] = 'teamMatchYellowCards';
				$values [$i] = $teamMatchYellowCards;
				$i ++;
			}
			if ($teamMatchRedCards != '') {
				$columns [$i] = 'teamMatchRedCards';
				$values [$i] = $teamMatchRedCards;
				$i ++;
			}
			if ($teamMatchPoints != '') {
				$columns [$i] = 'teamMatchPoints';
				$values [$i] = $teamMatchPoints;
				$i ++;
			}
			$where = 'teamMatchID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamMatchUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($teamMatchUpdate) {
					$updateTeamMatchResult = mysql_query ( $teamMatchUpdate );
					if ($updateTeamMatchResult) {
						$updateTeamMatch = true;
					}
				}
			}
		} else {
			$updateTeamMatch = false;
		}
		
		return $updateTeamMatch;
	}
	
	// Create function
	function createTeamMatch($teamID, $matchID, $teamMatchGoals, $teamMatchPoints, $teamMatchYellowCards, $teamMatchRedCards) {
		$createTeamMatch ['result'] = false;
		if ($teamID != '' && is_numeric ( $teamID ) && $teamID > 0 && $matchID != '' && is_numeric ( $matchID ) && $matchID > 0 && $teamMatchGoals != '' && is_numeric ( $teamMatchGoals ) && $teamMatchPoints != '' && is_numeric ( $teamMatchPoints ) && $teamMatchYellowCards != '' && is_numeric ( $teamMatchYellowCards ) && $teamMatchRedCards != '' && is_numeric ( $teamMatchRedCards )) {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'teamID',
						1 => 'matchID',
						2 => 'teamMatchGoals',
						3 => 'teamMatchPoints',
						4 => 'teamMatchYellowCards',
						5 => 'teamMatchRedCards' 
				);
				$values = array (
						0 => $teamID,
						1 => $matchID,
						2 => $teamMatchGoals,
						3 => $teamMatchPoints,
						4 => $teamMatchYellowCards,
						5 => $teamMatchRedCards 
				);
				$teamMatchInsert = $this->createInsertQuery ( $this->teamMatchTable, $columns, $values );
				if ($teamMatchInsert) {
					$teamMatchResult = mysql_query ( $teamMatchInsert );
					if ($teamMatchResult) {
						$teamMatchID = mysql_insert_id ();
						$teamMatchSelect = $this->getTeamMatchByID ( $teamMatchID );
						if ($teamMatchSelect) {
							$createTeamMatch ['result'] = true;
							// TeamMatchdaten
							$createTeamMatch ['teamMatchID'] = $teamMatchSelect ['teamMatchID'];
							$createTeamMatch ['teamID'] = $teamMatchSelect ['teamID'];
							$createTeamMatch ['matchID'] = $teamMatchSelect ['matchID'];
							$createTeamMatch ['teamMatchGoals'] = $teamMatchSelect ['teamMatchGoals'];
							$createTeamMatch ['teamMatchPoints'] = $teamMatchSelect ['teamMatchPoints'];
							$createTeamMatch ['teamMatchYellowCards'] = $teamMatchSelect ['teamMatchYellowCards'];
							$createTeamMatch ['teamMatchRedCards'] = $teamMatchSelect ['teamMatchRedCards'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createTeamMatch;
	}
	
	// Delete functions
	
	// ####TeamType####
	// Get functions
	function getTeamTypeByID($id) {
		if (is_numeric ( $id )) {
			$teamType = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamTypeSelect = $this->createSelectQuery ( '*', $this->teamTypeTable, 'teamTypeID = ' . $id, '', '' );
				if ($teamTypeSelect) {
					$teamTypeResult = mysql_query ( $teamTypeSelect );
					
					if ($teamTypeResult) {
						while ( $teamTypeRow = mysql_fetch_array ( $teamTypeResult ) ) {
							$teamTypeID = $teamTypeRow ['teamTypeID'];
							$teamTypeName = $teamTypeRow ['teamTypeName'];
							$teamType = array (
									"teamTypeID" => $teamTypeID,
									"teamTypeName" => $teamTypeName 
							);
						}
					}
				}
			}
		} else {
			$teamType = false;
		}
		return $teamType;
	}
	
	// Set functions
	function updateTeamType($id, $teamTypeName) {
		$updateTeamType = false;
		
		if (is_numeric ( $id )) {
			$table = $this->teamTypeTable;
			
			$i = 0;
			if ($teamTypeName = '') {
				$columns [$i] = 'teamTypeName';
				$values [$i] = $teamTypeName;
				$i ++;
			}
			$where = 'teamTypeID = ' . $id;
			$con = $this->getConnection ();
			if ($con == true) {
				$teamTypeUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
				if ($teamTypeUpdate) {
					$updateTeamTypeResult = mysql_query ( $teamTypeUpdate );
					if ($updateTeamTypeResult) {
						$updateTeamType = true;
					}
				}
			}
		} else {
			$updateTeamType = false;
		}
		
		return $updateTeamType;
	}
	
	// Create function
	function createTeamType($teamTypeName) {
		$createTeamType ['result'] = false;
		if ($teamTypeName != '') {
			$con = $this->getConnection ();
			if ($con == true) {
				$columns = array (
						0 => 'teamTypeName' 
				);
				$values = array (
						0 => $teamTypeName 
				);
				$teamTypeInsert = $this->createInsertQuery ( $this->teamTypeTable, $columns, $values );
				if ($teamTypeInsert) {
					$teamTypeResult = mysql_query ( $teamTypeInsert );
					if ($teamTypeResult) {
						$teamTypeID = mysql_insert_id ();
						$teamTypeSelect = $this->getTeamTypeByID ( $teamTypeID );
						if ($teamTypeSelect) {
							$createTeamType ['result'] = true;
							// TeamTypedaten
							$createTeamType ['teamTypeID'] = $teamTypeSelect ['teamTypeID'];
							$createTeamType ['teamTypeName'] = $teamTypeSelect ['teamTypeName'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createTeamType;
	}
	
	// Delete functions
	
	// ####Tournament####
	// Get functions
	function getTournamentByID($id) {
		if (is_numeric ( $id )) {
			$tournament = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$tournamentSelect = $this->createSelectQuery ( '*', $this->tournamentTable, 'tournamentID = ' . $id, '', '' );
				if ($tournamentSelect) {
					$tournamentResult = mysql_query ( $tournamentSelect );
					
					if ($tournamentResult) {
						while ( $tournamentRow = mysql_fetch_array ( $tournamentResult ) ) {
							$tournamentID = $tournamentRow ['tournamentID'];
							$eventID = $tournamentRow ['eventID'];
							$tournamentName = $tournamentRow ['tournamentName'];
							$tournament = array (
									"tournamentID" => $tournamentID,
									"eventID" => $eventID,
									"tournamentName" => $tournamentName 
							);
						}
					}
				}
			}
		} else {
			$tournament = false;
		}
		return $tournament;
	}
	
	// Set functions
	function updateTournament($id, $tournamentName, $event, $eventDate, $eventTime, $locationID) {
		$updateTournament = false;
		$updateEvent = $this->updateEvent ( $id, $eventDate, $eventTime, $locationID );
		if ($updateEvent) {
			if (is_numeric ( $id )) {
				$table = $this->tournamentTable;
				
				$i = 0;
				if ($tournamentName != '') {
					$columns [$i] = 'tournamentName';
					$values [$i] = $tournamentName;
					$i ++;
				}
				if ($eventID != '') {
					$columns [$i] = 'eventID';
					$values [$i] = $updateEvent ['eventID'];
					$i ++;
				}
				$where = 'tournamentID = ' . $id;
				$con = $this->getConnection ();
				if ($con == true) {
					$tournamentUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
					if ($tournamentUpdate) {
						$updateTournamentResult = mysql_query ( $tournamentUpdate );
						if ($updateTournamentResult) {
							$updateTournament = true;
						}
					}
				}
			} else {
				$updateTournament = false;
			}
		}
		return $updateTournament;
	}
	
	// Create function
	function createTournament($tournamentName, $eventDate, $eventTime, $locationID) {
		$createTournament ['result'] = false;
		if ($tournamentName != '' && $eventDate != '' && $eventTime != '' && $locationID != '' && is_numeric ( $locationID ) && $locationID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$event = $this->createEvent ( $eventDate, $eventTime, $locationID );
				$columns = array (
						0 => 'tournamentName',
						1 => 'eventID' 
				);
				$values = array (
						0 => $tournamentName,
						1 => $event ['eventID'] 
				);
				$tournamentInsert = $this->createInsertQuery ( $this->tournamentTable, $columns, $values );
				if ($tournamentInsert) {
					$tournamentResult = mysql_query ( $tournamentInsert );
					if ($tournamentResult) {
						$tournamentID = mysql_insert_id ();
						$tournamentSelect = $this->getTournamentByID ( $tournamentID );
						if ($tournamentSelect) {
							$createTournament ['result'] = true;
							// Teamdaten
							$createTournament ['tournamentID'] = $tournamentSelect ['tournamentID'];
							$createTournament ['tournamentName'] = $tournamentSelect ['tournamentName'];
							$createTournament ['eventID'] = $event ['eventID'];
							$createTournament ['eventDate'] = $event ['eventDate'];
							$createTournament ['eventTime'] = $event ['eventTime'];
							$createTournament ['locationID'] = $event ['locationID'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createTournament;
	}
	
	// Delete functions
	
	// ####Training####
	// Get functions
	function getTrainingByID($id) {
		if (is_numeric ( $id )) {
			$training = false;
			$con = $this->getConnection ();
			if ($con == true) {
				$trainingSelect = $this->createSelectQuery ( '*', $this->trainingTable, 'trainingID = ' . $id, '', '' );
				if ($trainingSelect) {
					$trainingResult = mysql_query ( $trainingSelect );
					
					if ($trainingResult) {
						while ( $trainingRow = mysql_fetch_array ( $trainingResult ) ) {
							$trainingID = $trainingRow ['trainingID'];
							$eventID = $trainingRow ['eventID'];
							$teamID = $trainingRow ['teamID'];
							$training = array (
									"trainingID" => $trainingID,
									"eventID" => $eventID,
									"teamID" => $teamID 
							);
						}
					}
				}
			}
		} else {
			$training = false;
		}
		return $training;
	}
	
	// Set functions
	function updateTraining($id, $teamID, $event, $eventDate, $eventTime, $locationID) {
		$updateTraining = false;
		$updateEvent = $this->updateEvent ( $id, $eventDate, $eventTime, $locationID );
		if ($updateEvent) {
			if (is_numeric ( $id )) {
				$table = $this->trainingTable;
				
				$i = 0;
				if ($teamID != '') {
					$columns [$i] = 'teamID';
					$values [$i] = $teamID;
					$i ++;
				}
				if ($eventID != '') {
					$columns [$i] = 'eventID';
					$values [$i] = $updateEvent ['eventID'];
					$i ++;
				}
				$where = 'trainingID = ' . $id;
				$con = $this->getConnection ();
				if ($con == true) {
					$trainingUpdate = $this->createUpdateQuery ( $table, $columns, $values, $where );
					if ($trainingUpdate) {
						$updateTrainingResult = mysql_query ( $trainingUpdate );
						if ($updateTrainingResult) {
							$updateTraining = true;
						}
					}
				}
			} else {
				$updateTraining = false;
			}
		}
		return $updateTraining;
	}
	
	// Create function
	function createTraining($teamID, $eventDate, $eventTime, $locationID) {
		$createTraining ['result'] = false;
		if ($teamID != '' && is_numeric ( $teamID ) && $teamID > 0 && $eventDate != '' && $eventTime != '' && $locationID != '' && is_numeric ( $locationID ) && $locationID > 0) {
			$con = $this->getConnection ();
			if ($con == true) {
				$event = $this->createEvent ( $eventDate, $eventTime, $locationID );
				$columns = array (
						0 => 'teamID',
						1 => 'eventID' 
				);
				$values = array (
						0 => $teamID,
						1 => $event ['eventID'] 
				);
				$trainingInsert = $this->createInsertQuery ( $this->trainingTable, $columns, $values );
				if ($trainingInsert) {
					$trainingResult = mysql_query ( $trainingInsert );
					if ($trainingResult) {
						$trainingID = mysql_insert_id ();
						$trainingSelect = $this->getTrainingByID ( $trainingID );
						if ($trainingSelect) {
							$createTraining ['result'] = true;
							// Trainingdaten
							$createTraining ['trainingID'] = $trainingSelect ['trainingID'];
							$createTraining ['teamID'] = $trainingSelect ['teamID'];
							$createTraining ['eventID'] = $event ['eventID'];
							$createTraining ['eventDate'] = $event ['eventDate'];
							$createTraining ['eventTime'] = $event ['eventTime'];
							$createTraining ['locationID'] = $event ['locationID'];
						}
					}
					// mysql_close();
				}
			}
		}
		return $createTraining;
	}
	
	// Delete functions
	
	// ####Hilfe Funktionen####
	// Sicheres Verschlüsseln
	function cryptPassword($input) {
		$rounds = 9;
		$salt = "";
		// Range erstellt ein Array mit Zeichen von bis...
		// Diese werden mit array_merge zusammengefï¿½gt
		$saltChars = array_merge ( range ( 'A', 'Z' ), range ( 'a', 'z' ), range ( 0, 9 ) );
		for($i = 0; $i < 22; $i ++) {
			$salt .= $saltChars [array_rand ( $saltChars )];
		}
		// Verwenden von Blowfish ($2y$)
		return crypt ( $input, sprintf ( '$2y$%02d$', $rounds ) . $salt );
	}
	
	// Mail erstellen
	function generateMail($clubName, $userKey, $userEmail) {
		// Daten fï¿½r E-Mail Nachricht festlegen
		// $bestaetigungslink = "<a href='manageyourteam.de/bestaetigung.php?schluessel=".$schluessel."&verein=".$verein."'>Accont aktivieren</a>";
		$bestaetigungslink = "<a href='http://manageyourteam.de/Bestaetigung/" . rawurlencode ( $clubName ) . "/" . $userKey . "'>Account aktivieren</a>";
		$bestaetigungslink_alternativ = "http://manageyourteam.de/Bestaetigung/" . rawurlencode ( $clubName ) . "/" . $userKey;
		// Daten fï¿½r Nachricht an Registrar
		$sender = "MAIL_FROM_ADDRESS";
		$receiver = $userEmail;
		$mailtext = "<div>Sehr geehrter User,<br>Bitte betï¿½tigen Sie folgenden Link, um Ihr Benutzerkonto sowie Ihren Verein freizuschalten: $bestaetigungslink<br><br>Alternativ kï¿½nnen Sie auch folgenden Link in die Adressleiste Ihres Browsers kopieren: <br>$bestaetigungslink_alternativ<br>Im Anschluss daran kï¿½nnen Sie sich einloggen.<br><br>Schï¿½ne Grï¿½ï¿½e<br><br>	Das Team von ManageYourTeam<br>-----------------------------------------------------------------------------<br>Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.</div>";
		$subject = "Neue Bestï¿½tigungsmail";
		
		echo $bestaetigungslink_alternativ . '<br>' . $receiver;
		
		// Nachricht senden
		// sendEmail($sender, $receiver, $mailtext, $subject);
	}
	
	// Mail versenden
	function sendEmail($sender, $receiver, $mailtext, $subject) {
		
		// Include the PHPMailer library
		require_once ('PHPMailerAutoload.php');
		
		// Neue Instanz erstellen
		$mailer = new PHPMailer ( true );
		
		// Verbindung aufbauen
		$mailer->IsSMTP (); // send via SMTP
		$mailer->SMTPAuth = true;
		$mailer->SMTPSecure = 'ssl';
		$mailer->Port = 465;
		$mailer->Host = "SMTP_HOST"; // SMTP servers
		$mailer->Username = "SMTP_USER"; // SMTP username
		$mailer->Password = "SMTP_PASSWORD"; // SMTP password
		                                
		// Senderinformationen
		$mailer->Sender = $sender;
		$mailer->AddReplyTo ( $sender, 'Manage Your Team' );
		$mailer->SetFrom ( $sender, 'Manage Your Team' );
		
		// Empfï¿½nger eintragen
		$teile = explode ( ";", $receiver );
		reset ( $teile );
		while ( list ( $key, $val ) = each ( $teile ) ) {
			$mailer->AddBCC ( $val );
		}
		
		/*
		 * // Anhï¿½nge anhï¿½ngen $teile = explode(";", $anhang); reset ($teile); while (list ($key, $val) = each ($teile)) { $dateiname = explode("/", $val); $dateiname = array_pop($dateiname); // Anhï¿½ngen $mailer->AddAttachment($val, $dateiname); }
		 */
		
		// Als HTML senden
		$mailer->IsHTML ( true );
		$mailer->CharSet = 'utf-8';
		
		$mailer->Subject = $subject;
		$mailer->Body = $mailtext;
		$mailer->AltBody = $mailtext;
		
		// Falls Senden fehlschlug
		if (! $mailer->Send ()) {
			exit ();
		}
		
		// Felder zurï¿½cksetzen
		$mailer->ClearAddresses ();
	}
}
?>
