<?php

/*
 * Klass som håller data kring inmatad data från loginformulär
 *
 **/

class PostedLoginCred {

	public $username;
	public $password;

	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}	
}