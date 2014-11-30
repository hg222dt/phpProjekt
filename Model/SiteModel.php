<?php

require_once("./Model/UserDAL.php");
require_once("./View/PostedLoginCred.php");
require_once("./Model/CurrentUser.php");
require_once("./Model/Quizzie.php");


require_once("QuizzDAL.php");
require_once("QuestionDAL.php");
require_once("AlternativesDAL.php");
require_once("ResultsDAL.php");
require_once("FinishedDAL.php");

require_once("Question.php");

class SiteModel {

	const USER_TYPE_TEACHER = 1;
	const USER_TYPE_STUDENT = 2;
	
	private $userDAL;
	public $quizzDAL;
	public $questionDAL;
	public $alternativesDAL;
	public $resultsDAL;
	public $finishedDAL;

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
		$this->finishedDAL = new FinishedDAL();
	}

	public function tryLogin($loginCred) {

		$this->setSessionHttpUserAgent();

		$resultArray = $this->userDAL->getUser($loginCred->username);

		$username = $resultArray["Username"];
		$password = $resultArray["Password"];
		$userRole = $resultArray['Role'];
		$userId = $resultArray['User_Id'];

		if(strcmp($password, md5($loginCred->password)) == 0 && $username != null) {

			$this->currentUser = new CurrentUser($username, $userRole);
			$this->setUserSession($userId);
			$this->setUserSessionRole($userRole);
			$this->setUserSessionUsername($username);
			$this->setUserLoggedInState(true);

			return true;

		} else {
			
			return false;

		}
	
	}

	public function didUserAnswerCorrect($questionId, $userId) {
		//return $this->resultsDAL->didUserAnswerCorrect($questionId, $userId);

		if($_SESSION['correctAnswerQuestion$questionId'] == 1) {
			return true;
		}

		return false;
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

	public function setSessionHttpUserAgent() {
		$_SESSION['httpUserAgent'] = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getSessionHttpUserAgent() {
		return $_SESSION['httpUserAgent'];
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

	public function setUserLoggedInState($loggedInState) {
		$_SESSION['UserLoggedIn'] = $loggedInState;
	}

	public function setUserLoggedOut() {
		$this->setUserSession(null);
		$this->setUserSessionRole(null);
		$this->setUserSessionUsername(null);
		$this->setUserLoggedInState(false);
	}

	public function isUserLoggedIn() {

		if((isset($_SESSION['UserLoggedIn']) && $_SESSION['UserLoggedIn']) == true && ($this->getSessionHttpUserAgent() == $_SERVER['HTTP_USER_AGENT'])) {
			return true;
		}
		return false;
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

		if(!$this->doesUserInputMatchAllCorrectValue($answerArray, $correctArray)) {
			$_SESSION['correctAnswerQuestion$questionId'] = 2;
			$falseFound = true;
			$isUserCorrect = 2;
		} else {
			//Skicka true till dal
			$isUserCorrect = 1;
			$_SESSION['correctAnswerQuestion$questionId'] = 1;

		}

		$quizzId = $this->getActiveQuizzRun();
		$userId = $this->getUserSession();

		$resultExists = $this->resultsDAL->checkIfResultExists($questionId, $userId);

		if(!$resultExists) {
			$this->resultsDAL->addResult((int) $isUserCorrect, (int) $questionId, (int) $quizzId, (int) $userId);
		}
	}

	public function doesUserInputMatchAllCorrectValue($answerArray, $correctArray) {
		
		$newCorrectArray = array();
		foreach ($correctArray as $key => $value) {
			if($value != "") {
				array_push($newCorrectArray, $value);
			}
		}

		if(sizeof($answerArray) != sizeof($newCorrectArray)) {
			return false;
		}

		foreach ($newCorrectArray as $key2 => $correctValue) {

			if($answerArray[$key2] != $correctValue) {
				return false;
			}
		}

		return true;

	}

	public function saveFinishedResult($userId, $quizzId) {

		$results = $this->resultsDAL->getResultsForUserAndQuizz($userId, $quizzId);

		$corrects = 0;
		$wrongs = 0;

		foreach ($results as $key => $value) {
			if($value == 1) {
				$corrects++;
			} else {
				$wrongs++;
			}
		}

		$resultInPercentage = $corrects / ($corrects + $wrongs);
		$this->finishedDAL->addFinishedQuizz($resultInPercentage, $quizzId, $userId);
		return $resultInPercentage;
	}

	public function getQuizzResultsUser() {
		$userId = $this->getUserSession();

		$quizzResults = $this->finishedDAL->getAllFinishedResultsUser((int) $userId);

		//Convert results to percentage
		foreach ($quizzResults as $key => $value) {
			$value = $value * 100;
			$roundedValue = round($value);
			$quizzResults[$key] = $roundedValue;

		}

		return $quizzResults;
	}

	public function getQuizzResultsSpecUser($userId) {
		$quizzResults = $this->finishedDAL->getAllFinishedResultsUser((int) $userId);

		//Convert results to percentage
		foreach ($quizzResults as $key => $value) {
			$value = $value * 100;
			$roundedValue = round($value);
			$quizzResults[$key] = $roundedValue;

		}

		return $quizzResults;
	}

	public function isQuizzDone($quizzId) {
		$userId = $this->getUserSession();
		$quizzResults = $this->finishedDAL->getAllFinishedQuizzes((int) $userId);

		foreach ($quizzResults as $key => $value) {
			if($value == $quizzId) {
				return true;
			}
		}
		return false;
	}

	public function isQuizzStartedByUser($quizzId) {

		$userId = $this->getUserSession();

		$result = $this->resultsDAL->getResultsForUserAndQuizz($userId, $quizzId);

		if($result != false) {
			return true;
		} else {
			return false;
		}
	}

	public function getResumeQuizzOrderValue($quizzId) {
		$userId = $this->getUserSession();

		$lastQuestionId = $this->resultsDAL->getLastFinishedQuestionId($userId, $quizzId);

		$lastQuestionOrderValue = $this->questionDAL->getQuestionOrder($lastQuestionId);

		$thisQuestionOrder = $lastQuestionOrderValue['QuizzOrderValue'] + 1;

		return $thisQuestionOrder;
	}


	public function getAmountDoneQuizzes() {
		return $this->finishedDAL->getAmountDoneQuizzes();
	}

	public function getAverageResultsQuizzes() {
		return $this->finishedDAL->getAverageResultsQuizzes();
	}

	public function getStudentsNames() {
		return $this->userDAL->getStudentsNames();
	}
}