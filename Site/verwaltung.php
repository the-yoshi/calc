<?php if (isset($_SESSION["user"]) && ($_SESSION["user"]["rolle"] == "admin" || $_SESSION["user"]["rolle"] == "lehrer")): ?>
<?php
	#Das Verwaltungsmenü zum Erstellen (Ändern, Löschen folgen) von Accounts, Klassen und Aufgabenprofilen
	#Wird Aufgerufen über die Parameter "Site" und "operation"
	#Diese werden auch an die "decision"-Methode weitergeleitet, die dann die notwendige Datenbankoperation ausführt,
	#falls Formulardaten durch selbstaufruf übergeben wurden 
	$back = $_SERVER["PHP_SELF"]."?site=verwaltung"; 
	$ort = $_SERVER["PHP_SELF"]."?site=verwaltung&operation="; 
	$mysql = new MySQL(); 
?>	
	<?php if (!isset($_GET["operation"])): ?>
	<ul>
		<li><strong>Accountverwaltung</strong></li>
		<!-- <li><a href="<?php echo $ort; ?>anzeigeAccount">Accounts anzeigen</a></li> -->
		<li><a href="<?php echo $ort; ?>neuAccount">Neuer Account</a></li>
		<!-- <li><a href="<?php echo $ort; ?>bearbeiteAccount">Account bearbeiten</a></li> -->
		<!-- <li><a href="<?php echo $ort; ?>loescheAccount">Account löschen</a></li> -->
		<li><strong>Klassenverwaltung</strong></li>
		<li><a href="<?php echo $ort; ?>neuKlasse">Neue Klasse</a></li>
		<!-- <li><a href="<?php echo $ort; ?>bearbeiteKlasse">Klasse bearbeiten</a></li> -->
		<!-- <li><a href="<?php echo $ort; ?>loescheKlasse">Klasse löschen</a></li> -->
		<li><strong>Aufgabenverwaltung</strong></li>
		<li><a href="<?php echo $ort; ?>neuAufgabentyp">Neues Aufgabenprofil</a>
		<!-- <li><a href="<?php echo $ort; ?>bearbeiteAufgabentyp">Aufagbenprofil bearbeiten</a></li> -->
		<!-- <li><a href="<?php echo $ort; ?>loescheAufgabentyp">Aufgabenprofil löschen</a></li> -->
		<li><a href="<?php echo $ort; ?>neuSchema">Neue Termvorlage</a></li>
	</ul>

	<?php elseif (isset($_GET["operation"])): ?>
		<a href="<?php echo $back; ?>">Zurück</a><br />
		<?php $op = $_GET["operation"]; ?>
		
		<?php if (isset($_POST["data"])):?>
				<?php 
					#Automatisch richtige DB-operation ausführen
					$bool = $mysql->decision($op, $_POST["data"]);
					
					#Konstanten eintragen, falls für Termvorlage vorhanden
					if (isset($_POST["data"]["konstanten"]) && $_POST["data"]["konstanten"] == true) {
						$konst = $_POST["konstante"];
						$id_aufgabe = $mysql->getId();
						
						foreach ($konst as $k=>$key) {					
							if (isset($konst[$k]["bis"])) {
								$mysql->setKonstante($k, $konst[$k]["von"], $konst[$k]["bis"]);
							} else {
								$mysql->setKonstante($k, $konst[$k]["von"]);
							}
							$id_konstante = $mysql->getId();
							$mysql->setKonstAufg($id_aufgabe, $id_konstante);
						}
						#Aufgabenkonstanten in DB eintragen!
					}
					
					#Eintragen der Klasse des Schülers, da diese sich nicht in der Accounttabelle befindet.
					if (isset($_POST["data"]["rolle"]) && $_POST["data"]["rolle"] == "schueler" && $_POST["klasse"] > 0) {
						$id = $mysql->getId();
						$mysql->setSchuelerKlasse($id, $_POST["klasse"]);
					}
					
					if ($bool) {
						#echo $bool;
						unset($_POST["data"]);
						header("location: {$back}");
					} else {
						echo "Fehler bei: $bool";
					}				
				?>
		<?php else:?>
			<form action="<?php echo $ort.$op; ?>" method="post">	
			<?php switch($op): case "neuAccount": ?>
			
				<LABEL>Username:<INPUT type="text" maxlength="30" name="data[username]" /></LABEL><br />
				<LABEL>Passwort:<INPUT type="text" maxlength="32" name="data[password]" /></LABEL><br />
				<LABEL>Email:<INPUT type="email" maxlength="50" name="data[email]" /></LABEL><br />
				<LABEL>Rolle:
					<SELECT id="rolle" name="data[rolle]" onchange="if(document.getElementById('rolle').value == 'schueler'){visible('klassenliste');} else {invisible('klassenliste');}">
						<OPTION value="schueler" selected> Schüler </OPTION>
						<?php if ($_SESSION["user"]["rolle"] == "admin"): ?>
							<OPTION value="admin"> Admin </OPTION>
							<OPTION value="lehrer"> Lehrer </OPTION>
						<?php endif;?>
					</SELECT>
				</LABEL>
				<div id="klassenliste"><label>Klasse:<?php echo $mysql->makeList("klasse", $mysql->getKlassen(), true); ?></label></div>
				<br />
				<LABEL>Vorname:<INPUT type="text" maxlength="30" name="data[vorname]" /></LABEL><br />
				<LABEL>Nachname:<INPUT type="text" maxlength="30" name="data[nachname]" /></LABEL><br />
			<?php break; ?>
			<?php case "neuKlasse":?>
				<LABEL>Klassenname:<INPUT type="text" maxlength="20" name="data[bezeichnung]" /></LABEL><br />
			<?php break; ?>
			<?php case "neuSchema":?>
				<LABEL>Term:<INPUT type="text" maxlength="20" name="data[termvorlage]" /></LABEL>
				<LABEL>Level:
					<SELECT name="data[level]">
						<OPTION value="1">1</OPTION>
						<?php 
							for($i=2; $i <= 20; $i++) {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
					</SELECT>
				</LABEL>
			<?php break;?>
			<?php case "neuAufgabentyp":?>
				<label>Termvorlage:</label>
				<?php  
					if (isset($_GET["id"])) {
						echo $mysql->makeList("data[term]", $mysql->getSchema(), true, true, $ort.$op, $_GET["id"]);
						
						echo "<br />Konstanten festlegen (optional):<br />";
						$variable = $mysql->zaehleVariablen($_GET["id"]);
						foreach ($variable as $v) {
							echo '<label>'.$v.':<input type="text" name="konstante['.$v.'][von]" size="3" /></label><label>-';
							echo '<input type="text" name="konstante['.$v.'][bis]" size="3" /></label><br />';
						}
						if (count($variable) > 0) {
							echo '<input type="hidden" name="data[konstanten]" value="true" />';
						} else {
							echo '<input type="hidden" name="data[konstanten]" value="false" />';
						}
					} else {
						echo $mysql->makeList("data[term]", $mysql->getSchema(), true, true, $ort.$op);
					}
				?>
				<br />
				<LABEL>Zahlenraum:<input type="text" name="data[von]" size="3" /></LABEL><LABEL>-<input type="text" name="data[bis]" size="3" /></LABEL><br />
				<LABEL>Typ:
					<SELECT id="art" name="data[typ]" onchange="if(document.getElementById('art').value == 'schaetzen'){visible('abweichung');} else {invisible('abweichung');}">
						<OPTION value="schaetzen"> Schätzen </OPTION>
						<option value="ausrechnen"> Ausrechnen </option>
						<OPTION value="runden"> Runden </OPTION>
						<OPTION value="vergleichen"> Vergleichen </OPTION>
					</SELECT>
				</LABEL><BR />
				<div id="abweichung"><LABEL>Abweichung:<INPUT type="text" name="data[abweichung]" size="3" /></LABEL>%<br /></div>
				<LABEL>Bezeichnung:<INPUT type="text" name="data[bezeichnung]" /></LABEL><br />
			<?php break;?>
				
			<?php endswitch; ?>
			<input type="hidden" name="data[ersteller]" value="<?php echo $_SESSION["user"]["id"]; ?>" />
			<INPUT type="submit" value="Fertig" />			
			</form>	
		<?php endif; ?>
	<?php endif;?>
<?php else: ?>
	Zugriff verweigert!
<?php endif; ?>









