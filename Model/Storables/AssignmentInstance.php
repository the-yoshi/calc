<?php

class AssignmentInstance extends Storable {
	public $parentAssignment;
	public $id;
	public $accountId;
	public $examId;
	public $term;
	public $date;
	
	public $correctResult;
	public $givenResult;
	
	# new assignment is generated based on given template

	
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
	
	public static function fromAssignment($template) {
		$r = new AssignmentInstance();
		$r->parentAssignment = $template;
		$variables = $template->getVariables();
		$von = array();
		$bis = array();
		foreach ($variables as $v) {
			$von[] = $v->lowerBound;
			$bis[] = $v->upperBound;
		}
			
		switch ($template->type) {
			case "calc":
				$rechnung = new Term($von, $bis, false, array(), $template->termScheme, $variables);
				break;
			case "round":
				$rechnung = new Runden($von, $bis, false);
				break;
		
			case "estimate":
				$rechnung = new Schaetzwert($von, $bis, false, array(), $template->termScheme, $variables, 10);
				break;
		
			case "evaluate":
				$rechnung = new Vergleich($von, $bis, false, array(), $template->termScheme, $variables);
				break;
		}
		
		$r->description = $rechnung->getA();
		$r->correctResult = $rechnung->getE();
		$r->term = $rechnung->getRT();
		return $r;
	}
	
	## IStorable methods
	##
	public function __construct () {
		
	}
	
	public static function fromArray ($array) {
		$r = new AssignmentInstance();
		$r->id = $array[0];
		$r->accountId = $array[1];
		$r->examId = $array[2];
		$r->parentAssignment = new Assignment();
		$r->parentAssignment->id = $array[3];
		$r->term = $array[4];
		$r->correctResult = $array[5];
		$r->givenResult = $array[6];
		$r->date = $array[7];
		return $r;
	}
	
	public function getStorableName() {
		return "historyitem";
	}
	
	public function getStorableFields() {
		return array('id', 'accountid', 'examid', 'assignmentid', 'term', 'correctresult', 'givenresult', 'date');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->accountId, $this->examId, $this->parentAssignment->id, $this->term, $this->correctResult, $this->givenResult, $this->date);
	}
	
	public function getStorableRelations() {
		return array();
	}
}

?>