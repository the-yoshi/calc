<?php

class StatisticsSite extends Site {
	
	# possible input:
	# _GET["uebung"]
	# _GET["alltime"]	(optional)
	
	public function getName() {
		return "statistik";
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
				$exam = $_GET["uebung"];
				if (isset($_GET["alltime"]))
					$ret .= $this->showExamStats($user->id, $exam);
				else
					$ret .= $this->showLatestExamStats($user->id, $exam);
			}
			# show all personal statistics
			else {
				if (isset($_GET["alltime"]))
					$ret .= $this->showPersonalStats();
				else
					$ret .= $this->showLatestPersonalStats();
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
		# students X exams table
		
		return "important!";
	}
	
	private function showAllLatestExamStats($examId) {
		return "bong";
	}
	
	private function showHistoryItems($historyItems) {
		$ret = '<table><tr><td>Aufgabe</td><td>Richtige Lösung</td><td>Deine Lösung</td></tr>';
		$count = 0;
		foreach ($historyItems as $assignmentInstance)
		{
			$color = "red";
			if ($assignmentInstance->isCorrect()) {
				$count++;
				$color = "green";
			}
			$ret .= "<tr><td>".$assignmentInstance->term."</td>";
			$ret .= "<td>".$assignmentInstance->correctResult."</td>";
			$ret .= "<td style='background-color: $color'>".$assignmentInstance->givenResult."</td></tr>";
		}
		$ret .= '</table>';
		return $ret;
	}
	
	private function showLatestExamStats($userId, $examId) {
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid='$userId' AND examid='$examId' AND date = (SELECT MAX(date) FROM historyitem WHERE accountid = $userId)");
	
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