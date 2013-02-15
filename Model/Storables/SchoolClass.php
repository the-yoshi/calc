<?php

class SchoolClass implements IStorable {
	public $id;
	public $name;
	
	
	## IStorable methods
	##
	
	public abstract function __construct($array) {
		$this->id = $array[0];
		$this->name = $array[1];
	}
	
	public function getStorableName() {
		return "class";
	}
	
	public function getStorableFieldNames() {
		return(array('id', 'name'));
	}
	
	public function getStorableValues() {
		return(array($this->id, $this->name));
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>