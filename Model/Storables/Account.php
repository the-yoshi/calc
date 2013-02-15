<?php

class Account implements IStorable {
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
	
	public function __construct ($array) {
		$this->id = $array[0];
		$this->name = $array[1];
		$this->password = $array[2];
		$this->email = $array[3];
		$this->role = $array[4];
		$this->firstName = $array[5];
		$this->lastName = $array[6];
	}
	
	public function getStorableName() {
		return "historyitem";
	}
	
	public function getStorableFields() {
		return array('id', 'name', 'password', 'email', 'role', 'firstName', 'lastName');
	}
	
	public function getStorableValues() {
		return array($this->id, $this->name, $this->password, $this->email, $this->role, $this->firstName, $this->lastName);
	}
	
	public function getStorableRelations() {
		$ret = array();
		foreach ($classes as $class) {
			
		}
	}
}

?>