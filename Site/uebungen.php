<?php
class ExamsSite extends Site {
	
	public function getName () {
		return "uebungen";
	}
	
	public function anzeigen() {
		$ort = $_SERVER["PHP_SELF"]."?site=".$this->getName();
		
		if (isset($_SESSION["user"]) && ($_SESSION["user"]["rolle"] == "admin" || $_SESSION["user"]["rolle"] == "lehrer")) {
			#Neue Übungen anlegen, oder zu einer vorhandenen Übung zum Bearbeiten springen 
			$ziel = $_SERVER["PHP_SELF"].'?site=zuteilung'; 
			$mysql = new MySQL(); 
			$lehrerid = $_SESSION["user"]["id"];
		
			$ret ='<strong>Neue Übung:</strong>
				<br />
				<form name="neu" action="'.$ziel.'" method="POST">
					<label>
						Name:
						<input type="text" name="bezeichnung" />
					</label>
					<br />
					<input type="hidden" name="ersteller" value="<?php echo $lehrerid; ?>" />
					<label>
						Modus:
						<select name="modus">
							<option value="vorgabe" > Übungsmodus</option>
							<option value="klausur" > Klausurmodus</option>
						</select>
					</label>
					<label>
						Anzahl:
						<input type="text" name="anzahl" size="3" />
					</label>
					<br />
					<label>
						<input type="hidden" name="aktiv" value="false" />
						<input type="submit" name="aktion" value="Anlegen" />
					</label>
				</form>
				<br />
				<strong>Übersicht</strong>';
			$ret .= $mysql->makeTaskList($lehrerid, $ziel);
		} else {
			$ret .= 'Zugriff verweigert!';
		}
		return $ret;
	}
}

$currentSite = new ExamsSite();
?>