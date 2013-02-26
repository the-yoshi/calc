<?php
class ExamsSite extends Site {
	
	public function getName () {
		return "aufgabenverwaltung";
	}
	
	public function showExamList() {
		$ziel = $_SERVER["PHP_SELF"]."?site=".$this->getName();  
		$id = ResourceManager::$user->id;
		$array = StorageManager::get("Exam");
		
		$html = "<table><tr><td>&Uuml;bung</td><td>Dauer</td><td>Aufgaben</td><td>Aktionen</td>";
		foreach ($array as $a) {
			$editlink = $ziel."&bearbeiten&uebung=".$a->id;
			
			$actions = '<a href="'.$editlink.'">Bearbeiten</a>';
			
			$html .= ViewHelper::createTableRow(array($a->name, 
													  $a->duration.' '.$a->durationType,
													  'asd',
													  $actions));
		}
		$html .= '</table>';
		return $html;
	}
	
	public function showEditExamForm($examId) {
		return 'Not there yet';
	}
	
	private function showVariableSettings($assignment) {
		$ret = "";
		$variables = $assignment->getVariables();
		foreach ($variables as $v) {
			$ret .= $v->lowerBound." &le; ".$v->name." &le; ".$v->upperBound."<br/>";
		}
		return $ret;
	}
	
	public function showNewExamForm() {
		$ziel = ResourceManager::$httpRoot.'?site=aufgabenverwaltung&erstellen'; 
		$newAssignmentLink = ResourceManager::$httpRoot.'?site=neueaufgabe';
		
		if (!isset($_SESSION["newExam"]))
			$_SESSION["newExam"] = new Exam();
		
		$newExam = $_SESSION["newExam"];
		
		$ret = '<strong>Neue Übung:</strong>
		<br />
		<form name="neu" action="'.$ziel.'" method="POST">
			<label>
				Name:
				<input type="text" name="exam_name" value="'.$newExam->name.'"/>
			</label>
			<br />
			<label>
				Dauer:
				<input type="text" name="duration" size="3" value="'.$newExam->duration.'"/>';
		$ret .= ViewHelper::createDropdownList("durationType", $newExam->durationType, array('minutes', 'assignments'), array('Minuten', 'Aufgaben'));
		$ret .= '</label>
			<br />
		<br />
		<strong>Aufgaben:</strong><br/><table>';
		$ret .= ViewHelper::createTableRow(array('Beschreibung', 'Typ', 'Term', 'Variablengrenzen', 'Anzahl'));
		foreach ($newExam->getAssignments() as $a) {
			$ret .= ViewHelper::createTableRow(array($a->description, $a->type, $a->termScheme, $this->showVariableSettings($a), $a->count));
		}
		
		$ret .= '<tr><td colspan="3"><a href="'.$newAssignmentLink.'">Neue Aufgabe anlegen</a></td><td><input name="submit" type="submit" value="Speichern"></td></tr>';
		$ret .= '</table></form>';
		
		return $ret;
	}
	
	public function anzeigen() {
		$ort = $_SERVER["PHP_SELF"]."?site=".$this->getName();
		if (!isset(ResourceManager::$user))
			Routing::relocate("");
		
		$user = ResourceManager::$user;
		
		$ret = '<a href="'.$ort.'">Übersicht</a> | <a href="'.$ort.'&erstellen">Übung erstellen</a><br/>';
		
		if ($user->role == "admin" || $user->role == "lehrer") {
			if (isset($_GET["erstellen"])) {
				# save the new exam
				if (isset($_POST["submit"])) {
					$newExam = $_SESSION["newExam"];
					$newExam->id = -1;
					$newExam->name = $_POST["exam_name"];
					$newExam->duration = $_POST["duration"];
					$newExam->durationType = $_POST["durationType"];
					$newExam->creator = ResourceManager::$user->id;
					$newExam->lowerBoundZ = 1;
					$newExam->upperBoundZ = 1;
					$examId = $newExam->store();
					
					foreach ($newExam->getAssignments() as $assignment) {
						$assignment->id = -1;
						$assignment->examId = $examId;
						$assignmentId = $assignment->store();
						
						foreach ($assignment->getVariables() as $variable) {
							$variable->id = -1;
							$variable->assignmentId = $assignmentId;
							$variable->store();
						}
					}
					unset($_SESSION["newExam"]);
					
					Routing::relocate("aufgabenverwaltung");
				}
				# show new Exam form
				else {
					$ret .= $this->showNewExamForm();
				}
			}
			else if (isset($_GET["bearbeiten"])) {
				$ret .= $this->showEditExamForm($_GET["uebung"]);
			}
			else {
				$ret .= $this->showExamList();
			}
		}
		return $ret;
	}
}
?>