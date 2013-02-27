<?php

class Routing {
	
	private static $routes;
	private static $paths;
	
	# holds all the routes for the website (should later go into central config file)
	private static function init() {
		Routing::$routes = array();
		Routing::$paths = array();
		
		# home page
		Routing::addRoute("",					"TaskSite");
		
		Routing::addRoute("aufgabe",			"TaskSite");
		Routing::addRoute("statistik",			"StatisticsSite");
		Routing::addRoute("aufgabenliste", 		"TaskListSite");
		Routing::addRoute("eigeneraccount",		"AccountSite");
		Routing::addRoute("verwaltung", 		"ManagementSite");
		Routing::addRoute("aufgabenverwaltung",	"ExamsSite");
		Routing::addRoute("neueaufgabe",		"NewAssignmentSite");
		Routing::addRoute("setzevariablen",		"SetVariablesSite");
	}
	
	# adds a route 
	private static function addRoute($route, $site) {
		Routing::$routes[$route] = $site;
		Routing::$paths[$site] = $route;
	}
	
	# returns the Site object the $route points to
	public static function get($route) {
		if (!isset(Routing::$routes))
			Routing::init();
		
		if (isset(Routing::$routes[$route])) {
			$siteClass = Routing::$routes[$route];
			return new $siteClass();
		}
		else
			return NULL;
	}
	
	public static function getPath($siteClass) {
		if (!isset(Routing::$paths))
			Routing::init();
		
		if (isset(Routing::$paths[$siteClass]))
			return Routing::$paths[$siteClass];
		else
			return NULL;
	}
	
	public static function relocate($siteName) {
		header("location: ".ResourceManager::$httpRoot."?site=".$siteName);
	}
}


?>
