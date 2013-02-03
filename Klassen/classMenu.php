<?php
#Definiert die Menüpunkte
class Menu {
	
	private $trenner = "";
	private $html = "";
	
	#Menüitems initialisieren
	private $logout = "";
	private $verwaltung = "";
	private $aufgabe = "";
	private $zuteilung = "";
	private $aufgabenliste = "";
	private $lehrerzuordnung = "";

		
	public function __construct($root, $alignment, $rolle = "guest") {
		
		#Einstellen des Rootverzeichnisses für Serverumzüge
		$this->logout = '<a href="'.$root.'?logout=true">Logout</a>';
		$this->verwaltung = '<a href="'.$root.'?site=verwaltung">Verwaltung</a>';
		$this->aufgabe = '<a href="'.$root.'?site=aufgabe">Aufgaben</a>';
		$this->zuteilung = '<a href="'.$root.'?site=uebungen">Übungen</a>';
		$this->aufgabenliste = '<a href="'.$root.'?site=aufgabenliste">Pflichtaufgaben</a>';
		$this->lehrerzuordnung = '<a href="'.$root.'?site=lehrerzuordnen">Lehrer zuordnen</a>';
		
		#Gewüschte Ausrichung des Menüs
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
	
	#Erstellt die jeweiligen Menüs für die einzelnen Rollen durch das Aneinanderreihen von den gewünschten Punkten
	private function admin() {
		$html = $this->verwaltung . $this->trenner . $this->lehrerzuordnung . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	private function guest() {
		$html = "In Arbeit";
		$this->html = $html;
	}
	
	private function schueler() {
		#auf aufgaben prüfen
		$html = $this->aufgabenliste . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	#LehrerMenü
	private function lehrer() {
		$html = $this->verwaltung . $this->trenner . $this->zuteilung . $this->trenner . $this->logout;
		$this->html = $html;
		#aufgaben erstellen
		#aufgaben zuordnen
		#auswertungen einsehen
		#aufgaben testen
		#schüler erstellen
	}
	
	#Rückgabe des Menüs als HTML-Code
	public function anzeigen() {
		$html = $this->html;
		return $html;
	}
	
}