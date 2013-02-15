<?php 

class AccountClassRelation implements IStorable {
	private $accountId;
	private $classId;
	
	public function __construct($examId, $assignmentId, $frequency) {
		$this->accountId = $accoundId;
		$this->classId = $classId;
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
