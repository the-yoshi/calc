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
	
		if ($firstfield) {$html .= '<option> Bitte wählen... </option>';}
			
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

	##
	##

	public static function showLogin() {
		$ret = '<center><form action="'.ResourceManager::$httpRoot. '" method="post">';
		if (isset($_SESSION['error'])) {
			$ret = $ret.$_SESSION['error'] . "<br />";
		}
		$ret = $ret.'<input type="text" name="logindaten[user]" /> <br />';
		$ret = $ret.'<input type="password" name="logindaten[password]" /> <br />';
		$ret = $ret.'<input type="submit" value="Anmelden" />';
		$ret = $ret.'</form></center>';
		return $ret;
	}
	
	public static function createTableRow($values) {
		$ret = "<tr>";
		foreach ($values as $v)
			$ret .= '<td>'.$v.'</td>';
		return $ret.'</tr>';
	}
	
	public static function createDropdownList($name, $value, $keys, $texts) {
		$ret = "<select name=\"$name\" value=\"$value\">";
		for ($i=0; $i<count($keys);$i++) {
			$ret .= "<option value=\"".$keys[$i]."\">".$texts[$i]."</option>";
		}
		$ret .="</select>";
		return $ret;
	}
}

?>