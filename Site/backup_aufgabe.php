<?php
$_SESSION["nr"] += 1;
$nr = $_SESSION["nr"];
$anzahl = $_SESSION["anzahl"];

if(isset($_POST['nr'])) {
	$_SESSION['aufgaben'][] = array($_POST['nr'], $_POST['term'], $_POST['phpergebnis'], $_POST['eingabe'], 0, 0, $_POST['abweichung'], $_POST['vergleich']);
}


echo "<br />Aufgabe Nr: " . $_SESSION["nr"] . "/" . $_SESSION['anzahl'] . "<br />";

$typ = $_SESSION["klassen"][mt_rand(0, count($_SESSION['klassen'])-1)];

$eingabe = "";

if ($nr<$anzahl) {
	echo '<form action="'. $_SERVER['PHP_SELF'] . '" method="post">';
} else {
	echo '<form action="auswertung.php" method="post">';
}

switch ($typ) {
	case "vergleich":
		$term = new Vergleich($_SESSION["von"], $_SESSION["bis"], $_SESSION["kommazahlen"], $_SESSION["operatoren"], $_SESSION["schemata"], $_SESSION["konstanten"]);
		#$term->vergleiche();
		
		$eingabe = '<input type="submit" value="Richtig" name="eingabe" /><input type="submit" value="Falsch" name="eingabe" />';
		
		echo '<input type="hidden" name="vergleich" value="true">';
		echo '<input type="hidden" name="abweichung" value="0">';
		
		break;
	case "term":
		$term = new Term($_SESSION["von"], $_SESSION["bis"], $_SESSION["kommazahlen"], $_SESSION["operatoren"], $_SESSION["schemata"], $_SESSION["konstanten"]);
		
		$eingabe = '<input type="text" name="eingabe" /><input type="submit" value="Weiter" />';
		
		echo '<input type="hidden" name="vergleich" value="false">';
		echo '<input type="hidden" name="abweichung" value="0">';
		
		break;
	case "runden":
		$term = new Runden($_SESSION["von"], $_SESSION["bis"], $_SESSION["kommazahlen"]);
		
		$eingabe = '<input type="text" name="eingabe" /><input type="submit" value="Weiter" />';
		
		echo '<input type="hidden" name="vergleich" value="false">';
		echo '<input type="hidden" name="abweichung" value="0">';
		
		break;
	case "schaetzwert":
		$term = new Schaetzwert($_SESSION["von"], $_SESSION["bis"], $_SESSION["kommazahlen"], $_SESSION["operatoren"], $_SESSION["schemata"], $_SESSION["konstanten"], $_SESSION['abweichung']);
		
		$eingabe = '<input type="text" name="eingabe" /><input type="submit" value="Weiter" />';
		
		echo '<input type="hidden" name="vergleich" value="false">';
		echo '<input type="hidden" name="abweichung" value="'.$term->getAbweichung().'">';
		
	 	break;
}

echo $term->getA() . "<br />";
echo $term->getT();

echo $eingabe;
echo '<input type="hidden" name="nr" value="'.$nr.'">';
echo '<input type="hidden" name="term" value="'.$term->getT().'">';
echo '<input type="hidden" name="phpergebnis" value="'. $term->getE() .'">';

echo '</form>';










