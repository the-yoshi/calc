<?php

class Assignment extends Storable {
	public $id;
	public $description;
	public $type;
	public $termScheme;
	public $creator;
	
	private $variables;
	
	
	
	public function getVariables() {
		$ret = array();
		foreach ($this->variables as $v) {
			$ret[] = StorageManager::getById("Variable", $v->variableId);
		}
		return $ret;
	}
	
	## IStorable methods
	##
	
	public function __construct() {
		
	}
	
	public static function fromArray ($array) {
		$r = new Assignment();	
		$r->id = $array[0];
		$r->description = $array[1];
		$r->type = $array[2];
		$r->termScheme = $array[3];
		$r->creator = $array[4];
		
		# fetch variables
		$r->variables = StorageManager::getByCondition("AssignmentVariableRelation", "assignmentid = '".$r->id."'");
		return $r;
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
		return $this->variables;
	}
}

?>