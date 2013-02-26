<?php

class Exam extends Storable {
	public $id;
	public $name;
	public $lowerBoundZ;
	public $upperBoundZ;
	public $duration;
	public $durationType;
	public $creator;
	
	# array containing all Assignment objects belonging to this exam
	private $assignments;
	
	
	public function generateInstance() {
		$assignmentInstances = array();
		
		foreach ($this->assignments as $a) {
			for ($i=0;$i<$a->count;$i++) {
				$assignmentInstances[] = AssignmentInstance::fromAssignment($a);
			}
		}
		$ret = new ExamInstance($this, $assignmentInstances);
		return $ret;
	}
	
	public function addAssignment($assignment) {
		$this->assignments[] = $assignment;
	}
	
	public function getAssignments() {
		return $this->assignments;
	}
	
	## IStorable methods
	##
	
	public function __construct () {
		$this->assignments = array();
	}
	
	public static function fromArray ($array) {
		$r = new Exam();
		$r->id = $array[0];
		$r->name = $array[1];
		$r->duration = $array[2];
		$r->durationType = $array[3];
		$r->creator = $array[4];
		$r->lowerBoundZ = $array[5];
		$r->upperBoundZ = $array[6];
		
		$r->assignments = StorageManager::getByCondition("Assignment", "examid = ".$r->id);
		return $r;
	}
	
	public function getStorableName() {
		return "exam";
	}
	
	public function getStorableFields() {
		return array('id', 'name', 'duration', 'durationtype', 'creator', 'lowerboundz', 'upperboundz');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->name, $this->duration, $this->durationType, $this->creator, $this->lowerBoundZ, $this->upperBoundZ);
	}
	
	public function getStorableRelations() {
		return $assignments;
	}
}


?>
