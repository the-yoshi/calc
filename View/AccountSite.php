<?php

class AccountSite extends Site {
	
	public function getName(){
		return "account";
	}
	
	private function showBT() {
		$links = array();
		$texts = array();
		$texts[] = 'Mein Account';
		$links[] = '#';
		return ViewHelper::createBT($texts, $links);
	}
	
	public function anzeigen(){
		$ret = $this->showBT();
		$ret .= "<p>Gibt's noch nicht!</p>";
		return $ret;
	}
	
}

?>