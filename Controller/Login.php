<?php
# Warum benutzt der Login keine Methoden aus der MySQL-Klasse sondern seinen eigenen Zugang?!
class Login {
	
	protected $user = "crud";
	protected $pw = "rw"; 
	protected $db = "kopfrechnen";
	protected $host = "localhost";
	protected $sql;	
	
	public function __construct() {
	
		$this->sql = StorageManager::getDatabase();
	}
	
	public function register($usernamen, $password1, $password2) {
		
		# HAS TO BE COMPLETELY REWRITTEN
		
		$sql = $this->sql;
		
		if ($password1 == $password2 && $password1 != "") {
			$pw = $sql->real_escape_string($password1);
		} else {
			return "Passw�rter stimmen nicht �berein!";
			exit();
		}
		
		$user = $sql->real_escape_string($username);
		#Pr�fen, ob Benutzername schon verwendet wird
		$query1 = "Select name from account where name = '$user'";
		$query2 = "Select name from anfrage where name = '$user'";
		
		$test1 = $sql->query($query1);
		$test2 = $sql->query($query2);
	
		if (!$test1 && !$test2) {
			$eintrag = "insert into anfrage (name, password) values ('" . $user . "','" . $pw . "')";
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
		
		$accounts = StorageManager::getByCondition("Account", "name = '$user'");
		
		#Session starten
		if(count($accounts) > 0) {
			$_SESSION['user'] = $accounts[0];
			return true;
		} else {
			$_SESSION['error'] = "Benutzername oder Passwort falsch!";
			session_destroy();
			return false;
		}		
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
	
	public static function isLoggedIn() {
		return (isset(ResourceManager::$user));
	}
}