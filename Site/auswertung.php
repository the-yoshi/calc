<?php

if(isset($_POST['nr']) && $_SESSION["nr"] == $_SESSION["anzahl"]) {
	$_SESSION['aufgaben'][] = array($_POST['nr'], $_POST['term'], $_POST['phpergebnis'], $_POST['eingabe'], 0, 0, $_POST['abweichung'], $_POST['vergleich']);
	$_SESSION['nr'] ++;
}

$e = new Auswertung();

$richtig = 0;
$falsch = 0;

foreach ($_SESSION["aufgaben"] as $a) {
	echo "<br />";
	echo "Nr $a[0]: $a[1] = $a[2] <br /> Dein Ergebnis: $a[3] <br /> ";
	$test = $e -> abweichung($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
	#$e -> abweichung($aufgabe, $term, $phpergebnis, $eingabeergebnis, $beginn, $ende, $abweichung, $vergleich)
	echo "<br>";
	
	if ($test[0]) {
		$richtig += 1;
	} else {
		$falsch += 1;
	}
}

$prozent = round(($richtig/($richtig+$falsch))*100,2);

echo "<br> Du hast $prozent% der Aufgaben richtig gel�st!";
echo '<br><form method="post" action="../index.php"><input type="submit" value="Nochmal!"></form>';