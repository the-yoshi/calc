<?php

class Assignment extends Storable {
	public $id;
	public $description;
	public $type;
	public $termScheme;
	public $examId;
	public $count;
	
	# complex fields
	private $variables;
	private $settings;
	
	
	public function addVariable($v) {
		$this->variables[] = $v;
	}
	
	public function getVariables() {
		return $this->variables;
	}
	
	public function setVariables($variables) {
		$this->variables = $variables;
	}
	
	public function getSettings() {
		return $this->settings;
	}
	
	public function addSetting($setting) {
		$this->settings[$setting->name] = $setting->value;
	}
	
	public function delete() {
		foreach (StorageManager::getByCondition("AssignmentInstance", "assignmentid = ".$this->id) as $historyItem) {
			if (!StorageManager::delete($historyItem)) {
				return false;
			}
		}
		foreach ($this->getVariables() as $variable) {
			StorageManager::delete($variable);
		}
		foreach ($this->getSettings() as $setting) {
			StorageManager::delete($setting);
		}
		return StorageManager::delete($this);
	}
	
	## IStorable methods
	##
	
	public function __construct() {
		$this->variables = array();
		$this->settings = array();
	}
	
	public static function fromArray ($array) {
		$r = new Assignment();	
		$r->id = $array[0];
		$r->description = $array[1];
		$r->type = $array[2];
		$r->termScheme = $array[3];
		$r->examId = $array[4];
		$r->count = $array[5];
		
		# fetch variables
		$r->setVariables(StorageManager::getByCondition("Variable", "assignmentid = ".$r->id));
		# fetch settings
		$r->settings = Setting::toAssociativeArray(StorageManager::getByCondition("Setting", "examid = ".$r->examId." AND assignmentid = ".$r->id));
		
		return $r;
	}
	
	public function getStorableName() {
		return "assignment";
	}
	
	public function getStorableFields() {
		return array('id', 'description', 'type', 'termscheme', 'examid', 'count');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->description, $this->type, $this->termScheme, $this->examId, $this->count);
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>