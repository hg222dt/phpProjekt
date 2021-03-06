<?php

require_once("./Model/SiteModel.php");
require_once("./View/PostedLoginCred.php");
require_once("./View/PostedRegCred.php");

/*
 * KLass som hanterar sitens vy-relaterade data
 *
 **/

class SiteView {

	//Konstanter för användar-actions
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
	const ACTION_TEACHER_CHOSES_STUDENT = "teacherChosesUniqueStudent";
	const ACTION_USER_RUN_DONE_QUIZZ ="runDoneQuizz";
	const ACTION_USER_SAVE_QUESTION = "saveQuestion";
	const ACTION_USER_SAVE_QUESTION_FINISH = "saveAndQuit";
	const ACTION_USER_SHOW_RESULT_QUESTION = "showResultOfQuestion";
	const ACTION_USER_RETURN_TO_MENU = "returnToMenu";

	//Användarmeddlenaden
	const MESSAGE_USER_LOGGED_OUT = "Du har loggat ut!";
	const MESSAGE_USER_LOGGED_IN = "Du har loggat in!";
	const MESSAGE_FAILED_LOGIN = "Du har desvärre failat login.";
	const MESSAGE_REGISTER_SUCCESS = "Registreringen lyckades!";
	const MESSAGE_EDIT_SUCCESS = "Frågan sparades!";
	const MESSAGE_QUIZZ_ALLREADY_PLAYED = "Du har redan lämnat in detta quizz.";
	const MESSAGE_FORM_WAS_NOT_CORRECT = "Frågan fylldes inte i korrekt.";
	const MESSAGE_ERROR_FATAL = "Sidan kunde inte laddas.";

	const NAME_EMPTY_ALTERNATIVE_INPUT = "No alternative";

	private $siteModel;
	private $pageMessage;
	private $usernamePlaceholder;

	private $currentUser;

	public function __construct($siteModel) {
		$this->siteModel = $siteModel;
	}

	public function getUserAction() {

		//Hämtar ut vilken användar-action som valts
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

			case SiteView::ACTION_TEACHER_CHOSES_STUDENT:
				return SiteView::ACTION_TEACHER_CHOSES_STUDENT;
				break;

			case SiteView::ACTION_USER_RUN_DONE_QUIZZ:
				return SiteView::ACTION_USER_RUN_DONE_QUIZZ;
				break;

			case SiteView::ACTION_USER_SHOW_RESULT_QUESTION:
				return SiteView::ACTION_USER_SHOW_RESULT_QUESTION;
				break;

			case SiteView::ACTION_USER_RETURN_TO_MENU:
				return SiteView::ACTION_USER_RETURN_TO_MENU;
				break;
		}
	}

	//Sätter ett meddelande till toppen av sidan
	public function setMessage($message) {
		$this->pageMessage = '<p>' . $message . '</p>';
	}

	//Sätter resultat meddelande efter spelat quizz till användaren.
	public function setResultMessage($resultDecimal) {

		$resultInPercentage = round($resultDecimal * 100);

		$this->pageMessage = '<p>Du hade ' . $resultInPercentage . ' % rätt på quizzet!</p>';
	}

	//Sida för inloggning
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

	//Sida för att registera användare
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
		<label for='repeatedPasswordId'>Upprepa lösenord</label>
		<input type='password' id='repeatedPasswordId' size='20' name='posted_repeated' placeholder='********'>
		<br>
		<label for='keepLoggedInId'>Välj behörighet:</label>		
		<input type='radio' name='posted_role' value='1'>Lärare</input>
		<input type='radio' name='posted_role' value='2'>Elev</input> 
		<br>
		<label for='teacherPassword'>Om du angivit lärare, vänligen mata in lösenord du fått via e-post.</label>
		<input type='password' id='teacherPassword' size='20' name='posted_teacher_password' placeholder='********'>
		<br>
		<input type='submit' name='loginFormPosted' value='Registrera'>
	</fieldset>
</form> 
<a href='?" . SiteView::ACTION_USER_RETURN_TO_MENU . "'> Tillbaka till huvudmenyn</a>
		";

		return $ret;
	}

	//Sida inloggad användare
	public function showLoggedInPage() {

		switch($this->siteModel->getSessionUserRole()) {

			//En lärare har loggat in
			case SiteModel::USER_TYPE_TEACHER:
				return $this->getTeacherLayout();
				break;

			//En student har loggat in
			case SiteModel::USER_TYPE_STUDENT:
				return $this->getStudentLayout();
				break;

			default:
				throw new Exception("Userrole is invalid.");
				break;
		}
	}

	//Första sida för att skapa ett quizz
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
<a href='?" . SiteView::ACTION_USER_RETURN_TO_MENU . "'> Tillbaka till huvudmenyn</a>
		";

		return $ret;
	}

	//Sida för att skapa fråga till ett quizz
	public function showCreateQuizzQuestion() {

		$questionId = $this->siteModel->getQuizzOrderValue();

		$ret="
<h2>Fråga $questionId</h1>
" . $this->pageMessage . "
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
	<br>
	<input type='submit' name='saveAndQuit' value='Spara och avsluta'>
</form>
		";

		return $ret;
	}

	//Editera quizz
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
	<label>Svarsalternativ: </label>
	<br>
	" . $this->getAlternativesInput($questionObj->alternatives) . "
	<br>
	<input type='submit' name='save' value='Uppdatera fråga och återgå till huvudmenyn'>
</form>
		";

		return $ret;
	}

	//Sida för att spela quizz
	public function showRunQuizz($questionId, $quizzId, $lastquestion) {

		$this->siteModel->setActiveQuestionId($questionId);

		$questionObj = $this->siteModel->getQuestionObject($questionId);

		try {
			$questionText = array_shift($questionObj->questionText);
		} catch (Exception $e) {
			throw $e;
		}	

		$alternatives = $questionObj->alternatives;
		$questionOrderArr = $questionObj->questionOrder;
		$questionOrder = $questionOrderArr['QuizzOrderValue'];

		if($lastquestion == true) {
			$buttonText = "Lämna in";
		} else {
			$buttonText = "Nästa fråga";
		}

		$submitButtonName = SiteView::ACTION_USER_SHOW_RESULT_QUESTION;

		return $this->getRunQuizzHTML($questionOrder, $questionText, $alternatives, $quizzId, $buttonText, $submitButtonName);
	}

	//Html gernereras här för att stoppas i vyn för att spela quizz
	public function getRunQuizzHTML($orderValue, $questionText, $alternatives, $quizzId, $buttonText, $submitButtonName) {

		$ret= "
<h2>Fråga $orderValue</h1>
<form action='?$submitButtonName=$quizzId' method='post'>
	<br>
	<p>$questionText<P>
	<br>
	<label>Svarsalternativ:</label>
	<br>
	" . $this->getAlternativesLabels($alternatives) . "
	<br>
	<input type='submit' name='showResultQuestion' value='$buttonText'>
</form>
<a href='?" . SiteView::ACTION_USER_RETURN_TO_MENU . "'> Tillbaka till huvudmenyn</a>
		";

		return $ret;
	}

	//Hämta vy-data för de olika alternativen till en fråga
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

	//Hämtar förifyllda alternativ på fråga som ska editeras
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
			
			$disabledAttribute = "";

			if($alternativeText == "No alternative") {
				$disabledAttribute = "disabled";
			}

			$ret .= "
<input type='text' size='20' name='posted_alternative$number' value='$alternativeText' $disabledAttribute>
<input type='radio' name='correctAnswer$number' value='1' $firstSelected $disabledAttribute>Rätt</input>
<input type='radio' name='correctAnswer$number' value='2' $secondSelected $disabledAttribute>Fel</input> 
<br>
			";
		}

		return $ret;
	}

	//Sida för att välja vilken quizz-fråga som ska editeras
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

	//Hämtar data som postats i formuläret för inloggning
	public function getPostedLoginCred() {
		return new PostedLoginCred($_POST['posted_username'], $_POST['posted_password']);
	}

	//Hämtar data som postats i formulär för registerering av ny användare
	public function getPostedRegCred() {

		//Skapar objekt för en ny användare		
		if(isset($_POST['posted_role'])) {
			$ret = new PostedRegCred($_POST['posted_username'], $_POST['posted_password'], $_POST['posted_repeated'], $_POST['posted_role']);

			if($_POST['posted_role'] == 1) {
				//Check för om användaren skrivit i lärarlösenordet korrekt om denne vill bli lärare.
				if(isset($_POST['posted_teacher_password'])) {
					$ret->teacherPassword = $_POST['posted_teacher_password'];
				}
			}

		} else {
			$ret =  new PostedRegCred($_POST['posted_username'], $_POST['posted_password'], $_POST['posted_repeated'], "noInput");
		}


		

		return $ret;
	}

	//Hämtar och presenterar alla quizz för att presenteras för en lärare
	public function getUserQuizzHTML() {
		//Hämta alla quizz för specifik lärare
		$userQuizzes = $this->siteModel->getUserQuizzes();
		$userQuizzIds = $this->siteModel->getUserQuizzIds();
		$amountDones = $this->siteModel->getAmountDoneQuizzes();
		$averageResults = $this->siteModel->getAverageResultsQuizzes();

		$ret = "";

		foreach ($userQuizzes as $key => $value) {
			$quizzId = $userQuizzIds[$key];

			$amountDone = "";
			$averageScore = "";

			foreach ($amountDones as $key2 => $value2) {
				if($key2 == $quizzId) {
					$amountDone = "<span>$value2 %</span>";
				}
			}

			foreach ($averageResults as $key3 => $value3) {
				if($key3 == $quizzId) {
					$averageScore = "<span>$value3 %</span>";
				}
			}
			
			$key = $key + 1;
			$ret .= "
<tr>
	<td>
		<span>" . $key . " . </span><a href='?getSpecQuizzPage=$quizzId'>" . $value . "
	</td>
	<td>
		</a> <a href='?deleteQuizz=$quizzId'> Ta bort</a>
	</td>
	<td>
		$amountDone
	</td>
	<td>
		$averageScore
	</td>
</tr>
			";
		}

		$ret .= "
</form>
		";

		return $ret;
	}

	//Hämtar alla quizz för att presenteras för en elev
	public function getAllQuizzForPlayHTML() {
		//Hämta alla användarens quizz
		$userQuizzes = $this->siteModel->getAllQuizzes();
		$userQuizzIds = $this->siteModel->getAllQuizzIds();

		$quizzResults = $this->siteModel->getQuizzResultsUser();

		$ret = "";

		foreach ($userQuizzes as $key => $value) {

			$quizzActionName = SiteView::ACTION_USER_RUN_QUIZZ;
			
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
		<a href='?$quizzActionName=$quizzId'>" . $key . ". " . $value . "</a>
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

	//Hämtar resultat för en elevs quizz.
	public function getStudentDataHTML($userId) {
		$userQuizzes = $this->siteModel->getAllQuizzes();
		$userQuizzIds = $this->siteModel->getAllQuizzIds();

		$quizzResults = $this->siteModel->getQuizzResultsSpecUser($userId);

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
		" . $key . ". " . $value . "
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

	private $studentResultsHTML = "";

	public function setStudentResultsHTML($chosenUserId) {
		$this->studentResultsHTML .="
<table>
	<tr>
		<th>
			Quizz
		</th>
		<th>
			Resultat
		</th>
	</tr>";
	
		$this->studentResultsHTML .= $this->getStudentDataHTML($chosenUserId) . "</table>";
	}

	//Hämtar huvudmeny för lärare
	public function getTeacherLayout() {
		$currentUser = $this->siteModel->currentUser;
		$username = $this->siteModel->getUserSessionUsername();

		$ret = "
<h2>Välkommen till din sida, lärare. Du är användarnamn: $username</h2>
" . $this->pageMessage . "
<br>
<a href='?createQuizzPage'>Tryck här för att skapa ett nytt quizzgame!</a><br>
<h2>Dina skapade quizz</h2>
<table border='1'>
	<tr>
		<th>
		Quizz
		</th>
		<th> 
		</th>
		<th>
		Andel inlämningar
		</th>
		<th>
		Snittresultat
		</th>
	</tr>
" . $this->getUserQuizzHTML() . "
</table>
<br>
<form action='?teacherChosesUniqueStudent' method='post'>
		<label>Se resultat för enskild student: </label>
		<br>
		<select name='uniqueStudent'>
		" . $this->getStudentList() . "
		</select>
		<input type='submit' name='studentChosen' value='Se elev'>
</form>

" . $this->studentResultsHTML . "

<br>
<a href='?userLogsOut'>Logga ut</a>
		";

		return $ret;
	}

	//Hämtar huvudmeny för student
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

	//Hämtar studentlista
	public function getStudentList() {

		$students = $this->siteModel->getStudentsNames();

		$ret = "";

		foreach ($students as $key => $value) {
			$ret .= "<option value='$key'>$value</option>";
		}

		return $ret;
	}

	//Hämtar alternativ till fråga i quizz
	public function getAlternatives() {

		$alternatives = array();

		$alternativeTexts = array();
		$alternativeCorrects = array(); 

		for($i=1; $i<6; $i++) {

			if(isset($_POST['posted_alternative'. $i]) && isset($_POST['correctAnswer'. $i])) {
				$alternativeTexts[$i-1] = $_POST['posted_alternative' . $i];
				$alternativeCorrects[$i-1] = $_POST['correctAnswer' . $i];
			} else {
				$alternativeTexts[$i-1] = SiteView::NAME_EMPTY_ALTERNATIVE_INPUT;
				$alternativeCorrects[$i-1] = 0;
			}
		}

		$alternatives[0] = $alternativeTexts;
		$alternatives[1] = $alternativeCorrects;

		return $alternatives;
	}

	public function getQuizzName() {
		return $_POST['quizzName'];
	}

	public function getQuestionText() {

		return $_POST['questionText'];
	}

	//Hämtar id ur url
	public function getChosenItemId() {
		$url = $_SERVER['REQUEST_URI'];
		return substr($url, strpos($url, "=") + 1);
	}

	//Hämtar array innhållandes aid på de svarsalternativ användaren valt i quizzfråga
	public function getAnswerArray() {
		if(!empty($_POST['userAnswerList'])) {
			$answerArray = array();
    		foreach($_POST['userAnswerList'] as $answer) {
        	    array_push($answerArray, $answer);
    		}
    		return $answerArray;
		}
	}

	//Hämtar postat id på student som läraren valt att se specifika uppgifter på
	public function getChosenStudent() {
		return $_POST['uniqueStudent'];
	}

	//Formuläret när man skapar quizzfråga måste minst innehålla en fråge-text
	public function isFormCorrectlyFilledIn() {
		if($this->getQuestionText() == null) {
			return false;
		}
		return true;
	}

	//Visar sida för användaren efter quizz-fråga, som ger feedback om användaren svarat rätt eller fel.
	public function ShowQuestionResultPage($userHasCorrectAnswer, $quizzId, $lastQuestion) {

		//Texten på knappen ändras beroend epå om det är sista frågan eller inte.
		if($lastQuestion) {
			$buttonText = "Lämna in!";
		} else {
			$buttonText = "Nästa fråga!";
		}

		$buttonName= SiteView::ACTION_USER_RUN_QUIZZ_GOTO_NEXT;
		
		
		//Om användaren har svart rätt
		if($userHasCorrectAnswer) {
			$retStr = "<div class='feedbackContainerRightAnswer'>";
			$retStr .= "Rätt svar! ";
			
		} 
		//Om användaren har svarat fel.
		else {
			$retStr = "<div class='feedbackContainerWrongAnswer'>";
			$retStr .= "Fel svar! ";
		}

		$retStr .= "<br> <a href='?$buttonName=$quizzId'>$buttonText</a> <br>";

		//Om det inte är sista frågan, så ska knapp för att gå tillbaka till huvudmenyn visas.
		if(!$lastQuestion) {
			$retStr .= "<a href='?" . SiteView::ACTION_USER_RETURN_TO_MENU . "'> Tillbaka till huvudmenyn. Dina svar sparas.</a>";
		}


		$retStr .= "</div>";

		return $retStr;
	}
}












