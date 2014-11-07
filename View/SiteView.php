<?php

require_once("./Model/SiteModel.php");
require_once("./View/PostedLoginCred.php");
require_once("./View/PostedRegCred.php");

class SiteView {

	const ACTION_USER_GOTO_REGISTER = "userGotoRegister";
	const ACTION_USER_LOGS_OUT = "userLogsOut";
	const ACTION_USER_LOGS_IN_DEFAULT = "userTryLogIn";
	const ACTION_USER_FAILED_LOGIN = "userFailedLogin";
	const ACTION_USER_TRY_REGISTER = "userTryRegister";
	const ACTION_USER_CREATE_QUIZZ_PAGE = "createQuizzPage";
	const ACTION_USER_CREATE_NEW_QUIZZ = "createNewQuizz";
	const ACTION_USER_SUBMIT_QUESTION = "userSubmitQuestion";
	const ACTION_USER_CHOSE_SPEC_QUIZZ_EDIT = "getSpecQuizzPage";
	const ACTION_USER_GOTO_EDIT_QUIZZ = "userGotoEditQuizz";
	const ACTION_USER_SAVE_EDIT_QUESTION = "saveEditQuestion";
	const ACTION_USER_DELETE_QUIZZ = "deleteQuizz";
	const ACTION_USER_RUN_QUIZZ = "runQuizz";
	const ACTION_USER_RUN_QUIZZ_GOTO_NEXT = "runQuizzGoToNext";

	const MESSAGE_USER_LOGGED_OUT = "You logged out!";
	const MESSAGE_USER_LOGGED_IN = "You are logged in!";
	const MESSAGE_FAILED_LOGIN = "You desvärre failed login.";
	const MESSAGE_REGISTER_SUCCESS = "Registreringen lyckades!";
	const MESSAGE_EDIT_SUCCESS = "The question is saved!";

	const NAME_EMPTY_ALTERNATIVE_INPUT = "No alternative";

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

			case SiteView::ACTION_USER_GOTO_EDIT_QUIZZ:
				return SiteView::ACTION_USER_GOTO_EDIT_QUIZZ;
				break;

			case SiteView::ACTION_USER_CHOSE_SPEC_QUIZZ_EDIT:
				return SiteView::ACTION_USER_CHOSE_SPEC_QUIZZ_EDIT;
				break;

			case SiteView::ACTION_USER_SAVE_EDIT_QUESTION:
				return SiteView::ACTION_USER_SAVE_EDIT_QUESTION;
				break;

			case SiteView::ACTION_USER_DELETE_QUIZZ:
				return SiteView::ACTION_USER_DELETE_QUIZZ;
				break;

			case SiteView::ACTION_USER_RUN_QUIZZ:
				return SiteView::ACTION_USER_RUN_QUIZZ;
				break;

			case SiteView::ACTION_USER_RUN_QUIZZ_GOTO_NEXT:
				return SiteView::ACTION_USER_RUN_QUIZZ_GOTO_NEXT;
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
</form>
		";

		return $ret;
	}

	public function showRegisterPage() {
		$ret = "
<h2>Skapa ett nytt konto.</h2>
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
</form>
		";

		return $ret;
	}

	public function showLoggedInPage() {

		switch($this->siteModel->getSessionUserRole()) {
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

		$questionId = $this->siteModel->getQuizzOrderValue();

		$ret="
<h2>Fråga $questionId</h1>
<form action='?userSubmitQuestion' method='post'>
	<label for='questionText'>Skriv fråga:</label>
	<br>
	<textarea rows='4' cols='50' id='questionText' name='questionText'></textarea>
	
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

	public function showEditQuizzQuestion($questionId) {

		//baserat på questionid, hämta: frågetext, alternativ, och rätt eller fel. samt frågeordning.
		//gör så att skicka editerar befintliga rader i databasen istället för att göra nya.

		$questionObj = $this->siteModel->getQuestionObject($questionId);

		$orderValue = array_shift($questionObj->questionOrder);
		$questionText = array_shift($questionObj->questionText);

		$ret="
<h2>Fråga $orderValue</h1>
<form action='?saveEditQuestion=$questionId' method='post'>
	<label for='questionText'>Skriv fråga:</label>
	<br>
	<textarea rows='4' cols='50' id='questionText' name='questionText' value=''>$questionText</textarea>
	<br>
	<label>Svarsalternativ: Fyll i så många alternativ du önskar. </label>
	<br>
	" . $this->getAlternativesInput($questionObj->alternatives) . "
	<br>
	<input type='submit' name='save' value='Uppdatera fråga'>
</form>
		";

		return $ret;
	}


	public function showRunQuizz($questionId, $quizzId, $lastquestion) {

		$this->siteModel->setActiveQuestionId($questionId);
		//var_dump($this->siteModel->getActiveQuestionId());

		$questionObj = $this->siteModel->getQuestionObject($questionId);
		$questionText = array_shift($questionObj->questionText);
		$alternatives = $questionObj->alternatives;
		$questionOrderArr = $questionObj->questionOrder;
		$questionOrder = $questionOrderArr['QuizzOrderValue'];

		if($lastquestion == true) {
			$buttonText = "Lämna in";
		} else {
			$buttonText = "Nästa fråga";
		}

		return $this->getRunQuizzHTML($questionOrder, $questionText, $alternatives, $quizzId, $buttonText);
	}

	public function getRunQuizzHTML($orderValue, $questionText, $alternatives, $quizzId, $buttonText) {

		$ret= "
<h2>Fråga $orderValue</h1>
<form action='?runQuizzGoToNext=$quizzId' method='post'>
	<br>
	<p>$questionText<P>
	<br>
	<label>Svarsalternativ:</label>
	<br>
	" . $this->getAlternativesLabels($alternatives) . "
	<br>
	<input type='submit' name='nextQuestion' value='$buttonText'>
</form>
		";

		return $ret;
	}

	public function getAlternativesLabels($alternatives) {
		$alternativeTexts = $alternatives[0];
		$correctAnswers = $alternatives[1];
		$alternativeIds = $alternatives[2];

		$ret="
<table border='0'>
		";

		for($i=0; $i<5; $i++) {

			$number = $i+1;

			$alternativeText = $alternativeTexts[$i];
			$alternativeId = $alternativeIds[$i];

			if($alternativeText != SiteView::NAME_EMPTY_ALTERNATIVE_INPUT){
				
			$firstSelected = "";
			$secondSelected = "";

			if($correctAnswers[$i] == 1) {
				$firstSelected = "checked";
			} else if ($correctAnswers[$i] == 2) {
				$secondSelected = "checked";
			}
			
			$ret .= "
	<tr>
    	<td><span>$alternativeText</span></td>
    	<td><input type='checkbox' name='userAnswerList[]' value='$alternativeId'/></td>
    </tr>
			";

			}
		}

		$ret .= "
</table>
		";

		return $ret;

	}


	public function getAlternativesInput($alternatives) {

		$alternativeTexts = $alternatives[0];
		$correctAnswers = $alternatives[1];

		$ret="";

		for($i=0; $i<5; $i++) {

			$number = $i+1;

			$alternativeText = $alternativeTexts[$i];

			$firstSelected = "";
			$secondSelected = "";

			if($correctAnswers[$i] == 1) {
				$firstSelected = "checked";
			} else if ($correctAnswers[$i] == 2) {
				$secondSelected = "checked";
			}
			
			$ret .= "
<input type='text' size='20' name='posted_alternative$number' value='$alternativeText'>
<input type='radio' name='correctAnswer$number' value='1' $firstSelected>Rätt</input>
<input type='radio' name='correctAnswer$number' value='2' $secondSelected>Fel</input> 
<br>
			";
		}

		return $ret;
	}

	public function showChoseQuizzQuestion($quizzId) {
		//Hämta hur många frågor som har skapats
		$questionArray = $this->siteModel->getNumberOfQuestionsInQuizz($quizzId);
		$ret = $this->pageMessage  . "<br>";
		$counter = 0;

		foreach ($questionArray as $key => $value) {
			$counter++;
			$ret .= "
<a href='?userGotoEditQuizz=$value'>Fråga  . $counter</a><br>
			";
		}

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

	public function getUserQuizzHTML() {
		//Hämta alla användarens quizz
		$userQuizzes = $this->siteModel->getUserQuizzes();
		$userQuizzIds = $this->siteModel->getUserQuizzIds();

		$ret = "";

		foreach ($userQuizzes as $key => $value) {
			$quizzId = $userQuizzIds[$key];
			$key = $key + 1;
			$ret .= "
<a href='?getSpecQuizzPage=$quizzId'>" . $key . ". " . $value . "</a> <a href='?deleteQuizz=$quizzId'> Ta bort</a><br>
			";
		}

		$ret .= "
</form>
		";

		return $ret;
	}

	public function getAllQuizzForPlayHTML() {
		//Hämta alla användarens quizz
		$userQuizzes = $this->siteModel->getAllQuizzes();
		$userQuizzIds = $this->siteModel->getAllQuizzIds();

		$quizzResults = $this->siteModel->getQuizzResultsUser();

		$ret = "";

		foreach ($userQuizzes as $key => $value) {
			
			$quizzId = $userQuizzIds[$key];
			$resultString = "";

			foreach ($quizzResults as $key2 => $resultQuizz) {
				if($key2 == $quizzId) {
					$resultString = "<span>$resultQuizz %</span>";
				}	
			}

			$key = $key + 1;
			$ret .= "
<tr>
	<td>
		<a href='?runQuizz=$quizzId'>" . $key . ". " . $value . "</a>
	</td>
	<td>
		$resultString
	</td>
</tr>
			";
		}

		$ret .= "
</form>
		";

		return $ret;
	}

	public function getTeacherLayout() {

		$currentUser = $this->siteModel->currentUser;
		$username = $this->siteModel->getUserSessionUsername();

		$ret = "
<h2>Välkommen till din sida, lärare. Du är användarnamn: $username</h2>
" . $this->pageMessage . "
<h2>Dina skapade quizz</h2>
" . $this->getUserQuizzHTML() . "
<a href='?createQuizzPage'>Skapa ett nytt quizzgamee!</a><br>
<a href='?userLogsOut'>Logga ut</a>
		";

		return $ret;
	}

	public function getStudentLayout() {

		$currentUser = $this->siteModel->getUserSessionUsername();

		$ret = "
<h2>Välkommen till din sida, elev. Du är användarnamn: $currentUser</h2>
" . $this->pageMessage . "

<h2>Spela ett quizz</h2>
<table>
	<tr>
		<th>Quizz</th>
		<th>Resultat</th>
	</tr>
" . $this->getAllQuizzForPlayHTML() . "
</table>
<a href='?userLogsOut'>Logga ut</a>
		";
		
		return $ret;
	}

	public function getAlternatives() {

		$alternatives = array();

		$alternativeTexts = array();
		$alternativeCorrects = array(); 

		for($i=1; $i<6; $i++) {

			if(isset($_POST['posted_alternative'. $i]) && isset($_POST['correctAnswer'. $i])) {
				$alternativeTexts[$i-1] = $_POST['posted_alternative' . $i];
				$alternativeCorrects[$i-1] = $_POST['correctAnswer' . $i];
//			} else if(isset($_POST['posted_alternative'. $i]) && !isset($_POST['correctAnswer'. $i])) {
//				throw new Exception("svarsalternativ fattas");
			} else {
				$alternativeTexts[$i-1] = SiteView::NAME_EMPTY_ALTERNATIVE_INPUT;
				$alternativeCorrects[$i-1] = 0;
			}
		}

		$alternatives[0] = $alternativeTexts;
		$alternatives[1] = $alternativeCorrects;

		return $alternatives;
	}

	/*
if(isset($_POST['posted_alternative'. $i]) && isset($_POST['correctAnswer'. $i])) {
				$alternativeTexts[$i-1] = $_POST['posted_alternative' . $i];
				$alternativeCorrects[$i-1] = $_POST['correctAnswer' . $i];
			//} else if (!isset($_POST['posted_alternative'. $i])) {
			//	throw new Exception("Någon rad är inte ifylld");
			} else if ($_POST['posted_alternative'. $i] == "EMPTY") {
				$alternativeCorrects[$i-1] = 0;
			} else if (!$_POST['posted_alternative'. $i] == "EMPTY" && !isset($_POST['correctAnswer'. $i])) {
				throw new Exception("Fel med ifyllning");

			} else if (isset($_POST['posted_alternative'. $i]) && !isset($_POST['correctAnswer'. $i]) && $_POST['posted_alternative'. $i] != "EMPTY") {
				throw new Exception("korrekt svar fattas någonstans.");
			} else  {
				$alternativeTexts[$i-1] = "EMPTY";
				$alternativeCorrects[$i-1] = 0;
			}
	*/

	public function getQuizzName() {
		return $_POST['quizzName'];
	}

	public function getQuestionText() {
		return $_POST['questionText'];
	}

	public function getChosenItemId() {
		$url = $_SERVER['REQUEST_URI'];
		return substr($url, strpos($url, "=") + 1);
	}

	public function getAnswerArray() {
		if(!empty($_POST['userAnswerList'])) {
			$answerArray = array();
    		foreach($_POST['userAnswerList'] as $answer) {
        	    array_push($answerArray, $answer);
    		}
    		return $answerArray;
		}
	}
}