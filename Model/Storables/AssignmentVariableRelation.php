<?php

class AssignmentVariableRelation extends Storable {
	
	public $assignmentId;
	public $variableId;
	

	
	## IStorable methods
	##
	public function __construct() {
	}
	
	public static function fromArray($array) {
		$r = new AssignmentVariableRelation();
		$r->assignmentId = $array[0];
		$r->variableId = $array[1];
		return $r;
	}
	public function getStorableName() {
		return "assignment_variable";
	}
	
	public function getStorableFields() {
		return(array('assignmentid', 'variableid'));
	}
	
	public function getStorableValues() {
		return(array($this->assignmentId, $this->variableId));
	}
	
	public function getStorableRelations() {
		return array();
	}
}


?>