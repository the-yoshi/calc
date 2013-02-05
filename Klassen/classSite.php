<?php

abstract class Site {
	private abstract $name;
	
	public function getName() {
		return $name;
	}
	
	public abstract function show();
}

?>