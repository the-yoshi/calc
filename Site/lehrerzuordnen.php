<?php
#Da Lehrer mehrere Klassen unterrichten k�nnen wird hier die Zuordnung vorgenommen.
#Je nachdem, was ausgesucht wurde, wird die Seite neu generiert mit den neuen Parametern.
#F�r die Formularelemente werden Methoden benutzt 

class AssignTeacherSite extends Site {

	public function getName() {
		return "assignteacher";	
	}

	public function anzeigen() {
		$ret = '';
		if (isset($_SESSION["user"]) && $_SESSION["user"]["rolle"] == "admin") {
		
			$ort = $_SERVER["PHP_SELF"]."?site=lehrerzuordnen";  
			$mysql = new MySQL(); 
		
			$ret .= '<form action="'.$ort.'" method="post">';
			if (!isset($_POST["lehrer"]) && !isset($_POST["klassen"])) {
				$ret .= $mysql->makePOSTList("lehrer", $mysql->getLehrer(), true, true, $ort);	
				$ret .= '<br />';
				
			} elseif (isset($_POST["lehrer"]) && !isset($_POST["klassen"])) {
				$ret .= $mysql->makePOSTList("dummy", $mysql->getLehrer(), false, false, $ort, $_POST["lehrer"], true);
				$ret .= '<input type="hidden" name="lehrer" value="'.$_POST["lehrer"].'" />';
				$ret .= '<br />';
				$ret .= $mysql->makeBox("klassen[]", $mysql->getKlassen(), $mysql->getKlassen($_POST["lehrer"]));
				$ret .= '<br /><input type="submit" name="apply" value="�bernehmen" />';
				
			} elseif (isset($_POST["lehrer"]) && isset($_POST["klassen"])) {
				$ret .= "<br /> Kekse";
				$mysql->setLehrerKlassen($_POST["lehrer"], $_POST["klassen"]);		
				unset($_POST["lehrer"]);
				unset($_POST["klassen"]);
				header("location: $ort");
			}
			$ret .= '</form>';
		} else {
			$ret .= "Zugriff verweigert!";
		}
		return $ret;
	}
}

$currentSite = new AssignTeacherSite();

?>