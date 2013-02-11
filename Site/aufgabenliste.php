<?php
#�bersicht der Aufgaben durch Aufruf der Methode, die f�r jede �bung ein Formular erstellt 

class TaskListSite extends Site {
	
	public function getName() {
		return 'tasklist';
	}
	
	public function anzeigen() {
		$ret = '';
		if (isset($_SESSION["user"]) && $_SESSION["user"]["rolle"] != "guest") {
			
			$mysql = new MySQL();
			$ziel = $_SERVER["PHP_SELF"]."?site=aufgabe";  
			$ret = $ret.$mysql->makeSchuelerTaskList($_SESSION["user"]["id"], $ziel);
		
		} else {
			$ret = $ret."Zugriff verweigert!";
		}
		return $ret;
	}
}

$currentSite = new TaskListSite();

?>