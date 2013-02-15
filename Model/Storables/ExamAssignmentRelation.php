<?php 

class ExamAssignmentRelation implements IStorable {
	
	private $examId;
	private $assignmentId;
	private $frequency;
	
	public function __construct($examId, $assignmentId, $frequency) {
		$this->examId = $examId;
		$this->assignmentID = $assignmentID;
		$this->frequency = $frequency;
	}
	
	
	## IStorable methods
	##
	
	public function getStorableName() {
		return "exam_assignment";
	}
	
	public function getStorableFieldNames() {
		return(array('examid', 'assignmentid', 'frequency'));
	}
	
	public function getStorableValues() {
		return(array($this->examId, $this->assignmentId, $this->frequency));
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>
