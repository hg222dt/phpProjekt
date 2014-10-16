<?php

class PostedRegCred {

	public $username;
	public $password;
	public $repeatedPassword;
	public $userRole;

	public function __construct($username, $password, $repeatedPassword, $userRole) {
		$this->username = $username;
		$this->password = $password;
		$this->repeatedPassword = $repeatedPassword;
		$this->userRole = $userRole;
	}	
}