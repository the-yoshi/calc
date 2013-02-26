<?php

# the StorageManager is the only component communicating with the database.
# apart from general get() and store() methods for IStorables,
# it holds hard-coded functions for special database access (e.g. count)

class StorageManager {
	
	private static $db;
	
	# übergangsweise mit drin:
	public static function getDatabase() {
		return StorageManager::$db;
	}
	
	public static function init() {
		StorageManager::connect();
	}
	
	private static function connect() {
		$MYSQL_HOSTNAME = "localhost";
		$MYSQL_USERNAME = "crud";
		$MYSQL_PASSWORD = "rw";
		$MYSQL_WORLD = "kopfrechnen";
		
		$sql = new mysqli($MYSQL_HOSTNAME, $MYSQL_USERNAME, $MYSQL_PASSWORD, $MYSQL_WORLD);
		
		if ($sql->errno) {
			echo "Connect failed.";
			return false;
		} else {
			StorageManager::$db = $sql; 
			return true;
		}
	}
	
	# fetches all objects of type $type from table new $type()->getStorableName()
	public static function get($type) {
		$proto = new $type();
		$sql = "SELECT * FROM ".$proto->getStorableName().";";
		$result = StorageManager::query($sql);
		$ret = array();
		if ($result != NULL) {
			foreach ($result as $obj) {
				$ret[] = $type::fromArray($obj);
			}
		}
		return $ret;
	}
	
	# fetches single object with id = $id
	# returns NULL when nothing found
	public static function getById($type, $id) {
		$r = StorageManager::getByCondition($type, "id = '".$id."'");
		if (count($r) > 0)
			return $r[0];
		else
			return NULL;
	}
	
	public static function getByCondition($type, $condition) {
		$proto = new $type();
		$sql = "SELECT * FROM ".$proto->getStorableName()." WHERE ".$condition;
		$result = StorageManager::query($sql);
		
		$ret = array();
		if ($result != NULL) {
			foreach ($result as $obj) {
				$ret[] = $type::fromArray($obj);
			}
		}
		return $ret;
	}
	
	public static function store($storable) {
		if ($storable->isStorable()) {
			$cond = "";
			$table = $storable->getStorableName();
			$fields = $storable->getStorableFields();
			$values = $storable->getStorableValues();
			
			for ($i=0;$i<count($values);$i++) {
				$values[$i] = "'".$values[$i]."'";
			}
			
			
			if (StorageManager::getById(get_class($storable), $values[0]) == NULL) {
				# insert new row
				$values[0] = StorageManager::getNewId($storable->getStorableName());
				$sql = "INSERT INTO ".$table." (".implode(",", $fields).") VALUES (".implode(",", $values).") ".$cond;
				$result = StorageManager::insertQuery($sql);
				return $values[0];
			} else {
				# update existing row
				$cond = "WHERE ".$fields[0]." = '".$values[0]."'";
				$fields = StorageManager::cutArray($fields);
				$values = StorageManager::cutArray($values);
				$setString = "SET ";
				for ($i = 0; $i<count($fields); $i++) {
					$setString .= $fields[$i]." = ".$values[$i].",";
				}
				$setString = substr($setString, 0, strlen($setString)-1);
				
				$sql = "UPDATE ".$table."".$setString.$cond;
				$result = StorageManager::insertQuery($sql);
			
				return $result;
			}
		}
	}

	# cuts the first field from the array
	private static function cutArray($arr) {
		$ret = array();
		for ($i=1;$i<count($arr);$i++) {
			$ret[] = $arr[$i];
		}
		return $ret;
	}
	
	private static function insertQuery($sql) {
		$db = StorageManager::$db;
		$erg = $db->query($sql);
		return $erg;
	}
	
	private static function query($sql) {
		$db = StorageManager::$db;
		$erg = $db->query($sql);
		
		if ($erg) {
			$array = array();
			while ($e = $erg->fetch_array()) {
				$array[] = $e;
			}
			return $array;
		}
		else
			return NULL;
	}
	
	private static function getNewId($tableName) {
		$sql = "SELECT MAX(id) AS id FROM $tableName";
		$last = StorageManager::query($sql);
		return $last[0][0]+1;
	}
	
	####### CUSTOM QUERIES

	
	public static function getCorrectAnswersPercentage ($account, $uebung) {
		$sql = "SELECT COUNT(*) AS num FROM historyitem
				WHERE accountid = '$account' AND examid = '$uebung' AND correctresult = givenresult";
		$correct = StorageManager::query($sql);
		$correct = $correct[0]["num"];
		$sql = "SELECT COUNT(*) AS num FROM historyitem
				WHERE accountid = '$account' AND examid = '$uebung'";
		$all = StorageManager::query($sql);
		$all = $all[0]["num"];
		
		if ($all > 0)
			return Round(($correct/$all)*100, 2);
		else
			return "";
	}
	
	public static function getLatestCorrectAnswersPercentage ($account, $uebung) {
		$sql = "SELECT COUNT(*) AS num FROM historyitem
				WHERE accountid = '$account' AND examid = '$uebung' AND correctresult = givenresult AND date = (SELECT MAX(date) FROM historyitem WHERE accountid = '$account' AND examid = '$uebung')";
		$correct = StorageManager::query($sql);
		$correct = $correct[0]["num"];
		$sql = "SELECT COUNT(*) AS num FROM historyitem
				WHERE accountid = '$account' AND examid = '$uebung' AND date = (SELECT MAX(date) FROM historyitem WHERE accountid = '$account' AND examid = '$uebung')";
		$all = StorageManager::query($sql);
		$all = $all[0]["num"];

		if ($all > 0)
			return Round(($correct/$all)*100, 2);
		else
			return "";
	}
}

?>
