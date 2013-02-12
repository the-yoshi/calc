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
			$ort = $_SERVER["PHP_SELF"]."?site=aufgabe";  
			$id = $_SESSION["user"]["id"];
			$sql = "Select uebung.id, bezeichnung, anzahl, modus from uebung, historie where uebung.id = historie.uebung and aktiv > 0 and historie.account = $id group by historie.uebung";
			$array = $mysql->getQuery($sql);
			
			$html = "";
			foreach ($array as $a) {

				$html .= '<p><strong>Übung '.$a["id"].': </strong>';
				$verbleibend = $mysql->getCountAufgaben($id, $a["id"]);
				if ($a["anzahl"] == $verbleibend[0][0]) {$wort = "beginnen";} else {$wort = "fortsetzen";}
				if ($verbleibend[0][0] == 0) {
					$statslink = $_SERVER["PHP_SELF"]."?site=statistik&uebung=".$a["id"];
					$html .= 'Test abgeschlossen! <a href="'.$statslink.'">Auswertung</a>';
				} else {
					$html .= '<form action="'.$ort.'" method="POST" name="uebung_'.$a["id"].'">';
					$html .= '<input type="hidden" name="uebung" value="'.$a["id"].'">';
					$html .= '<input type="hidden" name="anzahl" value="'.$a["anzahl"].'">';
					$html .= '<input type="hidden" name="modus" value="'.$a["modus"].'">';
					$html .= '<input type="submit" value="'.$a["bezeichnung"].' '.$wort.' ('.$verbleibend[0][0].'/'.$a["anzahl"].' Aufgaben verbleibend)" >';
					$html .= '</form>';
				}
				$html .= '</p><br />';
			}
			return $html;
		
		} else {
			$ret = $ret."Zugriff verweigert!";
		}
		return $ret;
	}
}

$currentSite = new TaskListSite();

?>