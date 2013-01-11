<?php
#Sp�ter um Erweitern um Errormanagement
class MySQL {
	
	private $db = "";
	
	public function __construct() {
		$this->db = new mysqli("localhost", "crud", "rw", "kopfrechnen");
	}
	
	#Beliebigen Query Auf�hren und das Ergebnis als Array erhalten
	#Noch auf Selects beschr�nken!
	private function getQuery($sql) {
		$db = $this->db;
		$erg = $db->query($sql);
		
		$array = array();
		while ($e = $erg->fetch_array()) {
			$array[] = $e;
		}
		return $array;
	}
	
	private function setQuery($sql) {
		$db = $this->db;
		$erfolg = $db->query($sql);
		return $erfolg;
	}
	
	#Escapefuntion zum Erh�hen der Sicherheit
	private function escape($val) {
		$db = $this->db;
		$val = $db->real_escape_string($val);
		return $val;
	}
	
	#Welcher Query der Verwaltung soll ausgef�hrt werden?
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
			default:
				$boolean = false;
		}
		return $boolean;
	}
	
	#Aus einem Array mit Id+Bezeichnung ein Formf�higes Select zur�ckliefern
	public function makeList($name, array $quelle) {
		$html = '<select name="'.$name.'">';	
		foreach ($q as $quelle) {
			$html .= '<option value="'.$q[0].'">'.$q[1].'</option>';	
		}
		$html .= '</select>';
		
		return $html;
	}
	
	#Vordefinierte Querys zum Eintragen in die Datenbank
	#Nur �ber decision() ansprechbar!
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
	
	#Vordefinierte Ausgabe-Querys	
	public function getKlassen() {
		$sql = "Select id, bezeichnung from klasse";
		$erg = $this->getQuery($sql);
		return $erg;
	}
	
	public function getAccount() {
	
	}
	
	
	
	
}