<?php

class Schaetzwert extends Term {
	protected $abweichung = 0;

	public function __construct($von, $bis, $komma, $operatoren, $schemata, $konstanten, $abweichung) {
		parent::__construct($von, $bis, $komma, $operatoren, $schemata, $konstanten);
		$this -> abweichung = $abweichung;
		
		$this->setA("Sch�tze: ");
	}
	
	public function getAbweichung() {
		return $this->abweichung;
	}

	public function validiere() {
		$erg = $this -> getE();
		$abweichung = $this -> abweichung;
		$obergrenze = ($erg * ((100+$abweichung)/100));
		$untergrenze = ($erg * ((100-$abweichung)/100));

		$this -> setE("$obergrenze;$erg;$untergrenze");
	}

}
