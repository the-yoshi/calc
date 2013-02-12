<?php if (isset($_SESSION["user"]) && ($_SESSION["user"]["rolle"] == "admin" || $_SESSION["user"]["rolle"] == "lehrer")): ?>
<?php 
	#Operationen f�r die einzelnen �bungen. Erstellte �bungen k�nnen hier bearbeitet werden bzw erhalten erst ihren Inhalt.
	#Der Name zuteilung.php kommt daher, da hier die einzelnen Klassen und Aufgabenprofile der �bung zugeordnet werden 
	$ort = $_SERVER["PHP_SELF"].'?site=zuteilung'; 
	$target = $_SERVER["PHP_SELF"]."?site=uebungen"; 
	$ersteller = $_SESSION["user"]["id"];
	$mysql = new MySQL();
?>

	<?php switch ($_REQUEST["aktion"]): case "Anlegen": ?>
		<?php 
			$mysql->setUebung($_POST["bezeichnung"], $_POST["ersteller"],$_POST["modus"], $_POST["anzahl"], $_POST["aktiv"]);
			$link = $ort."&id=".$mysql->getId()."&aktion=Bearbeiten";
			header("location: $link");
		?>
	<?php break; ?>
	
	<?php case "Aktivieren": ?>
		<?php 
			$mysql->setAktiv($_GET["id"]);
			header("location: $target");
		?>
	<?php break;?>
	
	<?php case "Deaktivieren": ?>
		<?php 
			$mysql->setInaktiv($_GET["id"]);
			header("location: $target");
		?>
	<?php break;?>
	
	<?php case "Loeschen": ?>
		<?php 
			$mysql->deleteUebung($_GET["id"]);
			header("location: $target");
		?>
	<?php break;?>
	
	<?php case "Updaten": ?>
		<?php 
			$mysql->updateUebung($_GET["id"], $_POST["bezeichnung"], $_POST["anzahl"], $_POST["modus"]);
			$link = $ort."&id=".$_GET["id"]."&aktion=Bearbeiten";
			header("location: $link");
		?>	
	<?php break; ?>
		
	<?php case "Uebernehmen": ?>	
		<?php 
			$uebung = $_GET["id"];
			$profile = $_POST["profile"];
			$accounts = $_POST["schueler"];
			
			$mysql->resetUebungen($uebung);
			$data = $mysql->getUebung($uebung); 
			$modus = $data[0]["modus"];
			$anzahl = $data[0]["anzahl"];
			
			$aufgaben = array();
			for ($i = 1; $i <= $anzahl; $i++) {
				$profil = $profile[mt_rand(0, count($profile)-1)];		
				$parameter = $mysql->getParameter($profil);
				$konstanten = $mysql->getKonstanten($profil);
				#if (isset($konstanten[0])){$konstanten = $konstanten[0];}
				$parameter = $parameter[0];
				
				#echo $i. ": "; var_dump($parameter); echo "<br />";
				#echo $i. ": "; var_dump($konstanten); echo "<br />";
				
				#Falls Klausurmodus aktiviert, werden allen die gleichen Aufgaben zugewiesen, ansonsten nur per zufallsprinzip
				#die Termvorlagen eingetragen
				if ($modus == "klausur") {
					switch ($parameter["typ"]) {
						case "ausrechnen":
							$rechnung = new Term($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten);
							break;
						case "runden":
							$rechnung = new Runden($parameter["von"], $parameter["bis"], false);
							break;
							
						case "schaetzen":
							$rechnung = new Schaetzwert($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten, $parameter["abweichung"]);
							break;
							
						case "vergleichen":
							$rechnung = new Vergleich($parameter["von"], $parameter["bis"], false, array(), $parameter["termvorlage"], $konstanten);
							break;		
					}				
									
					foreach ($accounts as $a) {
						$mysql->setHistorieKlausur($uebung, $profil, $a, $rechnung->getT(), $rechnung->getE(), $rechnung->getA());	
					}
				} else {
					foreach ($accounts as $a) {
						$mysql->setHistorieVorgabe($uebung, $profil, $a);
					}
				}
			}
			
			$link = $ort."&id=".$_GET["id"]."&aktion=Bearbeiten";
			header("location: $link");
		?>
	<?php break; ?>
	
	<?php case "Bearbeiten": ?>
		<?php $data = $mysql->getUebung($_GET["id"]); $data = $data[0]; ?>
		<table><tr>
		<td colspan="2">
			<form name="one" action="<?php $link = $ort."&id=".$_GET["id"]."&aktion=Bearbeiten"; echo "$link"; ?>" method="POST">
				<label>Name: <input type="text" name="bezeichnung" value="<?php echo $data["bezeichnung"]; ?>" /></label>
				<label>Modus:<select name="modus">
					<option value="vorgabe" <?php if ($data["modus"] == "vorgabe") {echo "selected";}?>> �bungsmodus </option>
					<option value="klausur" <?php if ($data["modus"] == "klausur") {echo "selected";}?>> Klausurmodus </option>
				</select></label>		
				<label>Anzahl: <input type="text" name="anzahl" size="3" value="<?php echo $data["anzahl"]; ?>" /></label>
				<input type="hidden" name="aktion" value="Updaten" />
				<input type="submit" value="�ndern" />
			</form>
		</td></tr><tr><td>
			<form name="two" action="<?php $link = $ort."&id=".$_GET["id"]; echo "$link"; ?>" method="POST">
				<?php
					if (!isset($_POST["klassen"]) && !isset($_POST["schueler"])) {
						echo '<label>Klasse(n): '.ViewHelper::makeBox("klassen[]", $mysql->getKlassen($ersteller)).'</label><br />';
						
					} elseif (isset($_POST["klassen"]) && !isset($_POST["schueler"])) { 
						echo '<label>Klasse(n): '.ViewHelper::makeBox("klassen[]", $mysql->getKlassen($ersteller), $_POST["klassen"]).'</label><br />';
						echo '<label>Sch�ler: '.ViewHelper::makeBox("schueler[]", $mysql->getSchueler($_POST["klassen"])).'</label><br />';
					
					} elseif (isset($_POST["schueler"]) && isset($_POST["schueler"])) {
						echo '<label>Klasse(n): '.ViewHelper::makeBox("klassen[]", $mysql->getKlassen($ersteller), $_POST["klassen"]).'</label><br />';
						echo '<label>Sch�ler: '.ViewHelper::makeBox("schueler[]", $mysql->getSchueler($_POST["klassen"]), $_POST["schueler"]).'</label><br />';
					}
	
					if (!isset($_POST["profile"]) && !isset($_POST["auswahl"])) {
						echo '<label>Profile: '.ViewHelper::makeBox("profile[]", $mysql->getAufgaben($ersteller)).'</label><br />';
							
					} elseif (isset($_POST["profile"]) && !isset($_POST["auswahl"])) {
						echo '<label>Profile: '.ViewHelper::makeBox("profile[]", $mysql->getAufgaben($ersteller), $_POST["profile"]).'</label><br />';
								
					}
						
					if (isset($_GET["id"]) && isset($_POST["profile"]) && isset($_POST["schueler"])) {
						echo '<input type="hidden" name="aktion" value="Uebernehmen" />';
						echo '<input type="submit" value="�bernehmen" />';
					} else {
						echo '<input type="hidden" name="aktion" value="Bearbeiten" />';
						echo '<input type="submit" value="Filtern" />';
					}
			
				?>
			</form>
		</td>
		<td>
			<?php 
				echo $mysql->makeCountList($_GET["id"]);
			?>
		</td>	
		</tr></table>	
	<?php break; ?>
	
	<?php endswitch; ?>
<?php else: ?>
	Zugriff verweigert!
<?php endif; ?>