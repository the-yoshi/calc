<?php 

class ManagementSite extends Site {

	public function getName () {
		return "management";
	}

	public function anzeigen() {
		if (!isset(ResourceManager::$user))
			Routing::relocate("");
		
		$user = ResourceManager::$user;
		$ret = '';
		if ($user->role == "admin" || $user->role == "lehrer") {
			
			#Das Verwaltungsmen� zum Erstellen (�ndern, L�schen folgen) von Accounts, Klassen und Aufgabenprofilen
			#Wird Aufgerufen �ber die Parameter "Site" und "operation"
			#Diese werden auch an die "decision"-Methode weitergeleitet, die dann die notwendige Datenbankoperation ausf�hrt,
			#falls Formulardaten durch selbstaufruf �bergeben wurden 
			$back = $_SERVER["PHP_SELF"]."?site=verwaltung"; 
			$ort = $_SERVER["PHP_SELF"]."?site=verwaltung&operation="; 
			
			if (!isset($_GET["operation"])) {
			$ret = $ret.'<ul>
				<li><strong>Accountverwaltung</strong>
					<ul>
						<!-- <li><a href="'.$ort.'anzeigeAccount">Accounts anzeigen</a></li> -->
						<li><a href="'.$ort.'neuAccount">Neuer Account</a></li>
						<!-- <li><a href="'.$ort.'bearbeiteAccount">Account bearbeiten</a></li> -->
						<!-- <li><a href="'.$ort.'loescheAccount">Account l�schen</a></li> -->
					</ul>
				</li>
				<li><strong>Klassenverwaltung</strong>
					<ul>
						<li><a href="'.$ort.'neuKlasse">Neue Klasse</a></li>
						<!-- <li><a href="'.$ort.'bearbeiteKlasse">Klasse bearbeiten</a></li> -->
						<!-- <li><a href="'.$ort.'loescheKlasse">Klasse l�schen</a></li> -->
					</ul>
				</li>
				<li><strong>Aufgabenverwaltung</strong>
					<ul>
						<li><a href="'.$ort.'neuAufgabentyp">Neues Aufgabenprofil</a></li>
						<!-- <li><a href="'.$ort.'bearbeiteAufgabentyp">Aufagbenprofil bearbeiten</a></li> -->
						<!-- <li><a href="'.$ort.'loescheAufgabentyp">Aufgabenprofil l�schen</a></li> -->
						<li><a href="'.$ort.'neuSchema">Neue Termvorlage</a></li>
					</ul>
				</li>
			</ul>';
			} else if (isset($_GET["operation"])) {
				$ret = $ret.'<a href="'.$back.'">Zur�ck</a><br />';
				$op = $_GET["operation"];
		
				if (isset($_POST["data"])) {
					#Automatisch richtige DB-operation ausf�hren
					$bool = $mysql->decision($op, $_POST["data"]);
					
					#Konstanten eintragen, falls f�r Termvorlage vorhanden
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
					
					#Eintragen der Klasse des Sch�lers, da diese sich nicht in der Accounttabelle befindet.
					if (isset($_POST["data"]["rolle"]) && $_POST["data"]["rolle"] == "schueler" && $_POST["klasse"] > 0) {
						$id = $mysql->getId();
						$mysql->setSchuelerKlasse($id, $_POST["klasse"]);
					}
					
					if ($bool) {
						unset($_POST["data"]);
						header("location: {$back}");
					} else {
						echo "Fehler bei: $bool";
					}
				} else {
					$ret = $ret.'<form action="'.$ort.$op.'" method="post">';
					
					switch($op): case "neuAccount":
						$ret = $ret.'<LABEL>Username:<INPUT type="text" maxlength="30" name="data[username]" /></LABEL><br />
						<LABEL>Passwort:<INPUT type="text" maxlength="32" name="data[password]" /></LABEL><br />
						<LABEL>Email:<INPUT type="email" maxlength="50" name="data[email]" /></LABEL><br />
						<LABEL>Rolle:
							<SELECT id="rolle" name="data[rolle]" onchange="if(document.getElementById(\'rolle\').value == \'schueler\'){visible(\'klassenliste\');} else {invisible(\'klassenliste\');}">
								<OPTION value="schueler" selected> Sch�ler </OPTION>';
								if ($_SESSION["user"]["rolle"] == "admin") {
									$ret = $ret.'<OPTION value="admin"> Admin </OPTION>
									<OPTION value="lehrer"> Lehrer </OPTION>';
								}
							$ret = $ret.'</SELECT>
						</LABEL>
						<div id="klassenliste"><label>Klasse:'.ViewHelper::makeList("klasse", $mysql->getKlassen(), true).'</label></div>
						<br />
						<LABEL>Vorname:<INPUT type="text" maxlength="30" name="data[vorname]" /></LABEL><br />
						<LABEL>Nachname:<INPUT type="text" maxlength="30" name="data[nachname]" /></LABEL><br />';
						break;
					case "neuKlasse":
						$ret = $ret.'<LABEL>Klassenname:<INPUT type="text" maxlength="20" name="data[bezeichnung]" /></LABEL><br />';
						break;
					case "neuSchema":
						$ret = $ret.'<LABEL>Term:<INPUT type="text" maxlength="20" name="data[termvorlage]" /></LABEL>
						<LABEL>Level:
							<SELECT name="data[level]">
								<OPTION value="1">1</OPTION>';
									for($i=2; $i <= 20; $i++) {
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
							$ret = $ret.'</SELECT>
						</LABEL>';
						break;
					case "neuAufgabentyp":
						$ret = $ret.'<label>Termvorlage:</label>';
						if (isset($_GET["id"])) {
							$ret = $ret.ViewHelper::makeList("data[term]", $mysql->getSchema(), true, true, $ort.$op, $_GET["id"]);
							
							$ret = $ret."<br />Konstanten festlegen (optional):<br />";
							$variable = $mysql->zaehleVariablen($_GET["id"]);
							foreach ($variable as $v) {
								$ret = $ret.'<label>'.$v.':<input type="text" name="konstante['.$v.'][von]" size="3" /></label><label>-';
								$ret = $ret.'<input type="text" name="konstante['.$v.'][bis]" size="3" /></label><br />';
							}
							if (count($variable) > 0) {
								$ret = $ret.'<input type="hidden" name="data[konstanten]" value="true" />';
							} else {
								$ret = $ret.'<input type="hidden" name="data[konstanten]" value="false" />';
							}
						} else {
							$ret = $ret.ViewHelper::makeList("data[term]", $mysql->getSchema(), true, true, $ort.$op);
						}
						$ret = $ret.'<br />
						<LABEL>Zahlenraum:<input type="text" name="data[von]" size="3" /></LABEL><LABEL>-<input type="text" name="data[bis]" size="3" /></LABEL><br />
						<LABEL>Typ:
							<SELECT id="art" name="data[typ]" onchange="if(document.getElementById(\'art\').value == \'schaetzen\'){visible(\'abweichung\');} else {invisible(\'abweichung\');}">
								<OPTION value="schaetzen"> Sch�tzen </OPTION>
								<option value="ausrechnen"> Ausrechnen </option>
								<OPTION value="runden"> Runden </OPTION>
								<OPTION value="vergleichen"> Vergleichen </OPTION>
							</SELECT>
						</LABEL><BR />
						<div id="abweichung"><LABEL>Abweichung:<INPUT type="text" name="data[abweichung]" size="3" /></LABEL>%<br /></div>
						<LABEL>Bezeichnung:<INPUT type="text" name="data[bezeichnung]" /></LABEL><br />';
						break;
					endswitch;
					$ret = $ret.'<input type="hidden" name="data[ersteller]" value="'.$_SESSION["user"]["id"].'" />
					<INPUT type="submit" value="Fertig" />			
					</form>';
				}
			} else {
				$ret=$ret.'Zugriff verweigert!';
			}
	
			return $ret;
		}
	}
}
?>
