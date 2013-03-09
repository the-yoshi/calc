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
	
	private function showBT() {
		$links = array();
		$texts = array();
		$texts[] = 'Statistik';
		$links[] = ResourceManager::$httpRoot.'?site=statistik&lehrer';
		if (isset($_GET["uebung"]) && isset($_GET["schueler"])) {
			$student = StorageManager::getById("Account", $_GET["schueler"]);
			if (isset($_GET["alltime"]))
				$texts[] = "Statistik f&uuml;r \"$student->name\" (&Uuml;bung ".$_GET["uebung"].")";
			else
				$texts[] = "Statistik f&uuml;r \"$student->name\" (&Uuml;bung ".$_GET["uebung"].', letztes Mal)';
			$links[] = "#";
		}

		return ViewHelper::createBT($texts, $links);
	}
	
	public function anzeigen() {
		$user = ResourceManager::$user;
		$ret = '';
		if (isset($_GET["lehrer"]) && ($user->role == "lehrer" || $user->role == "admin")) {
			## show everyone's statistics
			$ret .= $this->showBT();
			# show exam-specific statistics
			if (isset($_GET["uebung"]) && !isset($_GET["schueler"])) {
				$exam = $_GET["uebung"];
				if (isset($_GET["alltime"]))
					$ret .= $this->showAllExamStats($exam);
				else
					$ret .= $this->showAllLatestExamStats($exam);
			}
			else if (isset($_GET["uebung"]) && isset($_GET["schueler"])) {
				$examId = $_GET["uebung"];
				$studentId = $_GET["schueler"];
				if (isset($_GET["alltime"]))
					$ret .= $this->showExamStats($studentId, $examId);
				else
					$ret .= $this->showLatestExamStats($studentId, $examId);
			}
			# show all statistics
			else {
				$ret .= $this->showAllStats();
			}
		}
		else {
			## show personal statistics
			$ret .= $this->showPersonalBT();
			# show exam-specific statistics
			if (isset($_GET["uebung"])) {
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
	
	private function getColor($percentage) {
		if ($percentage < 33)
			return "rot";
		else if ($percentage < 67)
			return "gelb";
		else
			return "gruen";
	}
	
	private function showAllStats() {
		# students X exams table
		
		$ret = '<table class="statstable">';
		
		$exams = StorageManager::getSorted("Exam", "id", false);
		$students = StorageManager::get("Account");
		
        $ret .='<tr class="topCol"><td></td>';
		
		foreach ($exams as $exam) {
			$ret .= '<td colspan="2">'.$exam->name.'</td>';
		}
        $ret .= '</tr><tr class="col"><td></td>';
		for ($x=0; $x<count($exams); $x++) {
			$ret .= '<td>letzte</td><td>Gesamt</td>';
		}
		$ret .= '</tr>';
		
		
		foreach ($students as $student) {
			$ret .= '<tr>';
			$ret .= '<td class="links">'.$student->name.'</td>';
			foreach ($exams as $exam) {
				$latest = StorageManager::getLatestCorrectAnswersPercentage($student->id, $exam->id);
				$latestColor = $this->getColor($latest);
				$latestLink = Routing::getPath(get_class($this)).'&lehrer&uebung='.$exam->id."&schueler=".$student->id;
				$ret .= "<td class=\"$latestColor\"><a href=\"$latestLink\">$latest</a></td>";
				
				$all = StorageManager::getCorrectAnswersPercentage($student->id, $exam->id);
				$allColor = $this->getColor($all);
				$allLink = Routing::getPath(get_class($this)).'&lehrer&uebung='.$exam->id."&schueler=".$student->id."&alltime";
				$ret .= "<td class=\"$allColor\"><a href=\"$allLink\">$all</a></td>";
			}
			$ret .= '</tr>';
		}
		$ret .= '</table>';
		
		return $ret;
	}
	
	private function showAllLatestExamStats($examId) {
		return "bong";
	}
	
	private function showAllExamStats($examId) {
		return "bing";
	}
	
	private function showHistoryItems($historyItems) {
		$selfParams = "&";
		if (isset($_GET["uebung"]))
			$selfParams .= "uebung=".$_GET["uebung"]."&";
		if (isset($_GET["lehrer"]))
			$selfParams .= "lehrer&";
		if (isset($_GET["alltime"]))
			$selfParams .= "alltime&";
		if (isset($_GET["schueler"]))
			$selfParams .= "schueler=".$_GET["schueler"]."&";
		if (!isset($_GET["nurfalsche"]))
			$selfParams .= "nurfalsche";
		
		$ret = '<table class="statstable"><tr class="col"><td colspan="2" width="50%">Aufgabe</td><td>Richtige Lösung</td><td>Eingegebene Lösung</td><td style="width:146px"><a href="'.Routing::getPath(get_class($this)).$selfParams.'">';
		
		
		if (!isset($_GET["nurfalsche"]))
			$ret .= 'Nur falsche';
		else
			$ret .= 'Alle';
			
		$ret .= ' L&ouml;sungen anzeigen</a></td></tr>';
		$count = 0;
		foreach ($historyItems as $assignmentInstance)
		{
			$class = "rot";
			if ($assignmentInstance->isCorrect()) {
				$count++;
				$class = "gruen";
			}
			
			# if 'show only wrong' is activated and we're correct, skip
			if (isset($_GET["nurfalsche"]) && $assignmentInstance->isCorrect())
				continue;
			
			$ret .= "<tr><td>".$assignmentInstance->parentAssignment->description."</td>";
			$ret .= "<td>".$assignmentInstance->term."</td>";
			$ret .= "<td style='text-align:center'>".$assignmentInstance->correctResult."</td>";
			$ret .= "<td class='$class'>".$assignmentInstance->givenResult."</td></tr>";
		}
		$ret .= '</table>';
		return $ret;
	}
	
	private function showLatestExamStats($userId, $examId) {
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid='$userId' AND examid='$examId' AND date = (SELECT MAX(date) FROM historyitem WHERE accountid = $userId AND examid = $examId)");
	
		$ret = $this->showHistoryItems($historyItems);
		$count = StorageManager::getLatestCorrectAnswersPercentage($userId, $examId);
		if (ResourceManager::$user->role == "schueler") {
			$ret .= '<p>Du hast '.$count.'% der Aufgaben gelöst. ';
			if ($count < 20)
				$ret .= 'Schade! Versuch\'s noch einmal!';
			else if ($count < 50)
				$ret .= 'Gar nicht schlecht, aber Du solltest noch ein wenig &uuml;ben ;-)';
			else if ($count < 70)
				$ret .= 'Gut gemacht!';
			else if ($count < 90)
				$ret .= 'Herzlichen Gl&uumlckwunsch!';
			else
				$ret .= 'Wow! Du bist wirklich ein irrer Rechner! :-)';
			
			$ret .=  '</p>';
		}
		
		return $ret;
	}
	
	public function showExamStats($userId, $examId) {
		$historyItems = StorageManager::getByCondition("AssignmentInstance", "accountid='$userId' AND examid='$examId'");
		
		$ret = $this->showHistoryItems($historyItems);
		
		return $ret;
	}
}

?>