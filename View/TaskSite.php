<?php
class TaskSite extends Site {
	
	# possible input:
	# _GET["uebung"]	(optional)

	public function getName () {
		return "aufgabe";
	}
	
	private function showBT() {
		$links = array();
		$texts = array();
		$texts[] = 'Meine &Uuml;bungen';
		$links[] = ResourceManager::$httpRoot.'?site=aufgabenliste';
		$texts[] = 'Rechnen';
		$links[] = '#';
		return ViewHelper::createBT($texts, $links);
	}
	
	private function showAssignment($examInstance) {
			$formtarget = ResourceManager::$httpRoot.'?site=aufgabe&uebung='.$_GET["uebung"];
			$a = $examInstance->getCurrentAssignment();
			$nr = $examInstance->getCurrentAssignmentId();
			$c = $examInstance->getAssignmentCount();
			
			$ret = '<h2>Uebung '.$examInstance->exam->id.'</h2>';
			$ret .= "Nr ".($nr+1).'/'.$c.": <br />";
			
			$ret .= '<form name="f1" action="'.$formtarget.'" method="post"><label>'.$a->parentAssignment->description. "<br/>" . $a->term;	
			if ($a->parentAssignment->type == "evaluate") {
				$ret .= ViewHelper::createDropdownList("ergebnis", "", array('Richtig', 'Falsch'), array('Richtig', 'Falsch'));
			} else {
				$ret .= '=<input type="text" name="ergebnis" size="5" />';
			}
			$ret .= '<input type="submit" value="OK" />';
			$ret .= '</label>	
			</form>';
			
			return $ret;
	}
	

	public function anzeigen() {
		$ret = $this->showBT();
		if (!isset($_GET["uebung"]))
		{
			Routing::relocate("aufgabenliste");
		}
		else if (isset($_SESSION["currentExam"]) && $_GET["uebung"] != $_SESSION["currentExam"]->exam->id) {
			unset($_SESSION["currentExam"]);
		}
		
		if (!isset($_SESSION["currentExam"])) {
			$template = StorageManager::getById("Exam", $_GET["uebung"]);
			$examInstance = $template->generateInstance();
			$_SESSION["currentExam"] = $examInstance;
			
		}
		$examInstance = $_SESSION["currentExam"];
		
		if (isset($_POST["ergebnis"])) {
			$examInstance->storeCurrentSolution($_POST["ergebnis"]);
		}
		
		$account = ResourceManager::$user->id;
		
		if ($examInstance->isFinished()) {
			$ret .= "Fertig :) Glückwunsch! Möchtest du die <a href='".ResourceManager::$httpRoot."?site=statistik&uebung=".$examInstance->exam->id."'>Auswertung ansehen</a>?";
			unset($_SESSION["currentExam"]);
		} else {
			$ret .= $this->showAssignment($examInstance);
			$_SESSION["currentExam"] = $examInstance;
		}
		

		return $ret;
	}
}

?>