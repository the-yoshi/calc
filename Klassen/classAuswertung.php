<?php
#Alte Version aus Testzeiten
class Auswertung {
	
	#Richtig-Falsch-Feld in DB.historie einfügen!
	#Erreichte abweichung in DB.historie einfügen!
	
	protected $data = array("aufgabe"=>0, "account"=>0, "rechnung"=>"", "phpergebnis"=>"", "eingabeergebnis"=>0,"richtig"=>false, "abweichung"=>0, "differenz"=>0, "beginn"=>"", "beendet"=>"");
	protected $account = 0;
	
	public function __construct($account = 0) {
		$this->account = $account;	
	}
	
	/*Aufbau des Arrays:
	* Aufgabennr
	* AccountID
	* Term (String)
	* errechnetes Ergebnis
	* Ergbnis des Schülers
	* erlaubte abweichung
	* erreichte abweichung
	* startzeit
	* endzeit
	* Richtig/Falsch 
	* dauer
	*/
	
	public function abweichung($aufgabe, $term, $phpergebnis, $eingabeergebnis, $beginn, $ende, $abweichung, $vergleich) {
		$account = $this->account;
		
		switch ($vergleich) {
			case "false":
				$obergrenze = ($phpergebnis * ((100+$abweichung)/100));
				$untergrenze = ($phpergebnis * ((100-$abweichung)/100));
				
				if ($phpergebnis != 0) {
					$differenz = round(abs((1-($eingabeergebnis/$phpergebnis))*100),0);
				} else {
					$differenz = $eingabeergebnis;
				}
				
				if (($eingabeergebnis <= $obergrenze && $eingabeergebnis >= $untergrenze) or $phpergebnis == $eingabeergebnis) {
					$this->data[] = array($aufgabe, $account, $term, $phpergebnis, $eingabeergebnis, true, $abweichung, $differenz, $beginn, $ende);
					echo "Du lagst richtig, mit $differenz% Abweichung";
					return array(true, $differenz);
				} else {
					$this->data[] = array($aufgabe, $account, $term, $phpergebnis, $eingabeergebnis, false, $abweichung, $differenz, $beginn, $ende);
					echo "Du lagst falsch, mit $differenz% Abweichung";
					return array(false, $differenz);
				}
			break;
			
			case "true":
				if ($phpergebnis==$eingabeergebnis) {
					$this->data[] = array($aufgabe, $account, $term, $phpergebnis, $eingabeergebnis, true, 0, 0, $beginn, $ende);
					echo "Richtig";
					return array(true, 100);
				} else {
					$this->data[] = array($aufgabe, $account, $term, $phpergebnis, $eingabeergebnis, false, 0, 0, $beginn, $ende);
					echo  "Falsch";
					return array(false, 100);
				}
			break;
		}	
	}
	
	public function zeitdifferenz($beginn, $ende) {
		
		#return $zeit;
	}
	
	public function showall() {
		var_dump($this->data);
	}
}