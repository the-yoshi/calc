<?php 

class ExamAssignmentRelation extends Storable {
	
	public $examId;
	public $assignmentId;
	public $count;
	
	public function __construct() {
		
	}
	
	
	## IStorable methods
	##
	
	public static function fromArray ($array) {
		$r = new ExamAssignmentRelation();
		$r->examId = $array[0];
		$r->assignmentId = $array[1];
		$r->count = $array[2];
		return $r;
	}
	
	public function getStorableName() {
		return "exam_assignments";
	}
	
	public function getStorableFields() {
		return(array('examid', 'assignmentid', 'count'));
	}
	
	public function getStorableValues() {
		return(array($this->examId, $this->assignmentId, $this->count));
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>
