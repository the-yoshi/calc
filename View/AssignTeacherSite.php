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
		if (isset(ResourceManager::$user) && ResourceManager::$user->role == "admin") {
		
			$ort = $_SERVER["PHP_SELF"]."?site=klassenverwaltung";  
			
			$ret .= '<form action="'.$ort.'" method="post">';
			if (!isset($_POST["lehrer"]) && !isset($_POST["klassen"])) {
				$ret .= '<br />';
				
			} elseif (isset($_POST["lehrer"]) && !isset($_POST["klassen"])) {
				$ret .= '<input type="hidden" name="lehrer" value="'.$_POST["lehrer"].'" />';
				$ret .= '<br />';
				$ret .= '<br /><input type="submit" name="apply" value="�bernehmen" />';
				
			} elseif (isset($_POST["lehrer"]) && isset($_POST["klassen"])) {
				$ret .= "<br /> Kekse";	
				unset($_POST["lehrer"]);
				unset($_POST["klassen"]);
			}
			$ret .= '</form>';
		} else {
			$ret .= "Zugriff verweigert!";
		}
		return $ret;
	}
}
?>