<?php

class SetVariablesSite extends Site {
	
	public function getName() {
		return "setzevariablen";
	}
	
	private function showBT() {
		$manageExams = ResourceManager::$httpRoot."?site=aufgabenverwaltung";
		$newExam = ResourceManager::$httpRoot."?site=aufgabenverwaltung&erstellen";
		$newAssignment = ResourceManager::$httpRoot."?site=".$this->getName();
		return '<a href="'.$manageExams.'">&Uuml;bungen verwalten</a> >
				<a href="'.$newExam.'">Neue &Uuml;bung</a> >
				<a href="'.$newAssignment.'">Neue Aufgabe</a> >
				Variablengrenzen festlegen';
	}
	
	private function showVariableForm($term) {
		$assignment = $_SESSION["newAssignment"];
		for ($x=0; $x<strlen($term); $x++) {
			$char = $term[$x];
			if (preg_match("/[a-zA-Z]/", $char)) {
				$v = new Variable();
				$v->name = $char;
				$assignment->addVariable($v);
			}
		}
		$formtarget = ResourceManager::$httpRoot."?site=".$this->getName();
		$ret = "<form action=\"$formtarget\" method=POST><table>";
		$ret .= ViewHelper::createTableRow(array("Variable", "Untere Grenze", "Oberere Grenze"));
		$c = 0;
		foreach ($assignment->getVariables() as $v) {
			$ret .= ViewHelper::createTableRow(array($v->name, '<input name="variable'.$c.'_u">', '<input name="variable'.$c.'_o">'));
			$c++;
		}
		$ret .= '<tr><td colspan=3 style="text-align:right"><input name="submit" type="submit" value="Speichern"></td></tr></table></form>';
		$_SESSION["newAssignment"] = $assignment;

		return $ret;
	}
	
	public function anzeigen() {
		if (isset($_POST["aufgabe_name"]) && isset($_POST["aufgabe_typ"]) && isset($_POST["aufgabe_term"])) {
			$a = new Assignment();
			# set values
			$a->description = $_POST["aufgabe_name"];
			$a->termScheme = $_POST["aufgabe_term"];
			$a->type = $_POST["aufgabe_typ"];
			$a->count = $_POST["aufgabe_anzahl"];
			
			$_SESSION["newAssignment"] = $a;
			return $this->showVariableForm($a->termScheme);
		}
		else if (isset($_POST["submit"]) && isset($_SESSION["newAssignment"])) {
			$assignment = $_SESSION["newAssignment"];
			$c = 0;
			while (isset($_POST["variable".$c."_u"])) {
				$variables = $assignment->getVariables();
				$v = $variables[$c];
				$v->lowerBound = $_POST["variable".$c."_u"];
				$v->upperBound = $_POST["variable".$c."_o"];
				$c++;
			}
			
			# save assignment
			$newExam = $_SESSION["newExam"];
			$newExam->addAssignment($assignment);
			$_SESSION["newExam"] = $newExam;
			
			Routing::relocate("aufgabenverwaltung&erstellen");
		}
		else {
			Routing::relocate("aufgabenverwaltung&erstellen");
		}
	}
}

?>