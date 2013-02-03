<!DOCTYPE html>
<?php
	# Automatisches Laden der Klassen und starten einer Session
	function __autoload($classname) {
	    $filename = "./Klassen/class". $classname .".php";
	    require_once($filename);
	} #session_start();
?>
<HTML>
	<head>
		<title>Kopfrechnen Online</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />
		<!-- Sichtbarkeit eines Elements umschalten  -->
		<script type="text/javascript">
			<!--
				function visible(element) {
					document.getElementById(element).style.display = '';
				}
				function invisible(element) {
					document.getElementById(element).style.display = 'none';
				}
		//-->
		</script>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<!-- Willkommen zum Kopfrechnen! -->
				<?php /*
				#Horizontale Menüleiste im Header einfügen
					if (isset($_SESSION["user"]["rolle"])) { 
						$headermenu = new Menu($_SERVER["PHP_SELF"], "horizontal", $_SESSION["user"]["rolle"]); echo $headermenu->anzeigen();
					} else {
						$headermenu = new Menu($_SERVER["PHP_SELF"], "horizontal"); echo $headermenu->anzeigen();
					} */ 
				?>
			</div>
			<div id="main">
				<div id="leftmenu">
				#Einbingen des Navigationsmenüs
					<?php include("Menu/navi.php"); ?>
				</div> 
				<div id="content">
					<?php 
					#Einbinden der Seite, die via GET-Variable aufgerufen wird
					#Konfiguration des Menuüs via classMenu
						if (isset($_GET["site"])) {
							$site = $_GET['site'];
							try {
								include("Site/{$site}.php");
							} catch (Exception $e) {
								echo "Seite existiert nicht! $e";
							}
						} else {
							echo "Startseite!";
						}
					?>
				</div>
			</div>
			<div id="footer">
				Bei Kopfschmerzen hilft Medizin ;)
			</div>
		</div>
	</body>
</HTML>

