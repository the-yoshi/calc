<?php
class ExamsSite extends Site {
	
	public function getName () {
		return "aufgabenverwaltung";
	}
	
	private function showBT() {
		$texts = array('&Uuml;bungen verwalten');
		$links = array(ResourceManager::$httpRoot."?site=aufgabenverwaltung");
		
		if (isset($_GET["erstellen"])) {
			$texts[] = "Neue &Uuml;bung anlegen";
			$links[] = ResourceManager::$httpRoot.'?site=aufgabenverwaltung&erstellen';
		}
		else if (isset($_GET["bearbeiten"])) {
			$texts[] = "&Uuml;bung bearbeiten";
			$links[] = ResourceManager::$httpRoot.'?site=aufgabenverwaltung&bearbeiten';
		}
		return ViewHelper::createBT($texts, $links);
	}
	
	public function showExamList() {
		$ziel = $_SERVER["PHP_SELF"]."?site=".$this->getName();
		$id = ResourceManager::$user->id;
		$array = StorageManager::getSorted("Exam", "id", false);
		
		$html = "<table><tr class=\"col\"><td>&Uuml;bung</td><td>Dauer</td><td>Aufgaben</td><td>Aktionen</td>";
		foreach ($array as $a) {
			$editlink = $ziel."&bearbeiten&uebung=".$a->id;
			$deleteLink = $ziel."&loeschen=".$a->id;
			$t = ' - ';
			$actions = "<a href=\"$editlink\">Bearbeiten</a>$t".
					   "<a href=\"$deleteLink\">L&ouml;schen</a>";
			
			$html .= ViewHelper::createTableRow(array($a->name, 
													  $a->duration.' '.$a->durationType,
													  $this->showExamAssignments($a, true),
													  $actions));
							
		}
		$html .= '<tr><td colspan="4" style="text-align:right"><a href="'.$ziel.'&erstellen">Neue &Uuml;bung anlegen</a></td></tr>';
		$html .= '</table>';
		return $html;
	}
	
	public function showEditExamForm($examId) {
		return 'Noch nicht implementiert!';
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
		$newExam->duration = 0;
		$newExam->durationType = 'assignments';
		
		foreach ($newExam->getAssignments() as $assignment) {
			$newExam->duration += $assignment->count;
		}
		
		$ret = '<strong>Neue Übung:</strong>
		<table>
		<form name="neu" action="'.$ziel.'" method="POST">
			<tr><td>Name:</td><td colspan="2""><input style="width:100%" type="text" name="exam_name" value="'.$newExam->name.'"/></td>
			<td colspan="3"><input type="checkbox" name="random" />Zuf&auml;llige Aufgabenreihenfolge</td></tr>
			<tr><td>Dauer:</td><td><input style="width:100%; text-align:right;" type="text" readonly="true" name="duration" size="3" value="'.$newExam->duration.'"/><td>';
		$ret .= ViewHelper::createDropdownList("durationType", $newExam->durationType, array('assignments', 'minutes'), array('Aufgaben', 'Minuten'));
		$ret .= '</td><td colspan="3"></td></tr>
		</table>
		<strong>Aufgaben:</strong><br/>';
		$ret .= $this->showExamAssignments($newExam);
		$ret .= '</form>';
		return $ret;
	}
	
	public function showExamAssignments($exam, $short = FALSE) {
		$ret = "";
		if ($short)	{
			$num = 0;
			foreach ($exam->getAssignments() as $a) {
				$num++;
				$ret .= '<div style="width:40%;float:left;">'.$num.'. '.$a->description.'</div><div style="width:30%;clear:both;">'.$a->termScheme.'</div><div style="width:30%;float:right;">'.$a->count.'</div>';
			}
		} else {
			$ret .= '<table>';
			$ret .= ViewHelper::createTableRow(array('Beschreibung', 'Typ', 'Term', 'Variablengrenzen', 'Anzahl'));
			foreach ($exam->getAssignments() as $a) {
				$ret .= ViewHelper::createTableRow(array($a->description, $a->type, $a->termScheme, $this->showVariableSettings($a), $a->count));
			}
			$ret .= '<tr><td><a href="'.$newAssignmentLink.'">Neue Aufgabe anlegen</a></td></tr>';
			$ret .= '<tr><td colspan="5" style="text-align:right;"><input name="submit" type="submit" value="&Uuml;bung Speichern"></td></tr>';
			$ret .= '</table>';
		}

	
		
		return $ret;
	}
	
	public function anzeigen() {
		if (!isset(ResourceManager::$user))
			Routing::relocate("");
		
		$user = ResourceManager::$user;
		$ret = $this->showBT();
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
					$newExam->id = $newExam->store();
					
					if (isset($_POST["random"]) && $_POST["random"] == "on")
						$newExam->addSetting(Setting::fromArray(array($newExam->id, -1, "randomOrder", "true")));
					
					foreach ($newExam->getAssignments() as $assignment) {
						$assignment->id = -1;
						$assignment->examId = $newExam->id;
						$assignmentId = $assignment->store();
						
						foreach ($assignment->getVariables() as $variable) {
							$variable->id = -1;
							$variable->assignmentId = $assignmentId;
							$variable->store();
						}
						foreach (array_keys($assignment->getSettings()) as $setting) {
							$st = $assignment->getSettings();
							$s = Setting::fromArray(array($newExam->id, $assignment->id, $setting, $st[$setting]));
							$s->store();
						}

					}
					foreach (array_keys($newExam->getSettings()) as $setting) {
							$st = $newExam->getSettings();
							$s = Setting::fromArray(array($newExam->id, -1, $setting, $st[$setting]));
							$s->store();
					}
					
					$ret .= "<p class=\"gruen\">Übung \"".$newExam->name."\" wurde erstellt!</p>";
					
					unset($_SESSION["newExam"]);
					$ret .= $this->showExamList();
				}
				# show new Exam form
				else {
					$ret .= $this->showNewExamForm();
				}
			}
			else if (isset($_GET["bearbeiten"])) {
				$ret .= $this->showEditExamForm($_GET["uebung"]);
			} else if (isset($_GET["loeschen"])) {
				$exam = StorageManager::getById("Exam", $_GET["loeschen"]);
				
				if ($exam->delete()) {
					$ret .= "<p class=\"gruen\">&Uuml;bung gel&ouml;scht.</p>";
					$ret .= $this->showExamList();
				}
				else {
					$ret .= "<p class=\"rot\">L&oumlschen der &Uuml;bung ist fehlgeschlagen!</p>";
					$ret .= $this->showExamList();
				}
			} else {
				$ret .= $this->showExamList();
			}
		}
		return $ret;
	}
}
?>