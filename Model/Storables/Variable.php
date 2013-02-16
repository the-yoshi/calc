<?php

class Variable extends Storable {
	public $id;
	public $name;
	public $lowerBound;
	public $upperBound;
	
	
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
		return $r;
	}
	
	public function getStorableName() {
		return "variable";
	}
	
	public function getStorableFields() {
		return(array('id', 'name', 'lowerBound', 'upperBound'));
	}
	
	public function getStorableValues() {
		return(array($this->id, $this->name, $this->lowerBound, $this->upperBound));
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>