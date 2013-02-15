<?php

class Exam implements IStorable {
	public $id;
	public $name;
	public $lowerBoundZ;
	public $upperBoundZ;
	public $duration;
	public $durationType;
	public $creator;
	
	# array containing all ExamAssignmentRelation objects belonging to this exam
	private $assignments;
	
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
}


?>
