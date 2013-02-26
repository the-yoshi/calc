<?php

class Variable extends Storable {
	public $id;
	public $name;
	public $lowerBound;
	public $upperBound;
	public $assignmentId;
	
	
	## IStorable methods
	##
	
	public function __construct() {
		
	}
	
	public static function fromArray($array) {
		$r = new Variable();
		$r->id = $array[0];
		$r->name = $array[1];
		$r->lowerBound = $array[2];
		$r->upperBound = $array[3];
		$r->assignmentId = $array[4];
		return $r;
	}
	
	public function getStorableName() {
		return "variable";
	}
	
	public function getStorableFields() {
		return(array('id', 'name', 'lowerbound', 'upperbound', 'assignmentid'));
	}
	
	public function getStorableValues() {
		return(array($this->id, $this->name, $this->lowerBound, $this->upperBound, $this->assignmentId));
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>