<?php if (isset($_SESSION["user"]) && ($_SESSION["user"]["rolle"] == "admin" || $_SESSION["user"]["rolle"] == "lehrer")): ?>
<?php $ort = $_SERVER["PHP_SELF"].'?site=zuteilung'; $mysql = new MySQL(); $ersteller = $_SESSION["user"]["id"];?>



	<label> Modus:
	<?php
		if (!isset($_GET["id"])) {
			$_GET["id"] = "vorgabe";
		}
		echo $mysql->makeList("modus", array(array("vorgabe","Übungsmodus"),array("klausur", "Klausurmodus")), false, true, $ort, $_GET["id"]);
		$modus = $_GET["id"]; 
	?>
	</label>
	
	<br />
	
	<form name="one" action="<?php echo "$ort&id=$modus"; ?>" method="POST">
	
		<label>Name: <input type="text" name="bezeichnung" <?php if(isset($_POST["bezeichnung"])) {echo 'value="'.$_POST["bezeichnung"].'"';} ?>/></label><br/>
		<input type="hidden" name="ersteller" value="<?php echo $ersteller; ?>" />
		<label>Anzahl: <input type="text" name="anzahl" size="3" <?php if(isset($_POST["anzahl"])) {echo 'value="'.$_POST["anzahl"].'"';} ?>/></label><br/>
		
		<table>
			<tr>
				<td>
					<?php
						if (!isset($_POST["klassen"]) && !isset($_POST["schueler"])) {
							echo '<label>Klasse(n): '.$mysql->makeBox("klassen[]", $mysql->getKlassen($ersteller)).'</label><br />';
							
						} elseif (isset($_POST["klassen"]) && !isset($_POST["schueler"])) { 
							echo '<label>Klasse(n): '.$mysql->makeBox("klassen[]", $mysql->getKlassen($ersteller), $_POST["klassen"]).'</label><br />';
							echo '<label>Schüler: '.$mysql->makeBox("schueler[]", $mysql->getSchueler($_POST["klassen"])).'</label><br />';
						
						} elseif (isset($_POST["schueler"]) && isset($_POST["schueler"])) {
							echo '<label>Klasse(n): '.$mysql->makeBox("klassen[]", $mysql->getKlassen($ersteller), $_POST["klassen"]).'</label><br />';
							echo '<label>Schüler: '.$mysql->makeBox("schueler[]", $mysql->getSchueler($_POST["klassen"]), $_POST["schueler"]).'</label><br />';
						}
						
					?>
				<td>
				<td>
					<?php
						if (!isset($_POST["profile"]) && !isset($_POST["auswahl"])) {
							echo '<label>Profile: '.$mysql->makeBox("profile[]", $mysql->getAufgaben($ersteller)).'</label><br />';
							
						} elseif (isset($_POST["profile"]) && !isset($_POST["auswahl"])) {
							echo '<label>Profile: '.$mysql->makeBox("profile[]", $mysql->getAufgaben($ersteller), $_POST["profile"]).'</label><br />';
							
						}
					
					?>
				</td>
			</tr>
		</table>
		<br />
		
		<?php 
			if(isset($_POST["profile"]) && isset($_POST["schueler"])) {
				echo '<input type="hidden" value="indb" />'; 
				/*Im Kopf Methode aufrufen, die in die Datenbank einträgt und dann nur Profile
				 * und Schüler zurücksetzt, den rest der Auswahl aber beibehält!
				 * 
				 */
				echo '<input type="submit" value="Aufgabenprofile zuweisen" />';
			} else {
				echo '<input type="submit" value="Filtern" />';
			}
		
		?>
		
	</form>	











<?php else: ?>
	Zugriff verweigert!
<?php endif; ?>