<?php

class Assignment {
	public $assignmentId;
	public $description;
	public $term;
	public $type;
	
	private $phpResult;
	private $givenResult;
	private $isTemplate;
	
	# new assignment is generated based on given template
	public function __construct ($assignmentId) {
		$mysql = ResourceManager::$mysql;
		
		# fetch necessary data
		$sql = "SELECT bezeichnung,typ,term,von,bis FROM aufgabe
				WHERE id = $assignmentId";
		$r = $mysql->getQuery($sql);
		$r = $r[0]; # it's only one row
		
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
		return ($this->phpResult == $this->givenResult);
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
	
	public function store($examId, $time) {
		$mysql = ResourceManager::$mysql;
		
		$aufgabe = $this->assignmentId;
		$account = ResourceManager::$user["id"];
		$desc = $this->description;
		$rechnung = $this->term->getRT();
		$phpErg = $this->phpErgebnis;
		$einErg = $this->givenResult;
		$sql = "INSERT INTO historie (id, uebung, aufgabe, account, abgabe, dauer, beschreibung, rechnung, phpergebnis, eingabeergebnis, richtig)
							  VALUES (NULL, $examId, $aufgabe, $account, '$time', NULL, '$desc', '$rechnung', '$phpErg', '$einErg', NULL)";
		return $mysql->setQuery($sql);
	}
}

?>