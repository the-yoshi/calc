<?php

class StatisticsSite extends Site {
	
	public function getName() {
		return "statistik";
	}
	
	public static function uebungAnzeigen($uebung) {
		$uebung = $_GET["uebung"];
		$mysql = new MySQL();
		$count = 0;
		$historyItems = $mysql->getHistoryItems($_SESSION["user"]["id"], $uebung);
		$ret = '';
		
		$ret .= '<table><tr><td>Aufgabe</td><td>Richtige Lösung</td><td>Deine Lösung</td></tr>';
		
		foreach ($historyItems as $item)
		{
			$color = "red";
			if ($item[2] == $item[3]) {
				// setRichtig
				$count++;
				$color = "green";
			}
			
			$ret .= "<tr style='background-color: $color'><td>".$item[0].' '.$item[1]."</td><td>".$item[2]."</td><td>".$item[3]."</td></tr>";
		}
		$ret .= '</table>';
		
		$ret .= '<p>Du hast '.$count.' von '.count($historyItems).' Aufgaben gelöst. Glückwunsch!</p>';
		
		return $ret;
	}
	
	public function anzeigen() {
		$ret = '';
		// auswertung für aufgabe anzeigen
		if (isset($_GET["uebung"])) {
			$ret = StatisticsSite::uebungAnzeigen($_GET["uebung"]);
		}
		
		return $ret;
	}
}

$currentSite = new StatisticsSite();
?>