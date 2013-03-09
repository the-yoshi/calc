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
	# associative array containing all [name] -> [value] pairs (representing Settings) for this Exam
	private $settings;
	
	
	public function generateInstance() {
		$assignmentInstances = array();
		
		foreach ($this->assignments as $a) {
			for ($i=0;$i<$a->count;$i++) {
				$assignmentInstances[] = AssignmentInstance::fromAssignment($a);
			}
		}
		$ret = new ExamInstance($this, $assignmentInstances);
		
		if (isset($this->settings["randomOrder"]) && $this->settings["randomOrder"] == "true")
			$ret->randomize();
		
		return $ret;
	}
	
	public function addAssignment($assignment) {
		$this->assignments[] = $assignment;
	}
	
	public function getAssignments() {
		return $this->assignments;
	}
	
	public function getSettings() {
		return $this->settings;
	}
	
	public function addSetting($setting) {
		$this->settings[$setting->name] = $setting->value;
	}
	
	public function delete() {
		foreach ($this->getAssignments() as $assignment) {
			$a = StorageManager::getById("Assignment", $assignment->id);
			$a->delete();
		}
		$settings = $this->getSettings();
		foreach (array_keys($settings) as $field) {
			$s = StorageManager::getByCondition("Setting", "examid = ".$this->id." AND assignmentid = -1 AND name = '$field'");
			if (count($s) > 0)
				StorageManager::delete($s[0]);
		}
		
		return StorageManager::delete($this);
	}
	
	## Storable methods
	##
	
	public function __construct () {
		$this->assignments = array();
		$this->settings = array();
	}
	
	public static function fromArray ($array) {
		$r = new Exam();
		# simple fields are taken from the array
		$r->id = $array[0];
		$r->name = $array[1];
		$r->duration = $array[2];
		$r->durationType = $array[3];
		$r->creator = $array[4];
		$r->lowerBoundZ = $array[5];
		$r->upperBoundZ = $array[6];
		# fetch complex data
		$r->assignments = StorageManager::getByCondition("Assignment", "examid = ".$r->id);
		$r->settings = Setting::toAssociativeArray(StorageManager::getByCondition("Setting", "examid = ".$r->id." AND assignmentid = -1"));
		
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
		return array();
	}
}


?>
