<?php
#Umfangreichste Klasse
/* Stellt alle Methoden zur Kommunitkation mit der Datenbank bereit
 *  Außerdem sind methoden vorhanden, um Formularelemtente simpel mit
 * Daten aus der DB zu erstellen
 * Alle Eintragungen in die Datenbank werden über die Methode setQuery abgewickelt
 * Alle Abfragen werden mit getQuery erledigt. Diese Methode liefert immer ein Array zurück.
 * d.h. bei Abfrage von einzelwerten muss das element array[0] benutzt werden
 * "decision" ist eine besondere methode und entscheidet anhand der operation in der 
 * Verwaltungsseite, welche Elemente eingetragen werden müssen
 * Alle Querys werden vordefiniert! Von außen soll nur über die jeweilige Methode
 * eine bestimmte DB-Operation möglich sein!
 */
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
	
	#HTML Formularelemente
	public function makeList($name, array $quelle, $firstfield = false, $autosubmit = false, $ort = "", $id = 0, $disable = false) {	
		$html = '<select name="'.$name.'"';
		
		if ($autosubmit) {
			$html .= ' id="auswahl" onchange=\'window.location = "'.$ort.'&id="+document.getElementById("auswahl").value\' ';
		}		
		
		if ($disable) {$html .= " diabled ";}
		
		$html .= '>';
		
		if ($firstfield) {$html .= '<option> Bitte wählen... </option>';}
			
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
	
	public function makePOSTList($name, array $quelle, $firstfield = false, $autosubmit = false, $ort = "", $id = 0, $disable = false) {
		$html = '<select name="'.$name.'"';
	
		if ($autosubmit) {
			$html .= ' name="auswahl" onchange=\'this.form.submit();\' ';
		}
	
		if ($disable) {$html .= " disabled ";}
	
		$html .= '>';
	
		if ($firstfield) {$html .= '<option> Bitte wählen... </option>';}
			
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
	
	public function makeBox($name, array $liste, array $markiert = array()) {
		if (count($liste) <= 5) {
			$size = count($liste);
		} else {
			$size = 10;
		}
		
		$markiert_ids = array();
		if (count($markiert)>0) {
			foreach ($markiert as $m) {
				$markiert_ids[] = $m[0];
			}
		}
		
		$html = '<select name="'.$name.'" size="'.$size.'" multiple>';
		foreach ($liste as $l) {
			$html .= '<option value="'.$l[0].'" '; 
				if (in_array($l[0], $markiert_ids)) {$html .= " selected";}
			$html .= '>'.$l[1];
				if (isset($l[2])) {$html .= ', '.$l[2];}
			$html .= "</option>";
		}
		$html .= "</select>";
		return $html;
	}
	
	public function makeCountList($id) {
		$sql = "Select account from historie, account where uebung = $id and account.id = historie.account and rolle = 'schueler' group by account";
		$array = $this->getQuery($sql);
		#$array = $array[0];
		
		$html = "<ul>";
		foreach ($array as $a) {
			$sql = "Select nachname, vorname, count(aufgabe) as 'anzahl' from historie, account where uebung = $id and account = $a[0] and historie.account = account.id";
			$item = $this->getQuery($sql);
			$item = $item[0];
			$html .= '<li>' . $item["nachname"] . ', ' . $item["vorname"] . ' (' . $item["anzahl"] . ') </li>';
		}
		$html .= "</ul>";
		return $html;
	}	
	
	public function makeSchuelerTaskList($id, $ort) {
		$sql = "Select uebung.id, bezeichnung, anzahl, modus from uebung, historie where uebung.id = historie.uebung and aktiv > 0 and historie.account = $id group by historie.uebung";
		$array = $this->getQuery($sql);
		
		$html = "";
		foreach ($array as $a) {
			$html .= '<form action="'.$ort.'" method="POST" name="uebung_'.$a["id"].'">';
			$html .= '<input type="hidden" name="uebung" value="'.$a["id"].'">';
			$html .= '<input type="hidden" name="anzahl" value="'.$a["anzahl"].'">';
			$html .= '<input type="hidden" name="modus" value="'.$a["modus"].'">';
			$verbleibend = $this->getCountAufgaben($id, $a["id"]);
			if ($a["anzahl"] == $verbleibend[0][0]) {$wort = "beginnen";} else {$wort = "fortsetzen";}
			if ($verbleibend[0][0] == 0) {$deaktiviert = "disabled";} else {$deaktiviert = "";}
			$html .= '<input type="submit" value="'.$a["bezeichnung"].' '.$wort.' ('.$verbleibend[0][0].'/'.$a["anzahl"].' Aufgaben verbleibend)" '.$deaktiviert.'>';
			$html .= '<br /></form>';
		}
		return $html;
	}
	
	public function makeTaskList($lehrerid, $ort) {
		$data = $this->getUebungen($lehrerid);
		$html = "<table><tr><th>Name</th><th>Modus</th><th>Anzahl</th><th>Optionen</th></tr>";
		
		foreach ($data as $d) {
			$html .= '<tr><form name="uebung_'.$d["id"].'" action="'.$ort.'" method="get">';
			$html .= '<input type="hidden" name="site" value="zuteilung" />';
			$html .= '<input type="hidden" name="id" value="'.$d["id"].'" />';
			$html .= '<td>'.$d["bezeichnung"].'</td>';
			$html .= '<td>';
				if ($d["modus"] == "klausur") {$html .= "Klausur";} else {$html .= "Übung";}
			$html .= '</td>';
			$html .= '<td>'.$d["anzahl"].'</td>';
			
			$html .= "<td>";
			if ($d["aktiv"] == 0) {
				$html .= '<input type="submit" name="aktion" value="Aktivieren">';
			} else {
				$html .= '<input type="submit" name="aktion" value="Deaktivieren">';
			}
			
			$html .= '<input type="submit" name="aktion" value="Bearbeiten">';
			$html .= '<input type="submit" name="aktion" value="Loeschen">';
			$html .= "</td></form></tr>";
		}
		
		$html .= "</table>";
		return $html;
	}
	
	public function zufallsAufgabe($id) {

		$konstanten = $this->getKonstanten($id);
		$parameter = $this->getParameter($id);
		$parameter = $parameter[0];
			
		switch ($parameter["typ"]) {
			case "ausrechnen":
				$rechnung = new Term($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten);
				break;
			case "runden":
				$rechnung = new Runden($parameter["von"], $parameter["bis"], false);
				break;
		
			case "schaetzen":
				$rechnung = new Schaetzwert($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten, $parameter["abweichung"]);
				break;
		
			case "vergleichen":
				$rechnung = new Vergleich($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten);
				break;
		}
		
		return array("beschreibung" => $rechnung->getA(), "typ" => $parameter["typ"], "phpergebnis" => $rechnung->getE(), "rechnung" => $rechnung->getT());
	}
	
	#Anzahl der Variablen eines Terms
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
			$sql = 'insert into aufgabe (bezeichnung, typ, ersteller, term, abweichung, von, bis) values ("'.$data["bezeichnung"].'","'.$data["typ"].'",'.$data["ersteller"].','.$data["term"].','.$data["abweichung"].','.$data["von"].','.$data["bis"].')';
		} else {
			$sql = 'insert into aufgabe (bezeichnung, typ, ersteller, term, von, bis) values ("'.$data["bezeichnung"].'","'.$data["typ"].'",'.$data["ersteller"].','.$data["term"].','.$data["von"].','.$data["bis"].')';
			echo $sql;
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
	
	public function setSchuelerKlasse($schueler, $klasse) {
		$sql = 'insert into accountklasse (account, klasse) values ('.$schueler.','.$klasse.')';
		return $this->setQuery($sql);
	}
	
	public function setUebung($bezeichnung, $ersteller, $modus, $anzahl, $aktiv) {
		$sql = 'insert into uebung (bezeichnung, ersteller, modus, anzahl, aktiv) values ("'.$bezeichnung.'",'.$ersteller.',"'.$modus.'",'.$anzahl.','.$aktiv.')';
		return $this->setQuery($sql);
	}
	
	public function setErgebnis($id, $ergebnis) {
		$sql = "update historie set eingabeergebnis = '$ergebnis' where id = $id";
		return $this->setQuery($sql);
	}
	
	public function setVorgabe($id, $phpergebnis, $rechnung, $beschreibung) {
		$sql = "update historie set phpergebnis = '$phpergebnis', rechnung = '$rechnung', beschreibung = '$beschreibung' where id = $id";
		return $this->setQuery($sql);
	}
	
	#Vordefinierte Ausgabe-Querys
	public function getId() {
		return $this->lastqueryid;
	}
	
	public function getKlassen($lehrerid = 0) {
		if ($lehrerid == 0) {		
			$sql = "Select id, bezeichnung from klasse order by bezeichnung ASC";
		} else {
			$sql = "Select klasse.id, bezeichnung from klasse, accountklasse where accountklasse.klasse = klasse.id and accountklasse.account = $lehrerid order by bezeichnung ASC";
		}
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
	
	public function getSchueler(array $klassen = array()) {
		if (count($klassen)>0) {
			$sql = "Select account.id, vorname, nachname, username, bezeichnung from account, accountklasse, klasse where rolle='schueler' and account.id = accountklasse.account and accountklasse.klasse = klasse.id and (";
			foreach($klassen as $k) {
				$sql .= " accountklasse.klasse = $k or";
			}
			$sql = substr($sql, 0, strlen($sql)-3) . ") order by bezeichnung, nachname, vorname, username";
			
			
		} else {
			$sql = "Select id, vorname, nachname, username from account where rolle='schueler'";
		}
		$erg = $this->getQuery($sql);
		return $erg;
	}

	public function getAufgaben($lehrerid) {
		if ($lehrerid == 0) {
			$sql = "Select id, bezeichnung from aufgabe order by bezeichnung ASC";
		} else {
			$sql = "Select id, bezeichnung from aufgabe where ersteller = $lehrerid order by bezeichnung ASC";
		}
		$erg = $this->getQuery($sql);
		return $erg;
	}
	
	public function getUebungen($lehrerid) {
		$sql = "Select id, bezeichnung, anzahl, modus, aktiv from uebung where ersteller=$lehrerid";
		$erg = $this->getQuery($sql);
		return $erg;
	}
	
	public function getUebung($id) {
		$sql = "Select id, bezeichnung, anzahl, modus, aktiv from uebung where id=$id";
		$erg = $this->getQuery($sql);
		return $erg;
	}
	
	public function updateUebung($id, $bezeichnung, $anzahl, $modus) {
		$sql = "update uebung set bezeichnung = '$bezeichnung', anzahl = '$anzahl', modus = '$modus' where id=$id";
		return $this->setQuery($sql);
	}
	
	public function resetUebungen($id) {
		$sql = "delete from historie where uebung = $id and phpergebnis = NULL";
		return $this->setQuery($sql);
	}
	
	public function setHistorieKlausur($uebung, $aufgabe, $account, $rechnung, $phpergebnis, $beschreibung) {
		$sql = "insert into historie (uebung, aufgabe, account, rechnung, phpergebnis, beschreibung) values ($uebung, $aufgabe, $account, '$rechnung', '$phpergebnis', '$beschreibung')";
		return $this->setQuery($sql);
	}
	
	public function setHistorieVorgabe($uebung, $aufgabe, $account) {
		$sql = "insert into historie (uebung, aufgabe, account) values ($uebung, $aufgabe, $account)";
		return $this->setQuery($sql);
	}
	
	public function getParameter($id) {
		$sql = "Select termvorlage, typ, von, bis, komma, abweichung from aufgabe, term where aufgabe.term = term.id and aufgabe.id = $id";
		return $this->getQuery($sql);
	}
	
	public function getKonstanten($id) {
		$sql = "Select konstanten.konstante, von, bis from konstanten, aufgabekonstante where aufgabekonstante.konstante = konstanten.id and aufgabekonstante.aufgabe = $id";
		return $this->getQuery($sql);
	}
	
	public function getCountAufgaben($account, $uebung) {
		$sql = "Select count(id) from historie where uebung = $uebung and account = $account and eingabeergebnis is NULL";
		return $this->getQuery($sql);
	}
	
	public function getAufgabe($account, $uebung) {
		$sql = "Select historie.id, aufgabe.id as 'aid', rechnung, beschreibung, typ from historie, aufgabe where historie.aufgabe = aufgabe.id and account = $account and uebung = $uebung and eingabeergebnis is null limit 1";
		return $this->getQuery($sql);
	}
	
	public function setAktiv($id) {
		$sql = "Update uebung set aktiv = true where id = $id";
		return $this->setQuery($sql);
	}
	
	public function setInaktiv($id) {
		$sql = "Update uebung set aktiv = false where id = $id";
		return $this->setQuery($sql);
	}
	
	public function deleteUebung($id) {
		$sql = "Delete from uebung where id = $id";
		return $this->setQuery($sql);
	}
	
	public function getLehrer() {
		$sql = "Select id, concat(nachname, ', ', vorname, ' (', username, ')') from account where rolle='lehrer'";
		return $this->getQuery($sql);
	}
	
	public function setLehrerKlassen($lehrerid, array $klassenids) {
		$sql = "Delete from accountklasse where account = $lehrerid";
		$this->setQuery($sql);
		foreach ($klassenids as $k) {
			$sql = "insert into accountklasse (account, klasse) values ($lehrerid, $k[0])";
			$this->setQuery($sql);
		}
		return true;
	}
}










