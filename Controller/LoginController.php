<?php

require_once("../phpProjekt/View/SiteView.php");
require_once("../phpProjekt/Model/SiteModel.php");
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
					//Verify LoginCredentials
					if($this->siteModel->tryLogin($this->siteView->getPostedLoginCred())){
						$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_IN);

						return $this->siteView->showLoggedInPage();
					} else {
						$this->siteView->setMessage(SiteView::MESSAGE_FAILED_LOGIN);
						return $this->siteView->showLobby();
					}
					break;

				case SiteView::ACTION_USER_LOGS_OUT:
					$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_OUT);
					return $this->siteView->showLobby();
					break;
	
				case SiteView::ACTION_USER_FAILED_LOGIN:
					$this->siteView->setMessage(SiteView::MESSAGE_FAILED_LOGIN);
					return $this->siteView->showLobby();
					break;

				case SiteView::ACTION_USER_TRY_REGISTER:

					$postedRegCred = $this->siteView->getPostedRegCred();
					
					//Kolla så att det har går att registrera användaren. Validera
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
					$this->siteModel->saveQuizzQuestion($this->siteView->getQuestionText());

					$this->siteModel->saveQuizzAlternatives($this->siteView->getAlternatives());

					$this->siteModel->setQuizzOrderValue($this->siteModel->getQuizzOrderValue()+1);

					//Visa ny fråga
					return $this->siteView->showCreateQuizzQuestion();
					break;

				default:
					return $this->siteView->showLobby();
					break;
			}	
		} catch (Exception $e) {

		}
	}
}