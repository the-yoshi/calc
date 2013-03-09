<?php

class TaskListSite extends Site {
	
	public function getName() {
		return 'aufgabenliste';
	}
	
	private function showBT() {
		$links = array();
		$texts = array();
		$texts[] = 'Meine &Uuml;bungen';
		$links[] = '#';
		return ViewHelper::createBT($texts, $links);
	}
	
	public function showList() {
		$ziel = $_SERVER["PHP_SELF"]."?site=aufgabe&uebung=";  
		$id = ResourceManager::$user->id;
		$array = StorageManager::getSorted("Exam", "id", false);
		
		$html = "<table><tr class=\"col\"><td>&Uuml;bung</td><td>Dauer</td><td>Letztes Mal richtig</td><td>Gesamt richtig</td><td>Aktionen</td>";
		foreach ($array as $a) {
			
			$latestStatsValue = StorageManager::getLatestCorrectAnswersPercentage(ResourceManager::$user->id, $a->id);
			$latestStatsLink = $_SERVER["PHP_SELF"]."?site=statistik&uebung=".$a->id;
			$statsvalue = StorageManager::getCorrectAnswersPercentage(ResourceManager::$user->id, $a->id);
			$statslink = $_SERVER["PHP_SELF"]."?site=statistik&alltime&uebung=".$a->id;
			$linktarget = $ziel.$a->id;
			
			if (isset($_SESSION["currentExam"]) && $_SESSION["currentExam"]->exam->id == $a->id) {
				$actions = '<a href="'.$linktarget.'">Weitermachen ('.($_SESSION["currentExam"]->getCurrentAssignmentId()+1)."/".$_SESSION["currentExam"]->getAssignmentCount().')</a>';
			} else
				$actions = '<a href="'.$linktarget.'">Rechnen!</a>';
			
			$html .= ViewHelper::createTableRow(array($a->name,
													  $a->duration." ".$a->durationType,
													  '<a href="'.$latestStatsLink.'">'.$latestStatsValue.'%</a>',
													  '<a href="'.$statslink.'">'.$statsvalue.'%</a>',
													  $actions), "");
		}
		$html .= '</table>';
		return $html;
	}
	
	public function anzeigen() {
		$ret = $this->showBT();
		if (isset(ResourceManager::$user) && ResourceManager::$user->role != "guest") {
			$ret .= $this->showList();
		
		} else {
			$ret = $ret."Zugriff verweigert!";
		}
		return $ret;
	}
}

?>