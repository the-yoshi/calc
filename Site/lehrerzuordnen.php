<?php
#Da Lehrer mehrere Klassen unterrichten können wird hier die Zuordnung vorgenommen.
#Je nachdem, was ausgesucht wurde, wird die Seite neu generiert mit den neuen Parametern.
#Für die Formularelemente werden Methoden benutzt 
if (isset($_SESSION["user"]) && $_SESSION["user"]["rolle"] == "admin") {

	$ort = $_SERVER["PHP_SELF"]."?site=lehrerzuordnen";  
	$mysql = new MySQL(); 

	echo '<form action="'.$ort.'" method="post">';
	if (!isset($_POST["lehrer"]) && !isset($_POST["klassen"])) {
		echo $mysql->makePOSTList("lehrer", $mysql->getLehrer(), true, true, $ort);	
		echo '<br />';
		
	} elseif (isset($_POST["lehrer"]) && !isset($_POST["klassen"])) {
		echo $mysql->makePOSTList("dummy", $mysql->getLehrer(), false, false, $ort, $_POST["lehrer"], true);
		echo '<input type="hidden" name="lehrer" value="'.$_POST["lehrer"].'" />';
		echo '<br />';
		echo $mysql->makeBox("klassen[]", $mysql->getKlassen(), $mysql->getKlassen($_POST["lehrer"]));
		echo '<br /><input type="submit" name="apply" value="Übernehmen" />';
		
	} elseif (isset($_POST["lehrer"]) && isset($_POST["klassen"])) {
		echo "<br /> Kekse";
		$mysql->setLehrerKlassen($_POST["lehrer"], $_POST["klassen"]);		
		unset($_POST["lehrer"]);
		unset($_POST["klassen"]);
		header("location: $ort");
	}
	echo '</form>';	
	
	
	
} else {
	echo "Zugriff verweigert!";
}
?>