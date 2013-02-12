<?php

class StatisticsSite extends Site {
	
	# possible input:
	# _GET["uebung"]
	# _GET["alltime"]	(optional)
	
	public function getName() {
		return "statistik";
	}
	
	public function anzeigen() {
		$ret = '';
		if (ResourceManager::$user["rolle"] == "lehrer" || ResourceManager::$user["rolle"] == "admin")
		{
			## show everyone's statistics
			
			# show exam-specific statistics
			if (isset($_GET["uebung"])) {
				$exam = $_GET["uebung"];
				if (isset($_GET["alltime"]))
					$ret = $this->showAllExamStats($exam);
				else
					$ret = $this->showAllLatestExamStats($exam);
			}
			# show all statistics
			else {
				if (isset($_GET["alltime"]))
					$ret = $this->showAllStats();
				else
					$ret = $this->showAllLatestStats();
			}
		}
		else {
			## show personal statistics
			
			$uid = ResourceManager::$user["id"];
			
			# show exam-specific statistics
			if (isset($_GET["uebung"])) {
				$exam = $_GET["uebung"];
				if (isset($_GET["alltime"]))
					$ret = $this->showExamStats($uid, $exam);
				else
					$ret = $this->showLatestExamStats($uid, $exam);
			}
			# show all personal statistics
			else {
				if (isset($_GET["alltime"]))
					$ret = $this->showPersonalStats();
				else
					$ret = $this->showLatestPersonalStats();
			}
		}
		return $ret;
	}
	
	private function showPersonalStats() {
		return "not important";
	}
	
	private function showLatestPersonalStats() {
		return "not important";
	}
	
	private function showAllExamStats($examId) {
		return "bing";
	}
	
	private function showAllLatestExamStats($examId) {
		return "bong";
	}
	
	private function showLatestExamStats($userId, $examId) {
		$historyItems = HistoryItem::getLatestHistoryItems($_SESSION["user"]["id"], $examId);
		$ret = '<table><tr><td>Aufgabe</td><td>Richtige Lösung</td><td>Deine Lösung</td></tr>';
		$count = 0;
		foreach ($historyItems as $item)
		{
			$color = "red";
			if ($item[2] == $item[3]) {
				$count++;
				$color = "green";
			}
			$ret .= "<tr><td>".$item[0].' '.$item[1]."</td><td>".$item[2]."</td><td style='background-color: $color'>".$item[3]."</td></tr>";
		}
		$ret .= '</table>';
		$ret .= '<p>Du hast '.$count.' von '.count($historyItems).' Aufgaben gelöst. Glückwunsch!</p>';
		
		return $ret;
	}
	
	public function showExamStats($userId, $examId) {
		$mysql = new MySQL();
		$count = 0;
		$historyItems = HistoryItem::getHistoryItems($_SESSION["user"]["id"], $examId);
		$ret = '';
		
		$ret .= '<table><tr><td>Aufgabe</td><td>Richtige Lösung</td><td>Deine Lösung</td><td>Datum</td></tr>';
		
		foreach ($historyItems as $item)
		{
			$color = "red";
			if ($item[2] == $item[3]) {
				$count++;
				$color = "green";
			}
			
			$ret .= "<tr><td>".$item[0].' '.$item[1]."</td><td>".$item[2]."</td><td style='background-color: $color'>".$item[3]."</td><td>".$item[4]."</td></tr>";
		}
		$ret .= '</table>';
		
		$ret .= '<p>Du hast '.$count.' von '.count($historyItems).' Aufgaben gelöst. Glückwunsch!</p>';
		
		return $ret;
	}
}

?>