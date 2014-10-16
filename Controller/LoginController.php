<?php

require_once("../phpProjekt/View/SiteView.php");
require_once("../phpProjekt/Model/SiteModel.php");

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
					
					//Kolla om lösenordet är inte är som upprepade lösenordet
					if(strcmp($postedRegCred->password, $postedRegCred->repeatedPassword) != 0) {
						$this->siteView->setMessage("Lösenordet och det upprepade lösenordet stämmer inte överens");
						return $this->siteView->showRegisterPage();
					}

					//Kolla så att det har går att registrera användaren
					try {
						$regValidation = $this->siteModel->regNewUser($postedRegCred);
						$this->siteView->setMessage("Registreringen lyckades!");
						return $this->siteView->showLobby();
					} catch (Exception $e) {
						$this->siteView->setMessage($e->getMessage());
						return $this->siteView->showRegisterPage();
					}

					break;

				default:
					return $this->siteView->showLobby();
					break;
			}	
		} catch (Exception $e) {

		}
	}
}