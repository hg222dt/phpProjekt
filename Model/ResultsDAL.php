<?php

class ResultsDAL {
	
		private $dbConnection;

		public function __construct() {

			$this->dbConnection = mysqli_connect("localhost", "root", "root", "quizzgamez");

	        if(!$this->dbConnection) {

	            die('Connectionfailure4: ' . mysql_error());
	        }

		}


		public function addResult($isUserCorrect, $questionId, $quizzId, $userId) {
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO `quizz_results`
        	                                                    (`Question_Id`, `User_Id`, `Quizz_Id`, `CorrectAnswer`)
        	                                                    VALUES ($questionId, $userId, $quizzId, $isUserCorrect)");


		}


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

		public function getLastFinishedQuestionId($userId, $quizzId) {
			$results = array();

			$query = "SELECT `Question_Id` FROM `quizz_results` WHERE `User_Id` = $userId AND `Quizz_Id` = $quizzId";
			$result = mysqli_query($this->dbConnection, $query);

			$storeArray = Array();
			while ($row = mysqli_fetch_assoc($result)) {
			    $storeArray[] =  $row['Question_Id'];  
			}

	        return $storeArray[sizeof($storeArray)-1];
		}

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

}