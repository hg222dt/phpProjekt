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
	const ACTION_USER_CREATE_QUIZZ_PAGE = "createQuizzPage";
	const ACTION_USER_CREATE_NEW_QUIZZ = "createNewQuizz";
	const ACTION_USER_SUBMIT_QUESTION = "userSubmitQuestion";

	const MESSAGE_USER_LOGGED_OUT = "You logged out!";
	const MESSAGE_USER_LOGGED_IN = "You are logged in!";
	const MESSAGE_FAILED_LOGIN = "You desvärre failed login.";
	const MESSAGE_REGISTER_SUCCESS = "Registreringen lyckades!";

	private $siteModel;
	private $pageMessage;
	private $usernamePlaceholder;

	private $currentUser;

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

			case SiteView::ACTION_USER_CREATE_QUIZZ_PAGE:
				return SiteView::ACTION_USER_CREATE_QUIZZ_PAGE;
				break;

			case SiteView::ACTION_USER_CREATE_NEW_QUIZZ:
				return SiteView::ACTION_USER_CREATE_NEW_QUIZZ;
				break;

			case SiteView::ACTION_USER_SUBMIT_QUESTION:
				return SiteView::ACTION_USER_SUBMIT_QUESTION;
				break;

		}
	}

	//Sets a message on top of the rendered page
	public function setMessage($message) {
		$this->pageMessage = '<p>' . $message . '</p>';
	}

	public function showLobby() {

		$ret = "
<h1>Välkommen till Quizz-siten</h1>
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

		switch($this->siteModel->currentUser->userRole) {
			case SiteModel::USER_TYPE_TEACHER:
				return $this->getTeacherLayout();
				break;

			case SiteModel::USER_TYPE_STUDENT:
				return $this->getStudentLayout();
				break;

			default:
				throw new Exeption("Userrole is invalid.");
				break;
		}
	}

	public function showCreateQuizz() {

		$ret="
<h2>Skapa ett nytt quizz!</h2>
<p>Börja genom att namnge ditt quizz och trycka ok! </p>
<br>
<form action='?createNewQuizz' method='post'>
<input type='text' size='20' name='quizzName' value=''>
<br>
<input type='submit' name='saveQuestion' value='Skapa quizz!'>
</form>
		";

		return $ret;
	}

	public function showCreateQuizzQuestion() {

		$ret="
<h2>Fråga 1</h1>
<form action='?userSubmitQuestion' method='post'>
	<label for='questionText'>Skriv fråga:</label>
	<br>
	<textarea rows='4' cols='50' id='questionText' name='questionText'>
	</textarea>
	
	<br>
	<label>Svarsalternativ: Fyll i så många alternativ du önskar. </label>
	<br>
	<input type='text' size='20' name='posted_alternative1' value=''>
	<input type='radio' name='correctAnswer1' value='1'>Rätt</input>
	<input type='radio' name='correctAnswer1' value='2'>Fel</input> 
	<br>
	<input type='text' size='20' name='posted_alternative2' value=''>
	<input type='radio' name='correctAnswer2' value='1'>Rätt</input>
	<input type='radio' name='correctAnswer2' value='2'>Fel</input>
	<br>
	<input type='text' size='20' name='posted_alternative3' value=''>
	<input type='radio' name='correctAnswer3' value='1'>Rätt</input>
	<input type='radio' name='correctAnswer3' value='2'>Fel</input>
	<br>
	<input type='text' size='20' name='posted_alternative4' value=''>
	<input type='radio' name='correctAnswer4' value='1'>Rätt</input>
	<input type='radio' name='correctAnswer4' value='2'>Fel</input>
	<br>
	<input type='text' size='20' name='posted_alternative5' value=''>
	<input type='radio' name='correctAnswer5' value='1'>Rätt</input>
	<input type='radio' name='correctAnswer5' value='2'>Fel</input>
	<br>
	<input type='submit' name='saveQuestion' value='Spara fråga'>
</form>
		";

		return $ret;
	}


	public function getPostedLoginCred() {
		return new PostedLoginCred($_POST['posted_username'], $_POST['posted_password']);
	}

	public function getPostedRegCred() {

		if(isset($_POST['posted_role'])) {
			return new PostedRegCred($_POST['posted_username'], $_POST['posted_password'], $_POST['posted_repeated'], $_POST['posted_role']);
		} else {
			return new PostedRegCred($_POST['posted_username'], $_POST['posted_password'], $_POST['posted_repeated'], "noInput");
		}
	}

	public function getTeacherLayout() {

		$currentUser = $this->siteModel->currentUser;

		$ret = "<h2>Välkommen till din sida, lärare. Du är användarnamn: $currentUser->username</h2>
		" . $this->pageMessage . "

		<a href='?createQuizzPage'>Skapa ett nytt quizzgamee!</a>

		<a href='?userLogsOut'>Logga ut</a>";

		return $ret;
	}

	public function getStudentLayout() {

		$currentUser = $this->siteModel->currentUser;

		$ret = "<h2>Välkommen till din sida, elev. Du är användarnamn: $currentUser->username</h2>
		" . $this->pageMessage . "
		<a href='?userLogsOut'>Logga ut</a>";
		
		return $ret;
	}

	public function getAlternatives() {

		$alternatives = array();

		for($i=1; $i<6; $i++) {

			if($_POST['posted_alternative'. $i] != "") {
				$alternatives[$_POST['posted_alternative' . $i]] = $_POST['correctAnswer' . $i];
			}
		}

		return $alternatives;
	}

	public function getQuizzName() {
		return $_POST['quizzName'];
	}

	public function getQuestionText() {
		return $_POST['questionText'];
	}
}