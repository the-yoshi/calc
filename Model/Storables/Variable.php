<?php

class Variable implements IStorable {
	public $id;
	public $name;
	public $lowerBound;
	public $upperBound;
	
	
	## IStorable methods
	##
	
	public abstract function __construct($array) {
		$this->id = $array[0];
		$this->name = $array[1];
		$this->lowerBound = $array[2];
		$this->upperBound = $array[3];
	}
	
	public function getStorableName() {
		return "class";
	}
	
	public function getStorableFieldNames() {
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