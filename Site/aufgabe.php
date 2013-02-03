<?php
$mysql = new MySQL();
$account = $_SESSION["user"]["id"];
$uebung = $_POST["uebung"];
$modus = $_POST["modus"];
$anzahl = $_POST["anzahl"];
$ort = $_SERVER["PHP_SELF"].'?site=aufgabe';

#Eintragen des alten ergebnisses und falls letzte aufgabe weiterleiten an auswertung (folgt)
if (isset($_POST["ergebnis"])) {
	
	$mysql->setErgebnis($_POST["historie"], $_POST["ergebnis"]);
	
}

#Anzahl übriger Aufgaben in dieser Übung für den Schüler
$nr = $mysql->getCountAufgaben($account, $uebung);
$nr = $nr[0][0];

?>
<?php if($nr > 0) :?>
	<?php 
		
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
		
		echo "Nr " . (($anzahl-$nr)+1) . ": <br />";
	?>
	#Formular mit der Aufgabe
	<form action="<?php echo $ort; ?>" method="post">
		<label>
			<?php echo $aufgabe["beschreibung"] . " " . $aufgabe["rechnung"];?>	
			<?php if ($aufgabe["typ"] == "vergleichen"): ?>
				<input type="submit" name="ergebnis" value="richtig" />
				<input type="submit" name="ergebnis" value="falsch" />
			<?php else:?>
				= <input type="text" name="ergebnis" size="5" />
				<input type="submit" value="OK" />
			<?php endif;?>
		</label>	
		<input type="hidden" name="historie" value="<?php echo $aufgabe["id"]; ?>" />
		<input type="hidden" name="modus" value="<?php echo $modus;?>" />
		<input type="hidden" name="anzahl" value="<?php echo $anzahl; ?>" />
		<input type="hidden" name="uebung" value="<?php echo $uebung; ?>" />
	</form>
<?php else: ?>
	<?php
		$ziel = $_SERVER["PHP_SELF"]."site=auswertung"; 
		header("location: $ziel");
	?>
<?php endif; ?>