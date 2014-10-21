<?php

require_once("../phpProjekt/Model/UserDAL.php");
require_once("../phpProjekt/View/PostedLoginCred.php");
require_once("../phpProjekt/Model/CurrentUser.php");
require_once("../phpProjekt/Model/Quizzie.php");


require_once("QuizzDAL.php");
require_once("QuestionDAL.php");


class SiteModel {

	const USER_TYPE_TEACHER = 1;
	const USER_TYPE_STUDENT = 2;
	
	private $userDAL;
	public $quizzDAL;
	public $currentUser;

	public $quizzId;

	//Save all quizzes here.
	public $quizzList;
	public $quizz;


	public function __construct() {
		
		$this->userDAL = new UserDAL();
		$this->quizzDAL = new QuizzDAL();

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

		if (strlen($postedRegCred->username)<1) {

			throw new Exception("Användarnamnet saknas.");

		
		} else if (strlen($postedRegCred->username)<3) {
		
			throw new Exception("Användarnamnet måste innehålla minst 3 tecken.");
		

		} else if (strlen($postedRegCred->password)<1) {
		
			throw new Exception("Lösenord saknas.");
		

		} else if (strlen($postedRegCred->password)<5) {
		
			throw new Exception("Lösenordet måste innehålla minst 5 tecken.");
		

		} else if (strcmp($postedRegCred->password, $postedRegCred->repeatedPassword) != 0) {
		
			throw new Exception("Lösenordet och det upprepade lösenordet stämmer inte överens.");
		

		} else if ($postedRegCred->userRole == "noInput") {
		
			$postedRegCred->userRole = "noInput";
			throw new Exception("Du måste fylla i din roll");
		

		}

		//Testa lägga till medlem i databas
		try {

			$this->userDAL->addMember($postedRegCred);
			return true;
		
		} catch (Exception $e) {
		
			throw $e;
		
		}

	}

	public function createQuizzMachine($quizzName) {
		
	}

	public function startNewQuizz($quizzName) {
		$this->quizzDAL->createNewQuizz($quizzName);

		$this->setActiveQuizzId();
	}

	public function setActiveQuizzId() {
		$_SESSION['ActiveQuizzId'] = $this->quizzDAL->getLatestQuizzId();
	}

}