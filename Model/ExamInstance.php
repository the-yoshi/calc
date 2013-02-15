<?php

class ExamInstance {
	# primary key in 'uebungen' table
	public $examId;
	# array containing all assignments belonging to this istance
	private $assignments;
	# the current assignment that has to be solved
	private $currentAssignment;
	
	public function __construct ($examId) {
		$mysql = ResourceManager::$mysql;
		
		# fetch exam information
		$sql = "SELECT id,templateid,assignmentcount FROM exam_assignments
				WHERE examid = $examId
				ORDER BY id ASC";
		$arr = $mysql->getQuery($sql);
		
		# generate the assignments
		$this->assignments = array();
		foreach ($arr as $exam) {
			$c = $exam["assignmentcount"];
			while ($c > 0) {
				$this->assignments[] = new Assignment($exam["templateid"]);
				$c--;
			}
			
		}
		$this->currentAssignment = 0;
		$this->examId = $examId;
		$this->isTemplate = FALSE;
	}
	
	public function storeCurrentSolution($solution) {
		$a = $this->assignments[$this->currentAssignment];
		$a->setSolution($solution);
		$this->currentAssignment++;
		# when exam is completed, push to database
		if ($this->currentAssignment == count($this->assignments))
		{
			$time = date("Y-m-d H:i:s"); # all shall have the same timestamp
			foreach ($this->assignments as $a) {
				$a->store($this->examId, $time);
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
	
	public function isTemplate() {
		return $isTemplate;
	}
}


?>
