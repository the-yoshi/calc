<?php

class HistoryItem {
	
	
	public static function getHistoryItems ($account, $uebung) {
		$mysql = ResourceManager::$mysql;
		$sql = "SELECT beschreibung,rechnung,phpergebnis,eingabeergebnis,abgabe FROM historie WHERE account = '$account' AND uebung = '$uebung'";
		return $mysql->getQuery($sql);
	}
	
	public static function getLatestHistoryItems ($account, $uebung) {
		$mysql = ResourceManager::$mysql;
		$sql = "SELECT beschreibung,rechnung,phpergebnis,eingabeergebnis,abgabe FROM historie
				WHERE account = '$account' AND uebung = '$uebung' AND abgabe = (SELECT MAX(abgabe) FROM historie WHERE account = $account)
				ORDER BY abgabe DESC";
		return $mysql->getQuery($sql);
	}
	
	public static function getCorrectAnswersPercentage ($account, $uebung) {
		$mysql = ResourceManager::$mysql;
		$sql = "SELECT COUNT(*) AS num FROM historie
				WHERE account = '$account' AND uebung = '$uebung' AND phpergebnis = eingabeergebnis";
		$correct = $mysql->getQuery($sql);
		$correct = $correct[0]["num"];
		$sql = "SELECT COUNT(*) AS num FROM historie
				WHERE account = '$account' AND uebung = '$uebung'";
		$all = $mysql->getQuery($sql);
		$all = $all[0]["num"];
		
		if ($all > 0)
			return ($correct/$all)*100;
		else
			return "";
	}
	
	public static function getLatestCorrectAnswersPercentage ($account, $uebung) {
		$mysql = ResourceManager::$mysql;
		$sql = "SELECT COUNT(*) AS num FROM historie
				WHERE account = '$account' AND uebung = '$uebung' AND phpergebnis = eingabeergebnis AND abgabe = (SELECT MAX(abgabe) FROM historie WHERE account = $account)";
		$correct = $mysql->getQuery($sql);
		$correct = $correct[0]["num"];
		$sql = "SELECT COUNT(*) AS num FROM historie
				WHERE account = '$account' AND uebung = '$uebung' AND abgabe = (SELECT MAX(abgabe) FROM historie WHERE account = $account)";
		$all = $mysql->getQuery($sql);
		$all = $all[0]["num"];
		
		if ($all > 0)
			return ($correct/$all)*100;
		else
			return "";
	}
}


?>