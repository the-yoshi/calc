<?php
class TaskSite extends Site {

	public function getName () {
		return "aufgabe";
	}

	public function anzeigen() {
		$mysql = new MySQL();
		$account = $_SESSION["user"]["id"];
		if (!isset($_SESSION["uebung"])) {
			$_SESSION["uebung"] = $_POST["uebung"];
			$_SESSION["modus"] = $_POST["modus"];
			$_SESSION["anzahl"] = $_POST["anzahl"];
		}
		$uebung = $_SESSION["uebung"];
		$modus = $_SESSION["modus"];
		$anzahl = $_SESSION["anzahl"];

		$ort = $_SERVER["PHP_SELF"].'?site=aufgabe';
		$ret = "";
		
		#Eintragen des alten ergebnisses und falls letzte aufgabe weiterleiten an auswertung (folgt)
		if (isset($_POST["ergebnis"])) {
			
			$mysql->setErgebnis($_POST["historie"], $_POST["ergebnis"]);
			
		}
		
		#Anzahl �briger Aufgaben in dieser �bung f�r den Sch�ler
		$nr = $mysql->getCountAufgaben($account, $uebung);
		$nr = $nr[0][0];
		
		if($nr <= 0) {
			header('location: '.$_SERVER["PHP_SELF"]."?site=statistik&uebung=".$uebung); 
		}


		$ret .= '<h2>Uebung '.$uebung.'</h2>';

		#Auslesen der Aufgabe
		$aufgabe = $mysql->getAufgabe($account, $uebung);
		$aufgabe = $aufgabe[0];
		
		#Falls keine Klausuraufgabe, generierung der Aufgabe
		if ($modus == "vorgabe") {
			$aid = $aufgabe["aid"];
			$id = $aufgabe["id"];
			$profil = $mysql->zufallsAufgabe($aid[mt_rand(0, count($aid)-1)]);
			$aufgabe["phpergebnis"] = $profil["phpergebnis"];
			$aufgabe["rechnung"] = $profil["rechnung"];
			$aufgabe["beschreibung"] = $profil["beschreibung"];
			$mysql->setVorgabe($id, $aufgabe["phpergebnis"], $aufgabe["rechnung"], $aufgabe["beschreibung"]);
		}
		
		$ret .= "Nr " . (($anzahl-$nr)+1) . ": <br />";
		#Formular mit der Aufgabe
		$ret .= '<form action="'.$ort.'" method="post"><label>'.$aufgabe["beschreibung"] . " " . $aufgabe["rechnung"];	
			if ($aufgabe["typ"] == "vergleichen") {
				$ret .= '<input type="submit" name="ergebnis" value="richtig" />
				<input type="submit" name="ergebnis" value="falsch" />';
			} else {
				$ret .= '<input type="text" name="ergebnis" size="5" />
				<input type="submit" value="OK" />';
			}
		$ret .= '</label>	
		<input type="hidden" name="historie" value="'.$aufgabe["id"].'" />
		</form>';
		
		return $ret;
	}
}

$currentSite = new TaskSite();

?>