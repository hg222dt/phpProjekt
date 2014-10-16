<?php

require_once("../phpProjekt/Model/SiteModel.php");
require_once("../phpProjekt/View/PostedLoginCred.php");
require_once("../phpProjekt/View/PostedRegCred.php");

class SiteView {

	const ACTION_USER_GOTO_REGISTER = "userGotoRegister";
	const ACTION_USER_LOGS_OUT = "userLogsOut";
	const ACTION_USER_LOGS_IN_DEFAULT = "userTryLogIn";
	const ACTION_USER_FAILED_LOGIN = "userFailedLogin";
	const ACTION_USER_TRY_REGISTER = "userTryRegister";

	const MESSAGE_USER_LOGGED_OUT = "You logged out!";
	const MESSAGE_USER_LOGGED_IN = "You are logged in!";
	const MESSAGE_FAILED_LOGIN = "You desvärre failed login.";

	private $siteModel;
	private $pageMessage;
	private $usernamePlaceholder;

	public function __construct($siteModel) {
		$this->siteModel = $siteModel;
	}

	public function getUserAction() {
		switch(key($_GET)) {

			case SiteView::ACTION_USER_GOTO_REGISTER:
				return SiteView::ACTION_USER_GOTO_REGISTER;
				break;

			case SiteView::ACTION_USER_LOGS_OUT:
				return SiteView::ACTION_USER_LOGS_OUT;
				break;

			case SiteView::ACTION_USER_LOGS_IN_DEFAULT:
				return SiteView::ACTION_USER_LOGS_IN_DEFAULT;
				break;

			case SiteView::ACTION_USER_FAILED_LOGIN:
				return SiteView::ACTION_USER_FAILED_LOGIN;
				break;

			case SiteView::ACTION_USER_TRY_REGISTER:
				return SiteView::ACTION_USER_TRY_REGISTER;
				break;
		}
	}

	//Sets a message on top of the rendered page
	public function setMessage($message) {
		$this->pageMessage = '<p>' . $message . '</p>';
	}

	public function showLobby() {

		$ret = "<h1>Välkommen till Quizz-siten</h1>
<h2>Logga in eller skapa ett nytt konto.</h2>
<form action='?userTryLogIn' method='post'>
	<fieldset>
		<legend>Logga in med användarnamn och lösenord</legend>
		" . $this->pageMessage . "
		<label for='usrnameId'>Username</label>
		<input type='text' id='usrnameId' size='20' name='posted_username' value='$this->usernamePlaceholder'>
		<label for='passwordId'>Password</label>
		<input type='password' id='passwordId' size='20' name='posted_password' placeholder='********'>
		<input type='submit' name='loginFormPosted' value='Log in'>
	</fieldset>
	<h3>Eller registrera dig <a href='?userGotoRegister'>här</a>
</form>";

		return $ret;
	}

	public function showRegisterPage() {
		$ret = "<h2>Skapa ett nytt konto.</h2>
<form action='?userTryRegister' method='post'>
	<fieldset>
		<legend>Fyll i önskade användaruppgifter</legend>
		" . $this->pageMessage . "
		<label for='usrnameId'>Användarnamn</label>
		<input type='text' id='usrnameId' size='20' name='posted_username' value='$this->usernamePlaceholder'>
		<br>
		<label for='passwordId'>Lösenord</label>
		<input type='password' id='passwordId' size='20' name='posted_password' placeholder='********'>
		<br>
		<label for='passwordId'>Upprepa lösenord</label>
		<input type='password' id='repeatedPasswordId' size='20' name='posted_repeated' placeholder='********'>
		<br>
		<label for='keepLoggedInId'>Välj behörighet:</label>		
		<input type='radio' name='posted_role' value='1'>Lärare</input>
		<input type='radio' name='posted_role' value='2'>Elev</input> 
		<br>
		<input type='submit' name='loginFormPosted' value='Registrera'>
	</fieldset>
</form>";

		return $ret;
	}

	public function showLoggedInPage() {
		$ret = "<h2>Välkommen till din sida</h2>
		" . $this->pageMessage . "
		<a href='?userLogsOut'>Logga ut</a>";

		return $ret;
	}


	public function getPostedLoginCred() {
		return new PostedLoginCred($_POST['posted_username'], $_POST['posted_password']);
	}

	public function getPostedRegCred() {
		return new PostedRegCred($_POST['posted_username'], $_POST['posted_password'], $_POST['posted_repeated'], $_POST['posted_role']);
	}
}