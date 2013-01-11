<?php
require_once 'classMathe.php';
class Term extends Mathe {
	
	protected $term = "";
	protected $ergebnis = "";
	protected $operatoren = array();
	protected $schema = array();
	protected $konstanten = array();
	protected $rechenterm = "";
	
	public function __construct($von, $bis, $komma, array $operatoren, array $schemata, array $konstanten) {
		parent::__construct($von, $bis, $komma);
		$this -> operatoren = $operatoren;
		$this -> schema = $schemata;
		$this -> konstanten = $konstanten;
		
		$this -> generiere();
	}
	
	public function generiere() {
		$term = "";
		$operatoren = $this -> operatoren;
		$schema = $this -> schema;
		$konstanten = $this -> konstanten;
	
		# Trennt seperate Termteile voneinander
		$split = preg_split("/ /", $schema[mt_rand(0, count($schema)-1)]);
	
		$term = "";
		$part = array();
	
		foreach ($split as $s) {
	
			#Ersetzt einzeln stehende Operatoren zufällig
			if (in_array($s, $operatoren)) {
				$s = $operatoren[mt_rand(0, count($operatoren)-1)];
			}
			
			#Variablen durch vorgegebene Konstanten ersetzen
			foreach ($konstanten as $k) {
				if (isset($k[2])) {
					$s = preg_replace("/['.$k[0].']/", mt_rand($k[1],$k[2]),$s, 1);
				} else {
					$s = preg_replace("/['.$k[0].']/", $k[1],$s, 1);
				}
			}

			#Ersetzt restliche Variablen durch Zahlen
			while (preg_match("/[a-z]/", $s)) {
				$s = preg_replace("/[a-z]/", $z = $this -> zufall(),$s, 1);
			}
	
			$part[] = $s;
				
			#Ersetzt ^ durch die entsprechende PHP-Funktion
			if (preg_match("/\^/", $s)) {
				$s = "pow(" . preg_replace("/\^/", ",", $s) . ")";
			}
			$term .= $s;
		}
	
		#Gibt den Term und das Ergebnis aus
		$term = preg_replace("/\-\-/", "+", $term);
		$this -> rechenterm = $term;	
		$phpterm = eval("try{\$e = round($term, 2);} catch(Exception \$e) {echo \"Fehler \$e \";}");				
		$this -> setE($e);	
		$this -> formatTerm($part);
		$this -> setA("Berechne: ");
	}
	
	private function formatTerm($part) {
		$htmlterm = "";
	
		foreach ($part as $p) {
			$p = preg_replace("/\//", "&divide;", $p);
			
			if (preg_match("/\^/", $p)) {
				$p = preg_replace("/\^/", "<sup>", $p);
				$htmlterm .= $p . "</sup>";
			} elseif (preg_match("/\*/", $p)) {
				$p = preg_replace("/\*/", "&times;", $p);
				$htmlterm .= $p;
			} else {
				$htmlterm .= $p;
			}
		}
		$htmlterm = preg_replace("/\-\-/", "+", $htmlterm);
		
		$this -> setT($htmlterm);
	}
	
	public function getRT() {
		return $this -> rechenterm;
	}
}























?>