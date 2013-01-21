<?php
#Später um Erweitern um Errormanagement
class MySQL {
	
	private $db = "";
	private $lastqueryid = "";
	
	public function __construct() {
		$this->db = new mysqli("localhost", "crud", "rw", "kopfrechnen");
	}
	
	#Beliebigen Query Auführen und das Ergebnis als Array erhalten
	#Noch auf Selects beschränken!
	private function getQuery($sql) {
		$db = $this->db;
		$erg = $db->query($sql);
		
		$array = array();
		while ($e = $erg->fetch_array()) {
			$array[] = $e;
		}
		#$array = $erg->fetch_array();
		return $array;
	}
	
	private function setQuery($sql) {
		$db = $this->db;
		$bool = $db->query($sql);
		$this->lastqueryid = $db->insert_id;
		return $bool;
	}
	
	#Escapefuntion zum Erhöhen der Sicherheit
	private function escape($val) {
		$db = $this->db;
		$val = $db->real_escape_string($val);
		return $val;
	}
	
	#Welcher Query der Verwaltung soll ausgeführt werden?
	public function decision($action, array $data) {
		#Array bereinigen
		foreach ($data as $key=>$d) {
			$data[$d] = $this->escape($d);
		}
		#Aufrufen der passenden Queryfunktion
		switch ($action) {
			case "neuAccount":
				$boolean = $this->setAccount($data);
				break;
			case "neuKlasse":
				$boolean = $this->setKlassen($data);
				break;
			case "neuSchema":
				$boolean = $this->setSchema($data);
				break;
			case "neuAufgabentyp":
				$boolean = $this->setAufgabe($data);
				break;
			default:
				$boolean = false;
		}
		return $boolean;
	}
	
	#Aus einem Array mit Id+Bezeichnung ein Formfähiges Select zurückliefern
	public function makeList($name, array $quelle, $autosubmit = false, $ort = "", $id = 0) {
		$html = '<select name="'.$name.'"';
		if ($autosubmit) {
			$html .= 'id="auswahl" onchange=\'window.location = "'.$ort.'&id="+document.getElementById("auswahl").value\' ';
		} 
		$html .= '>';
		$html .= '<option> Bitte wählen... </option>';	
		foreach ($quelle as $q) {
			if (isset($id) && $id == $q[0]) {
				$html .= '<option value="'.$q[0].'" selected>'.$q[1].'</option>';
			} else {
				$html .= '<option value="'.$q[0].'">'.$q[1].'</option>';
			}
		}
		$html .= '</select>';
		
		return $html;
	}
	
	public function zaehleVariablen($term) {
		$sql = "Select termvorlage from term where id=$term limit 1";
		$erg = $this->getQuery($sql);
		$erg = str_replace("-", "+", $erg[0][0]);
		$anz = str_word_count($erg, 1);
		return $anz;
	}
	
	#Vordefinierte Querys zum Eintragen in die Datenbank
	#Nur über decision() ansprechbar!
	private function setKlassen(array $data) {
		$sql = 'insert into klasse (bezeichnung) values ("'.$data["bezeichnung"].'")';
		return $this->setQuery($sql);
	}
	
	private function setAccount(array $data) {
		$sql = 'insert into account (username, password, email, rolle, vorname, nachname) values("'.$data["username"].'","'.md5($data["password"]).'","'.$data["email"].'","'.$data["rolle"].'","'.$data["vorname"].'","'.$data["nachname"].'")';
		return $this->setQuery($sql);
	}
	
	private function setSchema(array $data) {
		$sql = 'insert into term (termvorlage, level) values ("'.$data["termvorlage"].'",'.$data["level"].')';
		return $this->setQuery($sql);
	}
	
	private function setAufgabe(array $data) {
		if ($data["abweichung"] != "") {
			$sql = 'insert into aufgabe (bezeichnung, typ, term, abweichung, von, bis) values ("'.$data["bezeichnung"].'","'.$data["typ"].'",'.$data["term"].','.$data["abweichung"].','.$data["von"].','.$data["bis"].')';
		} else {
			$sql = 'insert into aufgabe (bezeichnung, typ, term, von, bis) values ("'.$data["bezeichnung"].'","'.$data["typ"].'",'.$data["term"].','.$data["von"].','.$data["bis"].')';
		}
		return $this->setQuery($sql);
	}
	
	public function setKonstante($variable, $von, $bis = NULL) {
		if ($bis == NULL) {$bis = $von;}
		$sql = 'insert into konstanten (konstante, von, bis) values ("'.$variable.'",'.$von.','.$bis.')';
		return $this->setQuery($sql);
	}
	
	public function setKonstAufg($id_aufgabe, $id_konstante) {
		$sql = 'insert into aufgabekonstante (aufgabe, konstante) values ('.$id_aufgabe.','.$id_konstante.')';
		return $this->setQuery($sql);
	} 
	
	#Vordefinierte Ausgabe-Querys
	public function getId() {
		return $this->lastqueryid;
	}
	
	public function getKlassen() {
		$sql = "Select id, bezeichnung from klasse";
		$erg = $this->getQuery($sql);
		return $erg;
	}
	
	public function getAccount() {
	
	}
	
	public function getSchema() {
		$sql = "Select id, termvorlage from term order by level";
		$erg = $this->getQuery($sql);
		return $erg;
	}
	
	
}