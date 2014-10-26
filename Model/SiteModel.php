<?php

require_once("../phpProjekt/Model/UserDAL.php");
require_once("../phpProjekt/View/PostedLoginCred.php");
require_once("../phpProjekt/Model/CurrentUser.php");
require_once("../phpProjekt/Model/Quizzie.php");


require_once("QuizzDAL.php");
require_once("QuestionDAL.php");
require_once("AlternativesDAL.php");
require_once("ResultsDAL.php");

require_once("Question.php");

class SiteModel {

	const USER_TYPE_TEACHER = 1;
	const USER_TYPE_STUDENT = 2;
	
	private $userDAL;
	public $quizzDAL;
	public $questionDAL;
	public $alternativesDAL;
	public $resultsDAL;

	public $currentUser;

	public $quizzId;


	//Save all quizzes here.
	public $quizzList;
	public $quizz;


	public function __construct() {
		
		$this->userDAL = new UserDAL();
		$this->quizzDAL = new QuizzDAL();
		$this->questionDAL = new QuestionDAL();
		$this->alternativesDAL = new AlternativesDAL();
		$this->resultsDAL = new ResultsDAL();

	}

	public function tryLogin($loginCred) {

		$resultArray = $this->userDAL->getUser($loginCred->username);

		$username = $resultArray["Username"];
		$password = $resultArray["Password"];
		$userRole = $resultArray['Role'];
		$userId = $resultArray['User_Id'];

		if(strcmp($password, $loginCred->password) == 0) {

			$this->currentUser = new CurrentUser($username, $userRole);
			$this->setUserSession($userId);
			$this->setUserSessionRole($userRole);
			$this->setUserSessionUsername($username);

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
		$this->quizzDAL->createNewQuizz($quizzName, $this->getUserSession());

		$this->setActiveQuizzId();
	}

	public function setActiveQuizzId() {
		$_SESSION['ActiveQuizzId'] = $this->quizzDAL->getLatestQuizzId();
	}

	public function setActiveQuestionId($questionId) {
		$_SESSION['ActiveQuestionId'] = $questionId;
	}

	public function setActiveQuestionIdWithLatest() {
		$_SESSION['ActiveQuestionId'] = $this->questionDAL->getLatestQuestionId();
	}

	public function getActiveQuestionId() {
		return $_SESSION['ActiveQuestionId'];
	}

	public function getActiveQuizzId() {
		return $_SESSION['ActiveQuizzId'];
	}

	public function saveQuizzQuestion($questionText) {

		$quizzId = $this->getActiveQuizzId();
		$quizzOrderValue = $this->getQuizzOrderValue();

		$this->questionDAL->addQuestion($quizzId, $questionText, $quizzOrderValue);
		$this->setActiveQuestionIdWithLatest();
	}

	public function saveQuizzAlternatives($alternatives) {

		$alternativeTexts = $alternatives[0];
		$correctAnswers = $alternatives[1];
		$this->alternativesDAL->addAlternatives($alternativeTexts, $correctAnswers, $this->getActiveQuestionId());
	}

	public function setQuizzOrderValue($quizzOrderValue) {
		$_SESSION['quizzOrderValue'] = $quizzOrderValue;
	}

	public function getQuizzOrderValue() {
		return $_SESSION['quizzOrderValue'];
	}

	public function setActiveQuizzRun($quizzId) {
		$_SESSION['activeQuizzRun'] = $quizzId;
	}

	public function getActiveQuizzRun() {
		return $_SESSION['activeQuizzRun'];
	}

	public function setUserSession($userId) {
		$_SESSION['userIdLoggedIn'] = $userId;
	}

	public function getUserSession() {
		return $_SESSION['userIdLoggedIn'];
	}

	public function getUserQuizzes() {
		$userId = $this->getUserSession();
		$userQuizzes = array();
		$userQuizzes = $this->quizzDAL->getUserQuizzNames($userId);
		return $userQuizzes;
	}

	public function getUserQuizzIds() {
		$userId = $this->getUserSession();
		$userQuizzId = array();
		$userQuizzId = $this->quizzDAL->getUserQuizzIds($userId);
		return $userQuizzId;
	}

	public function getAllQuizzes() {
		$allQuizzes = array();
		$allQuizzes = $this->quizzDAL->getAllQuizzNames();
		return $allQuizzes;
	}

	public function getAllQuizzIds() {
		$userQuizzId = array();
		$allQuizzId = $this->quizzDAL->getAllQuizzIds();
		return $allQuizzId;
	}

	public function getNumberOfQuestionsInQuizz($quizzId) {
		return $this->questionDAL->getNumberOfQuestions($quizzId);
	
	}

	public function getQuestionObject($questionId) {
		return new Question($questionId);
	}

	public function getQuizzIdFromQuestionId($questionId) {
		return $this->questionDAL->getQuizzId($questionId);
	}

	public function saveEditedQuestion($questionId, $questionText, $alternatives) {
		$alternativeTexts = $alternatives[0];
		$correctAnswers = $alternatives[1];
		$this->questionDAL->editQuestion($questionId, $questionText, $alternativeTexts, $correctAnswers);	
	}

	public function deleteQuizz($quizzId) {
		$this->quizzDAL->deleteQuizz($quizzId);
	}

	public function setUserSessionRole($userRole) {
		$_SESSION['userRole'] = $userRole;
	}

	public function getSessionUserRole() {
		return $_SESSION['userRole'];
	}

	public function setUserSessionUsername($username) {
		$_SESSION['sessionUsername'] = $username;
	}

	public function getUserSessionUsername() {
		return $_SESSION['sessionUsername'];
	}

	public function getQuestionIdFromOrderAndQuizzId($orderValue, $quizzId) {
		return $this->questionDAL->getQuestionIdFromOrderAndQuizzId($orderValue, $quizzId);
	}

	public function saveQuestionAnswer($answerArray, $questionId) {

		//Hitta vad det rätta svaret på frågan är

		$isUserCorrect = 2;
		$answerIsCorrect = true;
		$correctFound = false;

		$answerIsInCorrectArray = false;

		$userFailedTotally = false;

		$correctArray = $this->alternativesDAL->getCorrects($questionId);

		$falseFound = false;

		foreach ($answerArray as $key2 => $answerValue) {

			if(!$this->doesUserInputMatchAnyCorrectValue($answerValue, $correctArray)) {
				$_SESSION['correctAnswerQuestion$questionId'] = 2;
				$falseFound = true;
				$isUserCorrect = 2;
			}
		}	

		if($falseFound == false) {

			//Skicka true till dal
			$isUserCorrect = 1;

			$_SESSION['correctAnswerQuestion$questionId'] = 1;
		}

		//Skicka true till dal

		$quizzId = $this->getActiveQuizzRun();
		$userId = $this->getUserSession();

		$this->resultsDAL->addResult((int) $isUserCorrect, (int) $questionId, (int) $quizzId, (int) $userId);
	}

	public function doesUserInputMatchAnyCorrectValue($answerValue, $correctArray) {
		foreach ($correctArray as $key => $correctValue) {
			if((int) $correctValue == (int) $answerValue) {
				return true;
			}
		}
		return false;
	}

	public function sumUpQuizzResult($questionIdsInQuizz) {
		for($i=0; $i<2; $i++) {
			$questionId = $questionIdsInQuizz[$i];
		}
	}
}