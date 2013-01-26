<?php
class Menu {
	
	private $trenner = "";
	private $html = "";
	
	#Menüitems zum kombinieren
	private $logout = "";
	private $verwaltung = "";
	private $aufgabe = "";
	private $zuteilung = "";

		
	public function __construct($root, $alignment, $rolle = "guest") {
		
		#Einstellen des Rootverzeichnisses für Serverumzüge
		$this->logout = '<a href="'.$root.'?logout=true">Logout</a>';
		$this->verwaltung = '<a href="'.$root.'?site=verwaltung">Verwaltung</a>';
		$this->aufgabe = '<a href="'.$root.'?site=aufgabe">Aufgaben</a>';
		$this->zuteilung = '<a href="'.$root.'?site=uebungen">Übungen</a>';
		
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
		$html = $this->verwaltung . $this->trenner . $this->zuteilung . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	private function guest() {
		$html = $this->aufgabe;
		$this->html = $html;
	}
	
	private function schueler() {
		#auf aufgaben prüfen
		$html = $this->logout;
		$this->html = $html;
	}
	
	#LehrerMenü
	private function lehrer() {
		$html = $this->verwaltung . $this->trenner . $this->zuteilung . $this->trenner . $this->aufgabe. $this->trenner . $this->logout;
		$this->html = $html;
		#aufgaben erstellen
		#aufgaben zuordnen
		#auswertungen einsehen
		#aufgaben testen
		#schüler erstellen
	}
	
	public function anzeigen() {
		$html = $this->html;
		return $html;
	}
	
}