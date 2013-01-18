<?php if (isset($_SESSION["user"]) && ($_SESSION["user"]["rolle"] == "admin" || $_SESSION["user"]["rolle"] == "lehrer")): ?>
<?php $back = $_SERVER["PHP_SELF"]."?site=klassenmanager"; $ort = $_SERVER["PHP_SELF"]."?site=klassenmanager&operation="; $mysql = new MySQL(); ?>
	
	<?php if (!isset($_GET["operation"])): ?>
	<ul>
		<li><strong>Accountverwaltung</strong></li>
		<li><a href="<?php echo $ort; ?>anzeigeAccount">Accounts anzeigen</a></li>
		<li><a href="<?php echo $ort; ?>neuAccount">Neuer Account</a></li>
		<li><a href="<?php echo $ort; ?>bearbeiteAccount">Account bearbeiten</a></li>
		<li><a href="<?php echo $ort; ?>loescheAccount">Account löschen</a></li>
		<li><strong>Klassenverwaltung</strong></li>
		<li><a href="<?php echo $ort; ?>neuKlasse">Neue Klasse</a></li>
		<li><a href="<?php echo $ort; ?>bearbeiteKlasse">Klasse bearbeiten</a></li>
		<li><a href="<?php echo $ort; ?>loescheKlasse">Klasse löschen</a></li>
		<li><strong>Aufgabenverwaltung</strong></li>
		<li><a href="<?php echo $ort; ?>neuAufgabentyp">Neuer Aufgabentyp</a>
		<li><a href="<?php echo $ort; ?>bearbeiteAufgabentyp">Aufagbentyp bearbeiten</a></li>
		<li><a href="<?php echo $ort; ?>loescheAufgabentyp">Aufgabentyp löschen</a></li>
		<li><a href="<?php echo $ort; ?>neuSchema">Neue Termvorlage</a></li>
	</ul>

	<?php elseif (isset($_GET["operation"])): ?>
		<a href="<?php echo $back; ?>">Zurück</a><br />
		<?php $op = $_GET["operation"]; ?>
		
		<?php if (isset($_POST["data"])):?>
				<?php 
					$bool = $mysql->decision($op, $_POST["data"]);
					if ($bool) {
						echo $bool;
						unset($_POST["data"]);
						#header("location: {$back}");
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
					<SELECT name="data[rolle]">
						<OPTION value="schueler" selected> Schüler </OPTION>
						<?php if ($_SESSION["user"]["rolle"] == "admin"): ?>
							<OPTION value="admin"> Admin </OPTION>
							<OPTION value="lehrer"> Lehrer </OPTION>
						<?php endif;?>
					</SELECT>
				</LABEL><br />
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
				<label>Termvorlage:
					<?php echo $mysql->makeList("data[term]", $mysql->getSchema()); ?>
				</label><br />
				<?php /* 
					if (isset($_POST["term"]) && $_POST["term"] > 0) {
						echo "<strong>Konstanten festlegen (optional):</strong><br />";
						$variable = $mysql->zaehleVariablen($_POST["term"]);
						foreach ($variable as $v) {
							echo '<label>'.$v.':<input type="text" name="konstante['.$v.'][von]" size="3" /></label><label>-';
							echo '<input type="text" name="konstante['.$v.'][bis]" size="3" /></label><br />';
						}
						if (count($variable) > 0) {
							echo '<input type="hidden" name="data[konstanten]" value="true" />';
						}	
					}
				*/?>
				<LABEL>Typ:
					<SELECT name="data[typ]">
						<option value="ausrechnen"> Term ausrechnen </option>
						<OPTION value="runden"> Runden </OPTION>
						<OPTION value="schaetzen"> Schätzen </OPTION>
						<OPTION value="vergleichen"> Vergleich (W/F) </OPTION>
					</SELECT>
				</LABEL><BR />
				<LABEL>Abweichung (optional):<INPUT type="text" name="data[abweichung]" /></LABEL>%<br />
				<LABEL>Bezeichnung:<INPUT type="text" name="data[bezeichnung]" /></LABEL><br />
			<?php break;?>
				
			<?php endswitch; ?>
			<INPUT type="submit" value="Fertig" />			
			</form>	
		<?php endif; ?>
	<?php endif;?>
<?php else: ?>
	Zugriff verweigert!
<?php endif; ?>









