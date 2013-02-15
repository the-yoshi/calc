<?php

//TODO: Aufgabentypen sollten einen eigenen Unterordner bekommen

class Runden extends Mathe {

	public function __construct($von, $bis, $komma) {
		parent::__construct($von, $bis, $komma);
		$this->runde();
	}
	
	public function runde() {
		if ($this->getKomma()) {
			$zahl = abs($this -> zufall());
		} else {
			$this->setKomma(true);
			$zahl = abs($this -> zufall());
			$this->setKomma(false);
		}

		if (preg_match("/\./", $zahl)) {
			$zahl = round($zahl . mt_rand(5, 9), 2);
			$anz = strlen($zahl)-3;
			$ziel = mt_rand(-1, $anz);
		} else {
			$anz = strlen($zahl);
			$ziel = mt_rand(0, $anz-1);
		}

		$ergebnis = round($zahl * pow(10, $ziel * (-1)),0) * pow(10, $ziel);

		$zahl = preg_replace("/\./", ",", $zahl);
		switch ($ziel) {
			case 0:
				$aufgabe = "Runde auf eine ganze Zahl: ";
				break;
			case $ziel < 0:
				$aufgabe = "Runde auf " . abs($ziel) . " Stellen nach dem Komma: ";
				break;
			case $ziel > 0:
				$aufgabe = "Runde auf " . abs($ziel + 1) . " Stellen vor dem Komma: ";
				break;
		}

		$this -> setA($aufgabe);
		$this -> setE($ergebnis);
		$this -> setT($zahl);
	}

}
