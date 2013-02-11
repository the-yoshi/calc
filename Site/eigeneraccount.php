<?php

class AccountSite extends Site {
	
	public function getName(){
		return "account";
	}
	
	public function anzeigen(){
		return "<p>Gibt's noch nicht!</p>";
	}
	
}

$currentSite = new AccountSite();

?>