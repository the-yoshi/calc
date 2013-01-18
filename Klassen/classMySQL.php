<?php
#Später um Erweitern um Errormanagement
class MySQL {
	
	private $db = "";
	
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
		$erfolg = $db->query($sql);
		return $erfolg;
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
	public function makeList($name, array $quelle, $autosubmit = false, $selected = 0) {
		$html = '<select name="'.$name.'"';
		if ($autosubmit) {
			$html .= " onchange='this.form.submit()' ";
		} 
		$html .= '>';
		$html .= '<option> Bitte wählen... </option>';	
		foreach ($quelle as $q) {
			if (isset($selected) && $selected == $q[0]) {
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
		$erg = $erg[0][0];
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
			$sql = 'insert into aufgabe (bezeichnung, typ, term, abweichung) values ("'.$data["bezeichnung"].'","'.$data["typ"].'",'.$data["term"].','.$data["abweichung"].')';
		} else {
			$sql = 'insert into aufgabe (bezeichnung, typ, term) values ("'.$data["bezeichnung"].'","'.$data["typ"].'",'.$data["term"].')';
		}
		return $this->setQuery($sql);
	}
	
	#Vordefinierte Ausgabe-Querys	
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