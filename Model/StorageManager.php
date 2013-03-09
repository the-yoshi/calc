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
			if ($result[0] == NULL)
				return array();
			foreach ($result as $obj) {
				$ret[] = $type::fromArray($obj);
			}
		}
		return $ret;
	}
	
	public static function getSorted($type, $field, $ascending) {
		if ($ascending)
			$order = "ASC";
		else
			$order = "DESC";
		$tail = " ORDER BY $field $order";
		
		$proto = new $type();
		$sql = "SELECT * FROM ".$proto->getStorableName().$tail;
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
			
			if ($fields[0] == "id" && StorageManager::getById(get_class($storable), $values[0]) == NULL) {
				# insert new row
				$values[0] = StorageManager::getNewId($storable->getStorableName());
				$sql = "INSERT INTO ".$table." (".implode(",", $fields).") VALUES (".implode(",", $values).") ".$cond;
				$result = StorageManager::insertQuery($sql);
				if ($result)
					return $values[0];
				else
					return -1;
			} else if ($fields[0] != "id") {
				$sql = "INSERT INTO ".$table." (".implode(",", $fields).") VALUES (".implode(",", $values).") ".$cond;
				$result = StorageManager::insertQuery($sql);
				if ($result)
					return $values[0];
				else
					return -1;
			}
		}
	}

	public static function delete($storable) {
		if ($storable->isStorable()) {
			$table = $storable->getStorableName();
			$fields = $storable->getStorableFields();
			$values = $storable->getStorableValues();
			
			$cond = "";
			#$cond .= "id = ".$storable->id;
			for ($i=0; $i<count($fields);$i++) {
				$cond .= $fields[$i]." = '".$values[$i]."'";
				if ($i<count($fields)-1)
					$cond .= " AND ";
			}
			
			
			$sql = "DELETE FROM $table WHERE $cond";
			# yup, insertQuery. got to give that function a better name.
			return StorageManager::insertQuery($sql);
		}
	}

	## PRIVATE FUNCTIONS

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
		
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid = $account AND examid = $uebung");
		$all = count($historyItems);
		$correct = 0;
		foreach ($historyItems as $item) {
			if ($item->isCorrect()) {
				$correct++;
			}
		}
		if ($all == 0 || $correct == 0)
			return 0;
		else
			return Round(($correct/$all)*100, 2);
	}
	
	public static function getLatestCorrectAnswersPercentage ($account, $uebung) {
			
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid = $account AND examid = $uebung AND date = (SELECT MAX(date) FROM historyitem WHERE accountid = '$account' AND examid = '$uebung')");
		$all = count($historyItems);
		$correct = 0;
		foreach ($historyItems as $item) {
			if ($item->isCorrect()) {
				$correct++;
			}
		}
		if ($all == 0 || $correct == 0)
			return 0;
		else
			return Round(($correct/$all)*100, 2);
	}
}

?>
