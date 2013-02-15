<?php

interface IStorable {
	
	## IMPLEMENTED FUNCTIONS
	##
	
	#
	public function isStorable() {
		return TRUE;
	}
	
	public function store() {
		foreach ($this->getStorableRelations() as $relation) {
			$relation->store();
		}
		
		StorageManager::store($this);
	}
	
	## ABSTRACT FUNCTIONS
	##
	
	# must be creatable via $array containing values in field-order
	public abstract function __construct($array);
	# returns the stored representation's name (e.g. table name)
	public abstract function getStorableName();
	# returns an array containing the name of the object's relevant (stored) fields - NOT the one-to-many relations, see below
	public abstract function getStorableFieldNames();
	# returns an array containing values for the fields in getFieldNames()
	public abstract function getStorableValues();
	# returns an array containing one IStorable for every one-to-many relation the object has 
	public abstract function getStorableRelations();
}

?>