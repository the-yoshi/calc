<?php
	/* Daniel:
	 * TODO: templates mit eigenem unterordner einführen (wenn nötig erstmal simple php-dinger)
	 * TODO: alle sites sollten die sites-klasse implementieren
	 * TODO: HTML rausnehmen, ÜBERALL, aber besonders in MySQL-Klasse
	 * 
	 */
	# Automatisches Laden der Klassen und starten einer Session
	
	
	$calc_mainpage = "Site/main.php";
	
	$calc_header = "";
	$calc_menu = "";
	$calc_content = "";
	$calc_footer = "";
	
	function __autoload($classname) {
	    $filename = "./Klassen/class". $classname .".php";
	    require_once($filename);
	}
	
	
	session_start();
	
	if (isset($_POST['logindaten']) && !isset($_SESSION["user"])) {
		$logindaten = $_POST['logindaten'];
	
		$login = new Login("crud", "rw");
		$login->einloggen($logindaten["user"], $logindaten["password"]);
		
	# Logoutvorgang
	} elseif (isset($_GET['logout'])) {
		session_destroy();
		$_GET["site"] = "main";
		unset($_SESSION["user"]);
		# Wenn eingeloggt
	} 
	
	if (isset($_SESSION["user"])) {
	
		$calc_menu = $calc_menu.'MoinMoin ';
		if (isset($_SESSION["user"]["vorname"])) {
			$calc_menu = $calc_menu.$_SESSION["user"]["vorname"] . " <br />alias ";
		}
		$calc_menu = $calc_menu.'<a href="'.$_SERVER["PHP_SELF"].'?site=eigeneraccount">'.$_SESSION["user"]["username"].'</a><br />';
	}
	else {
		$calc_menu = $calc_menu = $calc_menu.Login::anzeigen();
	}
	
	// TODO: escapen
	if (isset($_GET["site"]) && $_GET["site"] != "main" && $_GET["site"] != "" && file_exists("Site/".$_GET["site"].".php")) {
		$site = $_GET['site'];
		try {
			include("Site/{$site}.php");
			$calc_content = $currentSite->anzeigen(); // $currentSite muss in jeder Site-Klassendatei gesetzt werden. Kleiner Hack ;)
		} catch (Exception $e) {
			echo "Seite existiert nicht! $e";
		}
	}
	
	
	if (isset($_SESSION["user"]["rolle"]))
		$menu = new Menu($_SERVER["PHP_SELF"], "vertikal", $_SESSION["user"]["rolle"]);
	else
		$menu = new Menu($_SERVER["PHP_SELF"], "vertikal");
	
	$calc_menu = $calc_menu.$menu->anzeigen();
	$calc_footer = "Bei Kopfschmerzen Wasser trinken!";
	
	include $calc_mainpage;
?>
