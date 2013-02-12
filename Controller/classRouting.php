<?php

class Routing {
	# returns the Site object the $route points to
	public static function get($route) {
		switch ($route) {
			case 'aufgabe':
				return new TaskSite();
			case 'statistik':
				return new StatisticsSite();
			case 'aufgabenliste':
				return new TaskListSite();
			case 'eigeneraccount':
				return new AccountSite();
			case 'verwaltung':
				return new ManagementSite();
			case 'uebungen':
				return new ExamsSite();
			case 'lehrerzuordnen':
				return new AssignTeacherSite();
			default:
				header("location: ".$_SERVER["PHP_SELF"]);
		}
	}
}


?>
