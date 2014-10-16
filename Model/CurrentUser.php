<?php

class CurrentUser {
	
	public $username;
	public $userRole;

	public function __construct($username, $userRole) {
		$this->username = $username;
		$this->userRole = $userRole;
	}
}