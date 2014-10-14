<?php

//require_once("phpProjekt/Model/SiteModel.php");

class SiteView {

	const ACTION_USER_GOTO_REGISTER = "userGotoRegister";
	const ACTION_USER_LOGS_OUT = "userLogsOut";
	const ACTION_USER_LOGS_IN_DEFAULT = "userLogsIn";
	const ACTION_USER_FAILED_LOGIN = "userFailedLogin";

	const MESSAGE_USER_LOGGED_OUT = "You logged out!";
	const MESSAGE_USER_LOGGED_IN = "You are logged in!";
	const MESSAGE_FAILED_LOGIN = "You desvärre failed login.";
	const MESSAGE_WELCOME = "Hej and welcome. Play quiz pliiz";

	private $siteModel;
	private $pageMessage;

	public function __construct() {
		//$this->siteModel->$siteModel;
	}

	public function getUserAction() {
		switch(key($_GET)) {

			case SiteView::USER_GOTO_REGISTER):
				return SiteView::USER_GOTO_REGISTER;
				break;

			case SiteView::USER_LOGS_OUT:
				return SiteView::USER_LOGS_OUT;
				break;

			case SiteView::USER_LOGS_IN_DEFAULT:
				return SiteView::USER_LOGS_IN_DEFAULT;
				break;

			case SiteView::USER_FAILED_LOGIN:
				return SiteView::USER_FAILED_LOGIN;
				break;
		}
	}

	public function setMessage($message) {
		$this->pageMessage = '<p>' . $message . '</p>';
	}


	public function showLobby() {
		$ret = "showLobby " . $this->pageMessage;
		return $ret;
	}

	public function showRegisterPage() {
		$ret = "showRegisterPage " . $this->pageMessage;
		return $ret;
	}

	public function showLoggedInPage() {
		$ret = "showLoggedInPage " . $this->pageMessage;
		return $ret;
	}	
}