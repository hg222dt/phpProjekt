<?php

//require_once("phpProjekt/Model/SiteModel.php");
require_once("phpProjekt/View/SiteView.php");

class LoginController {

	private $siteView;
	private $siteModel;

	public function __construct() {
		//$siteModel = new SiteModel();
		$siteView = new SiteView();
	}

	public function doControll() {

		switch($this->siteView->getUserAction) {

			case SiteView::ACTION_USER_GOTO_REGISTER:
				return $this->siteView->showRegisterPage();
				break;

			case SiteView::ACTION_USER_LOGS_OUT:
				$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_OUT);
				return $this->siteView->showLobby();
				break;

			case SiteView::ACTION_USER_LOGS_IN_DEFAULT:
				$this->siteView->setMessage(SiteView::MESSAGE_USER_LOGGED_IN);
				return $this->siteView->showLoggedInPage();
				break;

			case SiteView::ACTION_USER_FAILED_LOGIN:
				$this->siteView->setMessage(SiteView::MESSAGE_FAILED_LOGIN);
				return $this->siteView->showLobby();
				break;

			default:
				$this->siteView->setMessage(SiteView::MESSAGE_WELCOME);
				return $this->siteView->showLobby();
				break;
		}	
	}
}