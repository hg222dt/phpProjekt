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

			$query = "SELECT `CorrectAnswer` FROM `quizz_results` WHERE `User_Id` = $userId AND `Quizz_Id` = $quizzId";
			$result = mysqli_query($this->dbConnection, $query);

			$storeArray = Array();
			while ($row = mysqli_fetch_assoc($result)) {
			    $storeArray[] =  $row['CorrectAnswer'];  
			}

	        return $storeArray;
		}

}