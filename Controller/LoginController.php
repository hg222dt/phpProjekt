<?php

require_once("./View/SiteView.php");
require_once("./Model/SiteModel.php");
require_once("RegisterController.php");

class LoginController {

	private $siteView;
	private $siteModel;

	public function __construct() {
		$this->siteModel = new SiteModel();
		$this->siteView = new SiteView($this->siteModel);
	}

	public function doControll() {

		try {
			switch($this->siteView->getUserAction()) {

				case SiteView::ACTION_USER_GOTO_REGISTER:
					return $this->siteView->showRegisterPage();
					break;

				case SiteView::ACTION_USER_LOGS_IN_DEFAULT:
					
					if($this->siteModel->isUserLoggedIn()) {
						return $this->siteView->showLoggedInPage();
					}
					
					if($this->siteModel->tryLogin($this->siteView->getPostedLoginCred())){
						$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_IN);
						return $this->siteView->showLoggedInPage();
					} else {
						$this->siteView->setMessage(SiteView::MESSAGE_FAILED_LOGIN);
						return $this->siteView->showLobby();
					}
					break;

				case SiteView::ACTION_USER_LOGS_OUT:
					$this->siteModel->setUserLoggedOut();
					$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_OUT);
					return $this->siteView->showLobby();
					break;
	
				case SiteView::ACTION_USER_FAILED_LOGIN:
					$this->siteView->setMessage(SiteView::MESSAGE_FAILED_LOGIN);
					return $this->siteView->showLobby();
					break;

				case SiteView::ACTION_USER_TRY_REGISTER:
					$postedRegCred = $this->siteView->getPostedRegCred();
					try {
						$regValidation = $this->siteModel->regNewUser($postedRegCred);
						$this->siteView->setMessage(SiteView::MESSAGE_REGISTER_SUCCESS);
						return $this->siteView->showLobby();
					} catch (Exception $e) {
						$this->siteView->setMessage($e->getMessage());
						return $this->siteView->showRegisterPage();
					}
					break;

				case SiteView::ACTION_USER_CREATE_QUIZZ_PAGE:

					return $this->siteView->showCreateQuizz();
					break;

				case SiteView::ACTION_USER_CREATE_NEW_QUIZZ:
					//starta nytt quizz - gör en ny rad i tabellen quizz. spara dess id i sesseion plus notifiera att du är inloggad på det quizzet
					$this->siteModel->setQuizzOrderValue(1);
					$this->siteModel->startNewQuizz($this->siteView->getQuizzName());

					return $this->siteView->showCreateQuizzQuestion();
					break;

				case SiteView::ACTION_USER_SUBMIT_QUESTION:
					//spara fråga i aktivt quizz
//					try {
						$this->siteModel->saveQuizzQuestion($this->siteView->getQuestionText());
						$this->siteModel->saveQuizzAlternatives($this->siteView->getAlternatives());
						$this->siteModel->setQuizzOrderValue($this->siteModel->getQuizzOrderValue()+1);
//					} catch (Exception $e) {
//						var_dump($e->getMessage());
//					}
					//Visa ny fråga
					return $this->siteView->showCreateQuizzQuestion();
					break;

				case SiteView::ACTION_USER_CHOSE_SPEC_QUIZZ_EDIT:
					//Hämta quizz användaren valt
					$quizzId = $this->siteView->getChosenItemId();
					$ret = $this->siteView->showChoseQuizzQuestion($quizzId);
					return $ret;
					break;

				case SiteView::ACTION_USER_GOTO_EDIT_QUIZZ:
					$questionId = $this->siteView->getChosenItemId(); 
					return $this->siteView->showEditQuizzQuestion($questionId);
					break;

				case SiteView::ACTION_USER_SAVE_EDIT_QUESTION:
					//Ta fram id för quizz också? med hjälp av questionId;
					$questionId = $this->siteView->getChosenItemId();
					$this->siteModel->saveEditedQuestion($questionId, $this->siteView->getQuestionText(), $this->siteView->getAlternatives());
					$quizzId = $this->siteModel->getQuizzIdFromQuestionId($questionId);
					$this->siteView->setMessage(SiteView::MESSAGE_EDIT_SUCCESS);
					return $this->siteView->showChoseQuizzQuestion(array_shift($quizzId));
					break;

				case SiteView::ACTION_USER_DELETE_QUIZZ:
					$quizzId = $this->siteView->getChosenItemId();
					$this->siteModel->deleteQuizz($quizzId);
					return $this->siteView->showLoggedInPage(); 
					break;

				case SiteView::ACTION_USER_RUN_QUIZZ:
					$quizzId = $this->siteView->getChosenItemId();

					//Kolla så att quizzet inte finns med i finishedquizzes
					if($this->siteModel->isQuizzDone((int) $quizzId)){
						$this->siteView->setMessage(SiteView::MESSAGE_QUIZZ_ALLREADY_PLAYED);
						return $this->siteView->showLoggedInPage();
					} 

					$this->siteModel->setQuizzOrderValue(1);
					$this->siteModel->setActiveQuizzRun($quizzId);
					$questionId = $this->siteModel->getQuestionIdFromOrderAndQuizzId(1, $quizzId);
					return $this->siteView->showRunQuizz($questionId, $quizzId, false);
					break;

				case SiteView::ACTION_USER_RUN_QUIZZ_GOTO_NEXT:
					$answerArray = $this->siteView->getAnswerArray();
					$this->siteModel->saveQuestionAnswer($answerArray, $this->siteModel->getActiveQuestionId());
					$quizzId = $this->siteView->getChosenItemId();
					$questionIdsInQuizz = $this->siteModel->getNumberOfQuestionsInQuizz($quizzId);
					$questionAmount = sizeof($questionIdsInQuizz);
					$newOrderValue = $this->siteModel->getQuizzOrderValue() + 1;
					$this->siteModel->setQuizzOrderValue($newOrderValue);
					$questionId = $this->siteModel->getQuestionIdFromOrderAndQuizzId($newOrderValue, $quizzId);

					if($questionAmount < $newOrderValue) {
						$this->siteModel->sumUpQuizzResult($questionIdsInQuizz);
						//Spara quizz-resultat i db
						$this->siteModel->saveFinishedResult($this->siteModel->getUserSession(), $quizzId);
						return $this->siteView->showLoggedInPage();
					} else if($questionAmount == $newOrderValue) {
						return $this->siteView->showRunQuizz($questionId, $quizzId, true);
					} else {
						return $this->siteView->showRunQuizz($questionId, $quizzId, false);
					}
					break;

				case SiteView::ACTION_TEACHER_CHOSES_STUDENT:
					$chosenUserId = $this->siteView->getChosenStudent();
					$this->siteView->setStudentResultsHTML($chosenUserId);
					return $this->siteView->showLoggedInPage();
					break;

				default:
					if($this->siteModel->isUserLoggedIn()) {
						return $this->siteView->showLoggedInPage();
					}
					return $this->siteView->showLobby();
					break;
			}	
		} catch (Exception $e) {

		}
	}
}