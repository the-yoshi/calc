<?php
class Mathe {
	#Zahlenraum
	protected $von = 0;
	protected $bis = 0;
	#Nachkommastellen verwenden
	protected $komma = false;
	#Feste Variablen
	protected $fixvar = array();

	#Rückgabewerte
	protected $aufgabenstellung = "";
	protected $term = "";
	protected $ergebnis = "";

	public function __construct($von, $bis, $komma) {
		$this -> von = $von;
		$this -> bis = $bis;
		$this -> komma = $komma;
	}

	public function setE($ergebnis) {
		$this -> ergebnis = $ergebnis;
	}

	public function getE() {
		return $this -> ergebnis;
	}

	public function setT($term) {
		$this -> term = $term;
	}

	public function getT() {
		return $this -> term;
	}

	public function setA($aufgabe) {
		$this -> aufgabenstellung = $aufgabe;
	}

	public function getVon() {
		return $this -> von;
	}

	public function getBis() {
		return $this -> bis;
	}

	public function getKomma() {
		return $this -> komma;
	}
	
	public function setKomma($komma) {
		$this->komma = $komma;
	}
	
	public function getA() {
		return $this->aufgabenstellung;
	}
	
	

	public function zufall($zero = false) {
		$min = $this -> getVon();
		$max = $this -> getBis();
		$komma = $this -> getKomma();
	
		if ($komma) {$x = 2;} else {$x = 0;}
	
		$temp = mt_rand($min, $max-1).".".mt_rand(1, 99);
		$zahl = round($temp*1, $x);
	
		if (!$zero or ($zero && $zahl != 0)) {
			return $zahl;
		} else {
			return 2;
		}
	}
}
