<?php

# TODO: the relation between correct and incorrect answers should be 50:50

class Vergleich extends Term {
	protected $voperatoren = array("==","<=",">=","<",">");
	
	public function __construct($von, $bis, $komma, $operatoren, $compOperators, $schemata, $konstanten) {
		parent::__construct($von, $bis, $komma, $operatoren, $schemata, $konstanten);
		$this->voperatoren = $compOperators;
		$this -> vergleiche();
	}

	public function vergleiche() {
		$e = round($this -> getE() * (mt_rand(70, 130)/100),0);
		$term = $this -> getRT();
		$anzeigeterm = $this -> getT();
		$voperatoren = $this -> voperatoren;

		$vo = $voperatoren[mt_rand(0, count($voperatoren)-1)];

		$vterm = $anzeigeterm.$vo.$e;
		
		eval("if($e $vo $term) {\$bool = true;} else {\$bool = false;}");
	
		if ($bool) {
			$this -> setE("Richtig");
		} else {
			$this -> setE("Falsch");
		}
		
		$vterm = preg_replace("/\=\=/", "=", $vterm);
		$vterm = preg_replace("/\<\=/", "&le;", $vterm);
		$vterm = preg_replace("/\>\=/", "&ge;", $vterm);

		$this -> setT($vterm);
		$this->setA("�berpr�fe: ");
	}
}
