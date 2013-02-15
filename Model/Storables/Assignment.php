<?php

class Assignment implements IStorable {
	public $id;
	public $description;
	public $type;
	public $termScheme;
	public $creator;
	
	private $variables;
	
	## IStorable methods
	##
	
	public function __construct ($array) {
		$this->id = $array[0];
		$this->description = $array[1];
		$this->type = $array[2];
		$this->termScheme = $array[3];
		$this->creator = $array[4];
		
		# fetch variables
		
	}
	
	public function getStorableName() {
		return "assignment";
	}
	
	public function getStorableFields() {
		return array('id', 'description', 'type', 'termscheme', 'creator');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->description, $this->type, $this->termScheme, $this->creator);
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>