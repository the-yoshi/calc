<?php 

class ManagementSite extends Site {

	public function getName () {
		return "management";
	}

	public function anzeigen() {
		$ret = "under construction";
		if (!isset(ResourceManager::$user))
			Routing::relocate("");
		
		return $ret;
	}
}
?>
