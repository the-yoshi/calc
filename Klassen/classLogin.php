<?php
# Warum benutzt der Login keine Methoden aus der MySQL-Klasse?!
class Login {
	
	protected $user = "crud";
	protected $pw = "rw"; 
	protected $db = "kopfrechnen";
	protected $host = "localhost";
	protected $sql;	
	
	public function __construct($login = 'login', $password = 'login') {
		$this -> user = $login;
		$this -> pw = $password;
	
		$this->connect();
	}	
	
	public function connect() {
		$host = $this->host;
		$user = $this->user;
		$password = $this->pw;
		$database = $this->db;
		
		$sql = new mysqli($host, $user, $password, $database);
		
		if ($sql->errno) {
			echo "Connect failed.";
			return false;
		} else {
			$this->sql = $sql; 
			return true;
		}
	}
	
	public function register($usernamen, $password1, $password2) {
		$sql = $this->sql;
		
		if ($password1 == $password2 && $password1 != "") {
			$pw = $sql->real_escape_string($password1);
		} else {
			return "Passw�rter stimmen nicht �berein!";
			exit();
		}
		
		$user = $sql->real_escape_string($username);
		#Pr�fen, ob Benutzername schon verwendet wird
		$query1 = "Select username from account where username = '$user'";
		$query2 = "Select username from anfrage where username = '$user'";
		
		$test1 = $sql->query($query1);
		$test2 = $sql->query($query2);
	
		if (!$test1 && !$test2) {
			$eintrag = "insert into anfrage (username, password) values ('" . $user . "','" . $pw . "')";
			$bool = $sql->query($eintrag);
		} else {
			return "Benutzername wird bereits verwendet!";
			exit();
		}	
		return true;
	}
	
	public function einloggen($user, $pw) {
		$sql = $this->sql;
		
		$user = $sql->real_escape_string($user);
		$pw = $sql->real_escape_string($pw);
		
		$query = "Select account.id, account.username, account.password, account.rolle, account.email, account.Vorname, account.Nachname from account where username='".$user."' and password='".md5($pw)."'";
		$daten = mysqli_fetch_array($sql->query($query));
		
		#Session starten
		if($daten) {
			$_SESSION['user'] = array('id' => $daten["id"], 'username' => $daten["username"], 'rolle' => $daten["rolle"], 'email' => $daten["email"], 'vorname' => $daten["Vorname"], 'nachname' => $daten["Nachname"]);
			return true;
		} else {
			$_SESSION['error'] = "Benutzername oder Passwort falsch!";
			session_destroy();
			return false;
		}		
		#Parameter f�r �bungsaufgabe vorhanden?
	}
	
	public function umtragen($usernamen) {
		#Austragen aus Anfrage
		#Eintragen in Account
		#Eintragen Rolle
	}
	
	public function pwaendern() {
		#Passwort �ndern
	}
		
	public function namenaendern() {
		
	}
	
	public function loeschen() {
		#Benutzer l�schen
	}

	public function rolleaendern() {
		#�ndern der Rolle
	}
	
	public static function anzeigen() {
		$ret = '<center><form action="'. $_SERVER["PHP_SELF"] . '" method="post">';
		if (isset($_SESSION['error'])) {
			$ret = $ret.$_SESSION['error'] . "<br />";
		}
		$ret = $ret.'<input type="text" name="logindaten[user]" /> <br />';
		$ret = $ret.'<input type="password" name="logindaten[password]" /> <br />';
		$ret = $ret.'<input type="submit" value="Anmelden" />';
		$ret = $ret.'</form></center>';
		return $ret;
	}
	
	
}