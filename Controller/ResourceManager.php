<?php

# this holds all global resources
# it further manages:
# - login/session management

class ResourceManager {
	public static $user;
	public static $mysql;
	
	public static $httpRoot;
	
	public static function init() {
		
		ResourceManager::$mysql = new MySQL($MYSQL_USERNAME, $MYSQL_PASSWORD);
		ResourceManager::$httpRoot = $_SERVER["PHP_SELF"];
		
		session_start();
		
			# logout
		if (isset($_GET['logout'])) {
			session_destroy();
			$_GET["site"] = "main";
			unset($_SESSION["user"]);
			
			# already logged in
		} else if (isset($_SESSION["user"])) {
			ResourceManager::$user = $_SESSION["user"];
			
			# login
		} else if (isset($_POST['logindaten']) && !isset($_SESSION["user"])) {
			$logindaten = $_POST['logindaten'];
		
			$login = new Login();
			if ($login->einloggen($logindaten["user"], $logindaten["password"]))
				ResourceManager::$user = $_SESSION["user"];
		} 
		
	}
}

?>