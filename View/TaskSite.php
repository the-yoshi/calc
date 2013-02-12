<?php
class TaskSite extends Site {
	
	# possible input:
	# _GET["uebung"]	(optional)

	public function getName () {
		return "aufgabe";
	}

	public function anzeigen() {
		if (!isset($_GET["uebung"]))
		{
			$tls = new TaskListSite();
			return $tls->anzeigen();
		}
		if (!isset($_SESSION["currentExam"])) {
			$_SESSION["currentExam"] = new Exam($_GET["uebung"]);
		}
		
		$exam = $_SESSION["currentExam"];
		#Eintragen des alten ergebnisses und falls letzte aufgabe weiterleiten an auswertung
		if (isset($_POST["ergebnis"])) {
			$exam->storeCurrentSolution($_POST["ergebnis"]);
		}
		
		$mysql = ResourceManager::$mysql;
		$account = ResourceManager::$user["id"];
		$formtarget = ResourceManager::$httpRoot.'?site=aufgabe&uebung='.$_GET["uebung"];
		$ret = "";
		
		if ($exam->getAssignmentCount() - $exam->getCurrentAssignmentId() == 0) {
			unset($_SESSION["currentExam"]);
			return "Fertig :) Glückwunsch! Möchtest du die <a href='".ResourceManager::$httpRoot."?site=statistik&uebung=".$exam->examId."'>Auswertung ansehen</a>?";
		} else {
			$a = $exam->getCurrentAssignment();
			$ret .= '<h2>Uebung '.$exam->examId.'</h2>';
			
			$nr = $exam->getCurrentAssignmentId();
			$c = $exam->getAssignmentCount();
			
			$ret .= "Nr ".($nr+1).'/'.$c.": <br />";
			
			$ret .= '<form name="f1" action="'.$formtarget.'" method="post"><label>'.$a->description. " " . $a->term->getRT();	
				if ($a->type == "vergleichen") {
					$ret .= '<input type="submit" name="ergebnis" value="richtig" />
					<input type="submit" name="ergebnis" value="falsch" />';
				} else {
					$ret .= '<input type="text" name="ergebnis" size="5" />
					<input type="submit" value="OK" />';
				}
			$ret .= '</label>	
			</form>';
			
			return $ret;
		}
	}
}

?>