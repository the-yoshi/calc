<?php

# the StorageManager is the only component communicating with the database.
# apart from general get() and store() methods for IStorables,
# it holds hard-coded functions for special database access (e.g. count)

class StorageManager {
	
	private static $db;
	
	public static function init() {
		StorageManager::$db = new mysqli("localhost", "crud", "rw", "kopfrechnen");
	}
	
	# fetches all rows from table (new $type->getStorableName())
	public static function get($type) {
		$proto = new $type();
		$sql = "SELECT * FROM ".$proto->getStorableName()." WHERE ".$condition.";";
		$result = query($sql);
		return $result;
	}
	
	# returns NULL when nothing found
	public static function getById($type, $id) {
		$proto = new $type();
		StorageManager::getByCondition($proto->getStorableName(), "id = '".$id."'");
	}
	
	public static function getByCondition($type, $condition) {
		$proto = new $type();
		$sql = "SELECT * FROM ".$proto->getStorableName()." WHERE ".$condition.";";
		$result = query($sql);
		return $result;
	}
	
	public static function store($storable) {
		if ($storable->isStorable()) {
			$cond = "";
			$table = $storable->getStorableName();
			$fields = $storable->getStorableFields();
			$values = $storable->getStorableValues();
			
			foreach ($values as $value) {
				$value = "'".$value."'";
			}
			
			
			if (StorageManager::getById($table, $fields[0]) == NULL) {
				# insert new row
				$values[0] = "NULL";
				$sql = "INSERT INTO ".$table." (".implode(",", $fields).") VALUES (".implode(",", $values).") ".$cond.";";
				$result = query($sql);
			
				return $result;
			} else {
				# update existing row
				$cond = "WHERE ".$fields[0]." = '".$values[0]."'";
				$setString = "SET ";
				for ($i = 0; $i<count($fields); $i++) {
					$setString .= $fields[$i]." = ".$values[$i].",";
				}
				$setString = substr($setString, 0, strlen($setString)-1);
				
				$sql = "UPDATE ".$table." (".implode(",", $fields).") VALUES (".implode(",", $values).") ".$cond.";";
				$result = query($sql);
			
				return $result;
			}
		}
	}
	
	private static function query($sql) {
		$db = $this->db;
		$erg = $db->query($sql);
		
		$array = array();
		while ($e = $erg->fetch_array()) {
			$array[] = $e;
		}
		return $array;
	}
}

?>
