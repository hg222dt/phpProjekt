<?php

/*
 * Klass som håller data kring inmatad data från registreringsformulär
 *
 **/

class PostedRegCred {

	public $username;
	public $password;
	public $repeatedPassword;
	public $userRole;
	public $teacherPassword;

	public function __construct($username, $password, $repeatedPassword, $userRole) {
		$this->teacherPassword = null;
		$this->username = $username;
		$this->password = $password;
		$this->repeatedPassword = $repeatedPassword;
		$this->userRole = $userRole;
	}
}