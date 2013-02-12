<?php

# here goes everything related to reoccurring html elements that have to be generated, e.g.
# - boxes,
# - lists,
# - the login

class ViewHelper {
	
	public static function makeBox($name, array $liste, array $markiert = array()) {
		if (count($liste) <= 5) {
			$size = count($liste);
		} else {
			$size = 10;
		}
		
		$markiert_ids = array();
		if (count($markiert)>0) {
			foreach ($markiert as $m) {
				$markiert_ids[] = $m[0];
			}
		}
		
		$html = '<select name="'.$name.'" size="'.$size.'" multiple>';
		foreach ($liste as $l) {
			$html .= '<option value="'.$l[0].'" '; 
				if (in_array($l[0], $markiert_ids)) {$html .= " selected";}
			$html .= '>'.$l[1];
				if (isset($l[2])) {$html .= ', '.$l[2];}
			$html .= "</option>";
		}
		$html .= "</select>";
		return $html;
	}
	
		public function makeList($name, array $quelle, $firstfield = false, $autosubmit = false, $ort = "", $id = 0, $disable = false) {	
		$html = '<select name="'.$name.'"';
		
		if ($autosubmit) {
			$html .= ' id="auswahl" onchange=\'window.location = "'.$ort.'&id="+document.getElementById("auswahl").value\' ';
		}		
		
		if ($disable) {$html .= " diabled ";}
		
		$html .= '>';
		
		if ($firstfield) {$html .= '<option> Bitte w�hlen... </option>';}
			
		foreach ($quelle as $q) {
			if (isset($id) && $id == $q[0]) {
				$html .= '<option value="'.$q[0].'" selected>'.$q[1].'</option>';
			} else {
				$html .= '<option value="'.$q[0].'">'.$q[1].'</option>';
			}
		}
		$html .= '</select>';
		
		return $html;
	}
	
	public static function makePOSTList($name, array $quelle, $firstfield = false, $autosubmit = false, $ort = "", $id = 0, $disable = false) {
		$html = '<select name="'.$name.'"';
	
		if ($autosubmit) {
			$html .= ' name="auswahl" onchange=\'this.form.submit();\' ';
		}
	
		if ($disable) {$html .= " disabled ";}
	
		$html .= '>';
	
		if ($firstfield) {$html .= '<option> Bitte w�hlen... </option>';}
			
		foreach ($quelle as $q) {
			if (isset($id) && $id == $q[0]) {
				$html .= '<option value="'.$q[0].'" selected>'.$q[1].'</option>';
			} else {
				$html .= '<option value="'.$q[0].'">'.$q[1].'</option>';
			}
		}
		$html .= '</select>';
	
		return $html;
	}
}

?>