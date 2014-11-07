<?php

class FinishedDAL {
	
		private $dbConnection;

		public function __construct() {

			$this->dbConnection = mysqli_connect("localhost", "root", "root", "quizzgamez");

	        if(!$this->dbConnection) {

	            die('Connectionfailure4: ' . mysql_error());
	        }

		}


		public function addFinishedQuizz($resultInPersentage, $quizzId, $userId) {
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO `finished_quizzes`
        	                                                    (`User_Id`, `Quizz_Id`, `ResultValue`)
        	                                                    VALUES ($userId, $quizzId, $resultInPersentage)");


		}

		public function getAllFinishedResultsUser($userId) {
			//returnerar array med resultat på alla avklarade quizz, med tillhörande quizzId (som key?)

			$result = array();

			$query = "SELECT ResultValue, Quizz_Id FROM `finished_quizzes` WHERE `User_Id` = $userId";
			$result = mysqli_query($this->dbConnection, $query);

	        $quizzResults = array();

			while($row = mysqli_fetch_assoc($result)) {
			  $quizzResults[$row['Quizz_Id']] = $row['ResultValue'];
			}

			return $quizzResults;
		}

		/*
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
		*/

}