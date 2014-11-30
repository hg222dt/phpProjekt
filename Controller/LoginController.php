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
						if(isset($_POST[SiteView::ACTION_USER_SAVE_QUESTION])) {
							if($this->siteView->isFormCorrectlyFilledIn()) {
								$this->siteModel->saveQuizzQuestion($this->siteView->getQuestionText());
								$this->siteModel->saveQuizzAlternatives($this->siteView->getAlternatives());
								$this->siteModel->setQuizzOrderValue($this->siteModel->getQuizzOrderValue()+1);
							} else {
								$this->siteView->setMessage(SiteView::MESSAGE_FORM_WAS_NOT_CORRECT);
								return $this->siteView->showCreateQuizzQuestion();
							}
							return $this->siteView->showCreateQuizzQuestion();
						} else if (isset($_POST[SiteView::ACTION_USER_SAVE_QUESTION_FINISH])) {
							if($this->siteView->isFormCorrectlyFilledIn()) {
								$this->siteModel->saveQuizzQuestion($this->siteView->getQuestionText());
								$this->siteModel->saveQuizzAlternatives($this->siteView->getAlternatives());
							} else {
								$this->siteView->setMessage(SiteView::MESSAGE_FORM_WAS_NOT_CORRECT);
								return $this->siteView->showCreateQuizzQuestion();
							}
							return $this->siteView->showLoggedInPage();
						}
//					} catch (Exception $e) {
//						var_dump($e->getMessage());
//					}
					//Visa ny fråga
					
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
					return $this->siteView->showLoggedInPage();
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


					//Kolla om quizzet är startat
					//Ta fram ordervalue på quizz-frågan


					if($this->siteModel->isQuizzStartedByUser($quizzId)) {
						$quizzOrderValue = $this->siteModel->getResumeQuizzOrderValue($quizzId);
					} else {
						$quizzOrderValue = 1;
					}


					$this->siteModel->setQuizzOrderValue($quizzOrderValue);
					$this->siteModel->setActiveQuizzRun($quizzId);
					$questionId = $this->siteModel->getQuestionIdFromOrderAndQuizzId($quizzOrderValue, $quizzId);
					return $this->siteView->showRunQuizz($questionId, $quizzId, false);
					break;

				case SiteView::ACTION_USER_RUN_QUIZZ_GOTO_NEXT:
					//$answerArray = $this->siteView->getAnswerArray();
					//$this->siteModel->saveQuestionAnswer($answerArray, $this->siteModel->getActiveQuestionId());
					$quizzId = $this->siteView->getChosenItemId();
					$questionIdsInQuizz = $this->siteModel->getNumberOfQuestionsInQuizz($quizzId);
					$questionAmount = sizeof($questionIdsInQuizz);
					$newOrderValue = $this->siteModel->getQuizzOrderValue() + 1;
					$this->siteModel->setQuizzOrderValue($newOrderValue);
					$questionId = $this->siteModel->getQuestionIdFromOrderAndQuizzId($newOrderValue, $quizzId);

					//Om frågeOrningsVärdet är mer än antalet frågor i quizzet skickas användaren tillbakatill huvudmenyn
					if($questionAmount < $newOrderValue) {
						$resultDecimal = $this->siteModel->saveFinishedResult($this->siteModel->getUserSession(), $quizzId);
						$this->siteView->setResultMessage($resultDecimal);
						return $this->siteView->showLoggedInPage();
					} 
					//Om det är sista frågan i quizzet
					else if($questionAmount == $newOrderValue) {
						return $this->siteView->showRunQuizz($questionId, $quizzId, true);
					} 
					//Om det inte är sista frågan i quizzet
					else {						
						return $this->siteView->showRunQuizz($questionId, $quizzId, false);
					}
					break;

				case SiteView::ACTION_USER_SHOW_RESULT_QUESTION;


					$quizzId = $this->siteView->getChosenItemId();
					$questionIdsInQuizz = $this->siteModel->getNumberOfQuestionsInQuizz($quizzId);
					$questionAmount = sizeof($questionIdsInQuizz);
					$newOrderValue = $this->siteModel->getQuizzOrderValue();




					$answerArray = $this->siteView->getAnswerArray();
					$this->siteModel->saveQuestionAnswer($answerArray, $this->siteModel->getActiveQuestionId());

					$questionId = $this->siteModel->getActiveQuestionId();
					$userId = $this->siteModel->getUserSession();
					$quizzId = $this->siteView->getChosenItemId();

					$didUserAnswerCorrect = $this->siteModel->didUserAnswerCorrect($questionId, $userId);





					//Om frågeOrningsVärdet är mer än antalet frågor i quizzet skickas användaren tillbakatill huvudmenyn
					if($questionAmount == $newOrderValue) {
						return $this->siteView->ShowQuestionResultPage($didUserAnswerCorrect, $quizzId, true);
					} 
					//Om det inte är sista frågan i quizzet
					else {
						return $this->siteView->ShowQuestionResultPage($didUserAnswerCorrect, $quizzId, false);
					}						
						




					//return $this->siteView->ShowQuestionResultPage($didUserAnswerCorrect, $quizzId);
					break;

				case SiteView::ACTION_TEACHER_CHOSES_STUDENT:
					$chosenUserId = $this->siteView->getChosenStudent();
					$this->siteView->setStudentResultsHTML($chosenUserId);
					return $this->siteView->showLoggedInPage();
					break;

				case SiteView::ACTION_USER_RETURN_TO_MENU:
					if($this->siteModel->isUserLoggedIn()) {
						return $this->siteView->showLoggedInPage();
					}
					return $this->siteView->showLobby();
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