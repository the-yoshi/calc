<?php

class Account extends Storable {
	public $id;
	public $name;
	public $password;
	public $email;
	public $role;
	public $firstName;
	public $lastName;
	
	private $classes;
	
	## IStorable methods
	##
	
	public function __construct() {
		
	}
	
	public static function fromArray($array) {
		$r = new Account();
		$r->id = $array[0];
		$r->name = $array[1];
		$r->password = $array[2];
		$r->email = $array[3];
		$r->role = $array[4];
		$r->firstName = $array[5];
		$r->lastName = $array[6];
		return $r;
	}
	
	public function getStorableName() {
		return "account";
	}
	
	public function getStorableFields() {
		return array('id', 'name', 'password', 'email', 'role', 'firstname', 'lastname');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->name, $this->password, $this->email, $this->role, $this->firstName, $this->lastName);
	}
	
	public function getStorableRelations() {
		$ret = array();
		foreach ($classes as $class) {
			
		}
		return $ret;
	}
}

?>