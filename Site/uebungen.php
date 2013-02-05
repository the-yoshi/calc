<?php if (isset($_SESSION["user"]) && ($_SESSION["user"]["rolle"] == "admin" || $_SESSION["user"]["rolle"] == "lehrer")): ?>
<?php
	#Neue �bungen anlegen, oder zu einer vorhandenen �bung zum Bearbeiten springen 
	$ziel = $_SERVER["PHP_SELF"].'?site=zuteilung'; 
	$mysql = new MySQL(); 
	$lehrerid = $_SESSION["user"]["id"];

?>

<strong>Neue �bung:</strong>
<br />
<form name="neu" action="<?php echo $ort; ?>" method="POST">
	<label>
		Name:
		<input type="text" name="bezeichnung" />
	</label>
	<br />
	<input type="hidden" name="ersteller" value="<?php echo $lehrerid; ?>" />
	<label>
		Modus:
		<select name="modus">
			<option value="vorgabe" > �bungsmodus</option>
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
<strong>�bersicht</strong>
<?php echo $mysql->makeTaskList($lehrerid, $ziel); ?>




<?php else: ?>
	Zugriff verweigert!
<?php endif; ?>