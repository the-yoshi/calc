<?php

class NewAssignmentSite extends Site {
	
	public function getName() {
		return "neueaufgabe";
	}
	
	private function showBT() {
		$links = array();
		$texts = array();
		$texts[] = '&Uuml;bungen verwalten';
		$links[] = ResourceManager::$httpRoot."?site=aufgabenverwaltung";
		$texts[] = 'Neue &Uuml;bung anlegen';
		$links[] = ResourceManager::$httpRoot."?site=aufgabenverwaltung&erstellen";
		$texts[] = 'Neue Aufgabe';
		$links[] = ResourceManager::$httpRoot."?site=".$this->getName();
		return ViewHelper::createBT($texts, $links);
	}
	
	private function showNewAssignmentView() {
		$ort = ResourceManager::$httpRoot."?site=setzevariablen";
		return "<strong>Grundeinstellungen:</strong>
				<form action=\"$ort\" method=\"POST\"><table>
					<tr><td>Beschreibung:</td><td><input name=\"aufgabe_desc\"></td><td>Typ:</td><td>".ViewHelper::createDropdownList("aufgabe_typ", "Berechnen", array('calc', 'round', 'estimate', 'evaluate'), array('Berechnen', 'Runden', 'Sch&auml;tzen', 'Bewerten')).
				   "<tr><td>Term:</td><td><input name=\"aufgabe_term\"></td><td>Anzahl:</td><td><input name=\"aufgabe_anzahl\"></td></tr>
					<tr><td colspan=\"4\" style=\"text-align:right\"><input type=\"submit\" value=\"Variablengrenzen festlegen\"></td></tr>
				</table></form>";
	}
	
	
	public function anzeigen() {
		
		$ret = $this->showBT();
		# show form
		$ret .= $this->showNewAssignmentView();
		return $ret;
	}
}

?>