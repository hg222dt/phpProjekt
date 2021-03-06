<?php

require_once("databaseCred.php");

/*
 * Dataaccesslager för allt relaterat till resultat på alla enskilda quizz-frågor
 *
 **/

class ResultsDAL {

		private $databaseCred;
		private $dbConnection;

		public function __construct() {

			$this->databaseCred = new DatabaseCred();

			$this->dbConnection = mysqli_connect($this->databaseCred->host, $this->databaseCred->username, $this->databaseCred->password, $this->databaseCred->databaseName);

	        if(!$this->dbConnection) {

	            die('Connectionfailure4: ' . mysql_error());
	        }

		}

		//Adderar resultat på specifik quizz-fråga till databas
		public function addResult($isUserCorrect, $questionId, $quizzId, $userId) {
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO `quizz_results`
        	                                                    (`Question_Id`, `User_Id`, `Quizz_Id`, `CorrectAnswer`)
        	                                                    VALUES ($questionId, $userId, $quizzId, $isUserCorrect)");
		}

		//Hämtar  resultat på specifikt quizz för användare
		public function getResultsForUserAndQuizz($userId, $quizzId) {
			$results = array();

			$query = "SELECT `CorrectAnswer`, 'Question_Id' FROM `quizz_results` WHERE `User_Id` = $userId AND `Quizz_Id` = $quizzId";
			$result = mysqli_query($this->dbConnection, $query);

			$storeArray = Array();
			while ($row = mysqli_fetch_assoc($result)) {
			    $storeArray[] =  $row['CorrectAnswer'];  
			}

	        return $storeArray;
		}

		//Hämtar id på senast avklarad fråga för specifik användare och quizz
		public function getLastFinishedQuestionId($userId, $quizzId) {
			$results = array();

			$query = "SELECT `Question_Id` FROM `quizz_results` WHERE `User_Id` = $userId AND `Quizz_Id` = $quizzId ORDER BY `Question_Id` ASC";
			$result = mysqli_query($this->dbConnection, $query);

			$storeArray = Array();
			while ($row = mysqli_fetch_assoc($result)) {
			    $storeArray[] =  $row['Question_Id'];  
			}

	        return $storeArray[sizeof($storeArray)-1];
		}

		//Hämtar om användare svarat korrekt på specifik fråga.
		public function didUserAnswerCorrect($questionId, $userId) {
			$results = array();

			$query = "SELECT `CorrectAnswer` FROM `quizz_results` WHERE `User_Id` = $userId AND `Question_Id` = $questionId";
			$result = mysqli_query($this->dbConnection, $query);

			$row = mysqli_fetch_assoc($result);

			if($row[`CorrectAnswer`] == 1) {
				return true;
			} else if ($row[`CorrectAnswer`] == 2) {
				return false;
			}
		}

		//KOllar om resultat på användare redan existerar i databasen
		public function checkIfResultExists($questionId, $userId) {

			$query = "SELECT `CorrectAnswer` FROM `quizz_results` WHERE `User_Id` = $userId AND `Question_Id` = $questionId";
			$result = mysqli_query($this->dbConnection, $query);

	        if (mysqli_num_rows($result) > 0) {
	        	return true;
	        }

	        return false;
		}

}