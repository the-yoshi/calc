<?php
class Menu {
	
	private $trenner = "";
	private $html = "";
	
	#Men�items zum kombinieren
	private $logout = "";
	private $klasseVerwalten = "";
	private $aufgabe = "";
	private $statistik = "";
	private $taskmanager = "";
		
	public function __construct($root, $alignment, $rolle = "guest") {
		
		#Einstellen des Rootverzeichnisses f�r Serverumz�ge
		$this->logout = '<a href="'.$root.'?logout=true">Logout</a>';
		$this->klasseVerwalten = '<a href="'.$root.'?site=klassenmanager">Verwaltung</a>';
		$this->aufgabe = '<a href="'.$root.'?site=aufgabe">Aufgaben</a>';
		$this->statistik = '<a href="'.$root.'?site=statistik">Statistik</a>';
		$this->taskmanager = '<a href="'.$root.'?site=taskmanager">�bungen</a>';
		
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
	
	private function admin() {
		$html = $this->klasseVerwalten . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	private function guest() {
		$html = "Test";
		$this->html = $html;
	}
	
	private function schueler() {
		#auf aufgaben pr�fen
		$html = $this->logout;
		$this->html = $html;
	}
	
	#LehrerMen�
	private function lehrer() {
		$html = $this->taskmanager . $this->trenner . $this->klasseVerwalten . $this->trenner . $this->aufgabe. $this->trenner . $this->logout;
		$this->html = $html;
		#aufgaben erstellen
		#aufgaben zuordnen
		#auswertungen einsehen
		#aufgaben testen
		#sch�ler erstellen
	}
	
	public function anzeigen() {
		$html = $this->html;
		return $html;
	}
	
}