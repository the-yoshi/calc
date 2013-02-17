<?php
class TaskSite extends Site {
	
	# possible input:
	# _GET["uebung"]	(optional)

	public function getName () {
		return "aufgabe";
	}
	
	private function showAssignment($examInstance) {
			$formtarget = ResourceManager::$httpRoot.'?site=aufgabe&uebung='.$_GET["uebung"];
			$a = $examInstance->getCurrentAssignment();
			$nr = $examInstance->getCurrentAssignmentId();
			$c = $examInstance->getAssignmentCount();
			
			$ret = '<h2>Uebung '.$examInstance->exam->id.'</h2>';
			$ret .= "Nr ".($nr+1).'/'.$c.": <br />";
			
			$ret .= '<form name="f1" action="'.$formtarget.'" method="post"><label>'.$a->parentAssignment->description. " " . $a->term;	
				if ($a->parentAssignment->type == "vergleichen") {
					$ret .= '<input type="submit" name="ergebnis" value="richtig" />
					<input type="submit" name="ergebnis" value="falsch" />';
				} else {
					$ret .= '=<input type="text" name="ergebnis" size="5" />
					<input type="submit" value="OK" />';
				}
			$ret .= '</label>	
			</form>';
			
			return $ret;
	}
	

	public function anzeigen() {
		if (!isset($_GET["uebung"]))
		{
			Routing::relocate("aufgabenliste");
		}
		if (!isset($_SESSION["currentExam"])) {
			$template = StorageManager::getById("Exam", $_GET["uebung"]);
			$examInstance = $template->generateInstance();
			$_SESSION["currentExam"] = $examInstance;
			
		} else {
			$examInstance = $_SESSION["currentExam"];
		}
		
		if (isset($_POST["ergebnis"])) {
			$examInstance->storeCurrentSolution($_POST["ergebnis"]);
		}
		
		$account = ResourceManager::$user->id;
		
		if ($examInstance->isFinished()) {
			$ret = "Fertig :) Glückwunsch! Möchtest du die <a href='".ResourceManager::$httpRoot."?site=statistik&uebung=".$examInstance->exam->id."'>Auswertung ansehen</a>?";
			unset($_SESSION["currentExam"]);
		} else {
			$ret = $this->showAssignment($examInstance);
			$_SESSION["currentExam"] = $examInstance;
		}
		return $ret;
	}
}

?>