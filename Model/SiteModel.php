<?php

require_once("../phpProjekt/Model/UserDAL.php");
require_once("../phpProjekt/View/PostedLoginCred.php");
require_once("../phpProjekt/Model/CurrentUser.php");

class SiteModel {
	
	private $userDAL;
	public $currentUser;

	public function __construct() {

		$this->userDAL = new UserDAL();
		//$this->userDAL->addMemberTest();

	}

	public function tryLogin($loginCred) {
		$resultArray = $this->userDAL->getUser($loginCred->username);

		$username = $resultArray["Username"];
		$password = $resultArray["Password"];
		$userRole = $resultArray['Role'];

		if(strcmp($password, $loginCred->password) == 0) {

			$this->currentUser = new CurrentUser($username, $userRole);

			return true;
		} else {
			return false;
		}
	}

	public function regNewUser($postedRegCred) {

		try {
			$this->userDAL->addMember($postedRegCred);
			return true;
		} catch (Exception $e) {
			throw $e;
		}

	}

}