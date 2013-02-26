<?php

class StatisticsSite extends Site {
	
	# possible input:
	# _GET["uebung"]
	# _GET["alltime"]	(optional)
	
	public function getName() {
		return "statistik";
	}
	
	private function showPersonalBT() {
		$links = array();
		$texts = array();
		$texts[] = 'Meine &Uuml;bungen';
		$links[] = ResourceManager::$httpRoot.'?site=aufgabenliste';
		if (isset($_GET["alltime"]))
			$texts[] = 'Statistik f&uuml;r &Uuml;bung '.$_GET["uebung"];
		else
			$texts[] = 'Statistik f&uuml;r &Uuml;bung '.$_GET["uebung"].' (letztes Mal)';
		$links[] = '#';
		return ViewHelper::createBT($texts, $links);
	}
	
	public function anzeigen() {
		$user = ResourceManager::$user;
		$ret = '';
		if (isset($_GET["allstats"]) && ($user->role == "lehrer" || $user->role == "admin")) {
			## show everyone's statistics
			
			# show exam-specific statistics
			if (isset($_GET["uebung"])) {
				$exam = $_GET["uebung"];
				if (isset($_GET["alltime"]))
					$ret .= $this->showAllExamStats($exam);
				else
					$ret .= $this->showAllLatestExamStats($exam);
			}
			# show all statistics
			else {
				if (isset($_GET["alltime"]))
					$ret .= $this->showAllStats();
				else
					$ret .= $this->showAllLatestStats();
			}
		}
		else {
			## show personal statistics
			
			# show exam-specific statistics
			if (isset($_GET["uebung"])) {
				$ret .= $this->showPersonalBT();
				$exam = $_GET["uebung"];
				if (isset($_GET["alltime"]))
					$ret .= $this->showExamStats($user->id, $exam);
				else
					$ret .= $this->showLatestExamStats($user->id, $exam);
			}
			else
				Routing::relocate("aufgabenliste");
		}
		return $ret;
	}
	
	private function showAllExamStats($examId) {
		# students X exams table
		
		return "important!";
	}
	
	private function showAllLatestExamStats($examId) {
		return "bong";
	}
	
	private function showHistoryItems($historyItems) {
		$ret = '<table><tr><td colspan="2">Aufgabe</td><td>Richtige Lösung</td><td>Deine Lösung</td></tr>';
		$count = 0;
		foreach ($historyItems as $assignmentInstance)
		{
			$color = "red";
			if ($assignmentInstance->isCorrect()) {
				$count++;
				$color = "green";
			}
			$ret .= "<tr><td>".$assignmentInstance->parentAssignment->description."</td>";
			$ret .= "<td>".$assignmentInstance->term."</td>";
			$ret .= "<td>".$assignmentInstance->correctResult."</td>";
			$ret .= "<td style='background-color: $color'>".$assignmentInstance->givenResult."</td></tr>";
		}
		$ret .= '</table>';
		return $ret;
	}
	
	private function showLatestExamStats($userId, $examId) {
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid='$userId' AND examid='$examId' AND date = (SELECT MAX(date) FROM historyitem WHERE accountid = $userId AND examid = $examId)");
	
		$ret = $this->showHistoryItems($historyItems);
		$count = StorageManager::getLatestCorrectAnswersPercentage($userId, $examId);
		$ret .= '<p>Du hast '.$count.'% der Aufgaben gelöst. Glückwunsch!</p>';
		
		return $ret;
	}
	
	public function showExamStats($userId, $examId) {
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid='$userId' AND examid='$examId'");
		
		$ret = $this->showHistoryItems($historyItems);
		
		return $ret;
	}
}

?>