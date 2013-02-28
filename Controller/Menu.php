<?php
#Definiert die Men�punkte
class Menu extends Site {

	public function getName () {
		return "menu";
	}
	private $trenner = "";
	private $html = "";
	
	#Men�items initialisieren
	private $account = "";
	private $logout = "";
	private $verwaltung = "";
	private $aufgabe = "";
	private $statistik = "";
	private $aufgabenliste = "";
	private $lehrerzuordnung = "";

		
	public function __construct($alignment) {
		
		$root = ResourceManager::$httpRoot;
		
		if (Login::isLoggedIn())
		{
			$username = ResourceManager::$user->name;
			$rolle = ResourceManager::$user->role;
			$this->account = 'Hallo <a href="'.$root.'?site=eigeneraccount">'.$username.'</a><br />';
		}
		else
			$rolle = "guest";
		
		$this->logout = '<a href="'.$root.'?logout=true">Logout</a>';
		$this->klassenverwaltung = '<a href="'.$root.'?site=klassenverwaltung">Klassen verwalten</a>';
		$this->aufgabe = '<a href="'.$root.'?site=aufgabe">Aufgaben</a>';
		$this->aufgabenverwaltung = '<a href="'.$root.'?site=aufgabenverwaltung">&Uuml;bungen verwalten</a>';
		$this->aufgabenliste = '<a href="'.$root.'?site=aufgabenliste">Meine &Uuml;bungen</a>';
		$this->statistik = '<a href="'.$root.'?site=statistik&lehrer">Statistik</a>';
		
		#Gew�schte Ausrichung des Men�s
		switch($alignment) {
			case "vertikal":
				$this->trenner = "<br />";
				break;
			case "horizontal":
				$this->trenner = " | ";
				break;
			default:
				$this->trenner = " ";
		}
		
		#Automatische auswahl des richtigen Menüs zum Zurückliefern
		switch($rolle) {
			case "admin":
				$this->admin();
				break;
			case "lehrer":
				$this->lehrer();
				break;
			case "schueler":
				$this->schueler();
				break;
			case "guest":
				$this->guest();
				break;
		}
	}
	
	#Erstellt die jeweiligen Men�s f�r die einzelnen Rollen durch das Aneinanderreihen von den gew�nschten Punkten
	private function admin() {
		$html = $this->account.
				$this->aufgabenliste.$this->trenner.
				$this->aufgabenverwaltung.$this->trenner.
				$this->klassenverwaltung.$this->trenner.
				$this->statistik.$this->trenner.
				$this->logout;
		$this->html = $html;
	}
	
	private function guest() {
		$html = "In Arbeit";
		$this->html = $html;
	}
	
	private function schueler() {
		#auf aufgaben pr�fen
		$html = $this->account. $this->aufgabenliste . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	#LehrerMen�
	private function lehrer() {
		$html = $this->account.  $this->aufgabenliste . $this->trenner . $this->aufgabenverwaltung. $this->klassenverwaltung . $this->trenner .$this->statistik.$this->trenner. $this->logout;
		$this->html = $html;
		#aufgaben erstellen
		#aufgaben zuordnen
		#auswertungen einsehen
		#aufgaben testen
		#sch�ler erstellen
	}
	
	#R�ckgabe des Men�s als HTML-Code
	public function anzeigen() {
		$html = $this->html;
		return $html;
	}
	
}