<?php

class NewAssignmentSite extends Site {
	
	public function getName() {
		return "neueaufgabe";
	}
	
	private function showBT() {
		$manageExams = ResourceManager::$httpRoot."?site=aufgabenverwaltung";
		$newExam = ResourceManager::$httpRoot."?site=aufgabenverwaltung&erstellen";
		$newAssignment = ResourceManager::$httpRoot."?site=".$this->getName();
		return '<a href="'.$manageExams.'">&Uuml;bungen verwalten</a> >
				<a href="'.$newExam.'">Neue &Uuml;bung</a> >
				<a href="'.$newAssignment.'">Neue Aufgabe</a> >
				Grundeinstellungen';
	}
	
	private function showNewAssignmentView() {
		$ort = ResourceManager::$httpRoot."?site=setzevariablen";
		return "<form action=\"$ort\" method=\"POST\"><table>
					<tr><td>Name:</td><td><input name=\"aufgabe_name\"></td><td>Typ:</td><td>".ViewHelper::createDropdownList("aufgabe_typ", "Berechnen", array('calc', 'round', 'estimate', 'evaluate'), array('Berechnen', 'Runden', 'Sch&auml;tzen', 'Bewerten')).
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