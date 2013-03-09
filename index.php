<?php
	/* Daniel:
	 * TODO: templates mit eigenem unterordner einführen (wenn nötig erstmal simple php-dinger)
	 * TODO: alle sites sollten die sites-klasse implementieren
	 * TODO: HTML rausnehmen, ÜBERALL, aber besonders in MySQL-Klasse
	 * 
	 */
	# Automatisches Laden der Klassen und starten einer Session
	
	
	$calc_mainpage = "View/_mainTemplate.php";
	
	$calc_header = "";
	$calc_menu = "";
	$calc_content = "";
	$calc_footer = "";
	
	function __autoload($classname) {
		$filename = "./Model/$classname".".php";
		if (!file_exists($filename))
			$filename = "./Model/Storables/$classname".".php";
		if (!file_exists($filename))
			$filename = "./View/$classname".".php";
		if (!file_exists($filename))
			$filename = "./Controller/$classname".".php";
	    require_once($filename);
	}
	
	StorageManager::init();
	ResourceManager::init();
	
	$menu = new Menu("vertikal");
	$calc_menu .= $menu->anzeigen();
	if (!Login::isLoggedIn())
		$calc_menu .= ViewHelper::showLogin();
	
	// TODO: escapen
	if (isset($_GET["site"])) {
		$route = $_GET['site'];
		try {
			$site = Routing::get($route);
			$calc_content .= $site->anzeigen();
		} catch (Exception $e) {
			$calc_content .= "Seite existiert nicht! $e";
		}
	}
	else
		Routing::relocate("home");
	
	$calc_footer = "";
	
	include $calc_mainpage;
?>
