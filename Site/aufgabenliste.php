<?php
#Übersicht der Aufgaben durch Aufruf der Methode, die für jede Übung ein Formular erstellt 
if (isset($_SESSION["user"]) && $_SESSION["user"]["rolle"] != "guest") {
	
	$mysql = new MySQL();
	$ziel = $_SERVER["PHP_SELF"]."?site=aufgabe";  
	echo $mysql->makeSchuelerTaskList($_SESSION["user"]["id"], $ziel);

} else {
	echo "Zugriff verweigert!";
}
?>