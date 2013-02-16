<?php 

class AccountClassRelation extends Storable {
	private $accountId;
	private $classId;
	
	public function __construct($examId, $assignmentId, $frequency) {
		$this->accountId = $accoundId;
		$this->classId = $classId;
	}
	
	
	## IStorable methods
	##
	
	public function getStorableName() {
		return "account_class";
	}
	
	public function getStorableFields() {
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
