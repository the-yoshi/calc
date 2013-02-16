<?php

class ExamInstance {
	# Exam object this instance is based on
	public $exam;
	# array containing all assignments belonging to this istance
	private $assignments;
	# the current assignment that has to be solved
	private $currentAssignment;
	
	public function __construct ($exam, $assignmentInstances) {
		$this->exam = $exam;
		$this->assignments = $assignmentInstances;
		$this->currentAssignment = 0;
	}
	
	public function storeCurrentSolution($solution) {
		$currentUser = ResourceManager::$user;
		$a = $this->assignments[$this->currentAssignment];
		$a->setSolution($solution);
		$this->currentAssignment++;
		# when exam is completed, push all assignmentinstances to database
		if ($this->currentAssignment == count($this->assignments))
		{
			$time = date("Y-m-d H:i:s"); # all shall have the same timestamp
			foreach ($this->assignments as $a) {
				$a->date = $time;
				$a->examId = $this->exam->id;
				$a->accountId = $currentUser->id;
				# push to database
				$a->store();
			}
		}
	}
	
	public function getCurrentAssignmentId() {
		return $this->currentAssignment;
	}
	
	public function getCurrentAssignment() {
		return $this->assignments[$this->currentAssignment];
	}
	
	public function getAssignmentCount() {
		return count($this->assignments);
	}
	
	public function isFinished() {
		return ($this->getAssignmentCount()-$this->getCurrentAssignmentId() == 0);
	}
}


?>
