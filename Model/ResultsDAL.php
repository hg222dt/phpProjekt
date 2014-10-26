<?php

class ResultsDAL {
	
		private $dbConnection;

		public function __construct() {

			$this->dbConnection = mysqli_connect("localhost", "root", "", "quizzgamez");

	        if(!$this->dbConnection) {

	            die('Connectionfailure: ' . mysql_error());
	        }

		}


		public function addResult($isUserCorrect, $questionId, $quizzId, $userId) {
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO `quizz_results`
        	                                                    (`Question_Id`, `User_Id`, `Quizz_Id`, `CorrectAnswer`)
        	                                                    VALUES ($questionId, $userId, $quizzId, $isUserCorrect)");


		}


}