<?php
class ExamsSite extends Site {
	
	public function getName () {
		return "uebungen";
	}
	
	public function showNewExamForm() {
		$ziel = $_SERVER["PHP_SELF"].'?site=zuteilung'; 
		return '<strong>Neue Übung:</strong>
		<br />
		<form name="neu" action="'.$ziel.'" method="POST">
			<label>
				Name:
				<input type="text" name="name" />
			</label>
			<br />
			<label>
				Dauer:
				<input type="text" name="duration" size="3" />
				<input type=""
			</label>
			<br />
		</form>
		<br />
		<strong>Übersicht</strong>';
		
	}
	
	public function anzeigen() {
		$ort = $_SERVER["PHP_SELF"]."?site=".$this->getName();
		if (!isset(ResourceManager::$user))
			Routing::relocate("");
		
		$user = ResourceManager::$user;
		
		if ($user->role == "admin" || $user->role == "lehrer") {
			$ret = $this->showNewExamForm();
		}
		return $ret;
	}
}
?>