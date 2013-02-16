<?php
class ExamsSite extends Site {
	
	public function getName () {
		return "uebungen";
	}
	
	public function showExamList() {
		
	}
	
	public function showNewExamForm() {
		$ziel = $_SERVER["PHP_SELF"].'?site=zuteilung'; 
		$ret = '<strong>Neue Übung:</strong>
		<br />
		<form name="neu" action="'.$ziel.'" method="POST">
			<label>
				Name:
				<input type="text" name="name" />
			</label>
			<br />
			<label>
				Dauer:
				<input type="text" name="duration" size="3" />';
		$ret .= ViewHelper::createDropdownList("durationType", array('minutes', 'assignments'), array('Minuten', 'Aufgaben'));
		$ret .= '</label>
			<br />
		</form>
		<br />
		<strong>Aufgaben</strong>';
		
		return $ret;
	}
	
	public function anzeigen() {
		$ort = $_SERVER["PHP_SELF"]."?site=".$this->getName();
		if (!isset(ResourceManager::$user))
			Routing::relocate("");
		
		$user = ResourceManager::$user;
		
		if ($user->role == "admin" || $user->role == "lehrer") {
			if (isset($_GET["erstellen"])) {
				$ret = $this->showNewExamForm();
			}
			else {
				$ret = $this->showExamList();
			}
		}
		else
		return $ret;
	}
}
?>