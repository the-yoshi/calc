<?php
#Definiert die Men�punkte
class Menu extends Site {

	public function getName () {
		return "menu";
	}
	private $trenner = "";
	private $html = "";
	
	#Men�items initialisieren
	private $logout = "";
	private $verwaltung = "";
	private $aufgabe = "";
	private $zuteilung = "";
	private $aufgabenliste = "";
	private $lehrerzuordnung = "";

		
	public function __construct($root, $alignment, $rolle = "guest") {
		
		#Einstellen des Rootverzeichnisses f�r Serverumz�ge
		$this->logout = '<a href="'.$root.'?logout=true">Logout</a>';
		$this->verwaltung = '<a href="'.$root.'?site=verwaltung">Verwaltung</a>';
		$this->aufgabe = '<a href="'.$root.'?site=aufgabe">Aufgaben</a>';
		$this->zuteilung = '<a href="'.$root.'?site=uebungen">�bungen</a>';
		$this->aufgabenliste = '<a href="'.$root.'?site=aufgabenliste">Pflichtaufgaben</a>';
		$this->lehrerzuordnung = '<a href="'.$root.'?site=lehrerzuordnen">Lehrer zuordnen</a>';
		
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
		
		#Automatische auswahl des richtigen Men�s zum Zur�ckliefern
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
		$html = $this->verwaltung . $this->trenner . $this->lehrerzuordnung . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	private function guest() {
		$html = "In Arbeit";
		$this->html = $html;
	}
	
	private function schueler() {
		#auf aufgaben pr�fen
		$html = $this->aufgabenliste . $this->trenner . $this->logout;
		$this->html = $html;
	}
	
	#LehrerMen�
	private function lehrer() {
		$html = $this->verwaltung . $this->trenner . $this->zuteilung . $this->trenner . $this->logout;
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