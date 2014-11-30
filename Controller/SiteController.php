<?php

require_once("./View/SiteView.php");
require_once("./Model/SiteModel.php");
require_once("RegisterController.php");

/*
 * Kontroller-klass
 *
 **/


class SiteController {

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
					
					//Om användaren är inloggad redan på cookie
					if($this->siteModel->isUserLoggedIn()) {
						return $this->siteView->showLoggedInPage();
					}
					
					//Om användaren kan logga in med inloggningsuppgifter
					if($this->siteModel->tryLogin($this->siteView->getPostedLoginCred())){
						$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_IN);
						return $this->siteView->showLoggedInPage();
					} 
					//Inloggningen har fallerat
					else {
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
					//Inmatade uppgifter från användaren hämtas
					$postedRegCred = $this->siteView->getPostedRegCred();
					try {
						//Försök görs för att registrera användaren.
						$regValidation = $this->siteModel->regNewUser($postedRegCred);
						$this->siteView->setMessage(SiteView::MESSAGE_REGISTER_SUCCESS);
						return $this->siteView->showLobby();
					} catch (Exception $e) {
						//Felmeddelande sätts om användaren matat in ogiltiga uppgiter på något sätt
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
					if(isset($_POST[SiteView::ACTION_USER_SAVE_QUESTION])) {
						//Formuläret har fyllts i korrekt
						if($this->siteView->isFormCorrectlyFilledIn()) {
							$this->siteModel->saveQuizzQuestion($this->siteView->getQuestionText());
							$this->siteModel->saveQuizzAlternatives($this->siteView->getAlternatives());
							$this->siteModel->setQuizzOrderValue($this->siteModel->getQuizzOrderValue()+1);
						} 
						//Formuläret har inte fyllts i korrekt
						else {
							$this->siteView->setMessage(SiteView::MESSAGE_FORM_WAS_NOT_CORRECT);
							return $this->siteView->showCreateQuizzQuestion();
						}
						return $this->siteView->showCreateQuizzQuestion();
					} else if (isset($_POST[SiteView::ACTION_USER_SAVE_QUESTION_FINISH])) {
						//m formulär är korrekt ifyllt
						if($this->siteView->isFormCorrectlyFilledIn()) {
							$this->siteModel->saveQuizzQuestion($this->siteView->getQuestionText());
							$this->siteModel->saveQuizzAlternatives($this->siteView->getAlternatives());
						} 
						//Formuläret är inte korrekt ifyllt
						else {
							$this->siteView->setMessage(SiteView::MESSAGE_FORM_WAS_NOT_CORRECT);
							return $this->siteView->showCreateQuizzQuestion();
						}
						return $this->siteView->showLoggedInPage();
					}
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

					//Kollar om quizzet är startat redan
					if($this->siteModel->isQuizzStartedByUser($quizzId)) {
						$quizzOrderValue = $this->siteModel->getResumeQuizzOrderValue($quizzId);
					} else {
						$quizzOrderValue = 1;
					}

					//Sätter värdet på vilken fråga som spelaren befinner sig på i quizset.
					$this->siteModel->setQuizzOrderValue($quizzOrderValue);
					$this->siteModel->setActiveQuizzRun($quizzId);
					$questionId = $this->siteModel->getQuestionIdFromOrderAndQuizzId($quizzOrderValue, $quizzId);
					return $this->siteView->showRunQuizz($questionId, $quizzId, false);
					break;

				//Anändaren väljer att gå till nästa fråga i quizzet
				case SiteView::ACTION_USER_RUN_QUIZZ_GOTO_NEXT:
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

				//UserAction att gå till sida för att få feedback opå om svaret på quizz-fråga var rätt eller fel.
				case SiteView::ACTION_USER_SHOW_RESULT_QUESTION;

					//Uppgifter om Quizz hämtas ut
					$quizzId = $this->siteView->getChosenItemId();
					$questionIdsInQuizz = $this->siteModel->getNumberOfQuestionsInQuizz($quizzId);
					$questionAmount = sizeof($questionIdsInQuizz);
					$newOrderValue = $this->siteModel->getQuizzOrderValue();

					//Användarens svar från frågan sparas.
					$answerArray = $this->siteView->getAnswerArray();
					$this->siteModel->saveQuestionAnswer($answerArray, $this->siteModel->getActiveQuestionId());

					$questionId = $this->siteModel->getActiveQuestionId();
					$userId = $this->siteModel->getUserSession();
					$quizzId = $this->siteView->getChosenItemId();

					//Hämtar uppgifteom användaren svarade rät tpå förra frågan.
					$didUserAnswerCorrect = $this->siteModel->didUserAnswerCorrect($questionId, $userId);

					//Om frågeOrningsVärdet är mer än antalet frågor i quizzet skickas användaren tillbakatill huvudmenyn
					if($questionAmount == $newOrderValue) {
						return $this->siteView->ShowQuestionResultPage($didUserAnswerCorrect, $quizzId, true);
					} 
					//Om det inte är sista frågan i quizzet
					else {
						return $this->siteView->ShowQuestionResultPage($didUserAnswerCorrect, $quizzId, false);
					}						
						
					break;

				//Visar meny för student
				case SiteView::ACTION_TEACHER_CHOSES_STUDENT:
					$chosenUserId = $this->siteView->getChosenStudent();
					$this->siteView->setStudentResultsHTML($chosenUserId);
					return $this->siteView->showLoggedInPage();
					break;

				//Visar meny för lärare
				case SiteView::ACTION_USER_RETURN_TO_MENU:
					//Om användare är inloggad
					if($this->siteModel->isUserLoggedIn()) {
						return $this->siteView->showLoggedInPage();
					}
					return $this->siteView->showLobby();
					break;

				default:
					//Om användare är inloggad
					if($this->siteModel->isUserLoggedIn()) {
						return $this->siteView->showLoggedInPage();
					}
					return $this->siteView->showLobby();
					break;
			}	
		} catch (Exception $e) {
			return SiteView::MESSAGE_ERROR_FATAL;
		}
	}
}