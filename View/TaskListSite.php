<?php

class TaskListSite extends Site {
	
	public function getName() {
		return 'tasklist';
	}
	
	public function anzeigen() {
		$ret = '';
		if (isset(ResourceManager::$user) && ResourceManager::$user->role != "guest") {
			$ziel = $_SERVER["PHP_SELF"]."?site=aufgabe&uebung=";  
			$id = ResourceManager::$user->id;
			$array = StorageManager::get("Exam");
			
			$html = "<table><tr><td>Aufgabe</td><td>Letztes Mal richtig</td><td>Gesamt richtig</td><td>Aktionen</td>";
			foreach ($array as $a) {
				
				$latestStatsValue = StorageManager::getLatestCorrectAnswersPercentage(ResourceManager::$user->id, $a->id);
				$latestStatsLink = $_SERVER["PHP_SELF"]."?site=statistik&uebung=".$a->id;
				$statsvalue = StorageManager::getCorrectAnswersPercentage(ResourceManager::$user->id, $a->id);
				$statslink = $_SERVER["PHP_SELF"]."?site=statistik&alltime&uebung=".$a->id;
				$linktarget = $ziel.$a->id;
				
				if (isset($_SESSION["currentExam"]) && $_SESSION["currentExam"]->examId == $a->id) {
					$actions = '<a href="'.$linktarget.'">Weitermachen ('.($_SESSION["currentExam"]->getCurrentAssignmentId()+1)."/".$_SESSION["currentExam"]->getAssignmentCount().')</a>';
				} else
					$actions = '<a href="'.$linktarget.'">Rechnen!</a>';
				
				$html .= '<tr>';
				$html .= '<td>'.$a->name.'</td>';
				$html .= '<td><a href="'.$latestStatsLink.'">'.$latestStatsValue.'%</a></td>';
				$html .= '<td><a href="'.$statslink.'">'.$statsvalue.'%</a></td>';
				$html .= '<td>'.$actions.'</td>';
				$html .= '<tr/>';
			}
			$html .= '</table>';
			return $html;
		
		} else {
			$ret = $ret."Zugriff verweigert!";
		}
		return $ret;
	}
}

?>