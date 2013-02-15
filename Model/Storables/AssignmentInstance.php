<?php

class AssignmentInstance implements IStorable {
	public $parentAssignment;
	public $id;
	public $accountId;
	public $examId;
	public $term;
	public $date;
	
	private $correctResult;
	private $givenResult;
	
	# new assignment is generated based on given template
	public function __construct ($assignment, $user, $exam) {
		$this->parentAssignment = $assignment;
		
		# generate the assignment!
		$this->generate($assignmentId);
		$this->assignmentId = $assignmentId;
	}
	
	public function setSolution($solution) {
		$this->givenResult = $solution;
	}
	
	public function isSolved() {
		return (isset($this->givenResult));
	}
	
	public function isCorrect() {
		if ($this->isSolved())
			return ($this->correctResult == $this->givenResult);
		else
			return false;
	}
	
	private function generate($assignmentId) {
		$mysql = ResourceManager::$mysql;
		$konstanten = $mysql->getKonstanten($assignmentId);
		$parameter = $mysql->getParameter($assignmentId);
		$parameter = $parameter[0];
			
		switch ($parameter["typ"]) {
			case "ausrechnen":
				$rechnung = new Term($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten);
				break;
			/*case "runden":
				$rechnung = new Runden($parameter["von"], $parameter["bis"], false);
				break;
		
			case "schaetzen":
				$rechnung = new Schaetzwert($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten, $parameter["abweichung"]);
				break;
		
			case "vergleichen":
				$rechnung = new Vergleich($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten);
				break;*/
		}
		
		$this->description = $rechnung->getA();
		$this->phpErgebnis = $rechnung->getE();
		$this->type = $parameter["typ"];
		$this->term = $rechnung;
	}
	
	## IStorable methods
	##
	
	public function __construct ($array) {
		
	}
	
	public function getStorableName() {
		return "historyitem";
	}
	
	public function getStorableFields() {
		return array('id', 'accountid', 'examid', 'assignmentid', 'term', 'correctresult', 'givenresult', 'date');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->accountId, $this->examId, $this->assignmentId, $this->term, $this->correctResult, $this->givenResult, $this->date);
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>