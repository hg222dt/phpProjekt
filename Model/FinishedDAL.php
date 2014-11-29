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

		$query = "SELECT `ResultValue`, `Quizz_Id` FROM `finished_quizzes` WHERE `User_Id` = $userId";
		$result = mysqli_query($this->dbConnection, $query);

        $quizzResults = array();

		while($row = mysqli_fetch_assoc($result)) {
		  $quizzResults[$row['Quizz_Id']] = $row['ResultValue'];
		}

		return $quizzResults;
	}

	public function getAllFinishedQuizzes($userId) {
		$result = array();

		$query = "SELECT Quizz_Id FROM `finished_quizzes` WHERE `User_Id` = $userId";
		$result = mysqli_query($this->dbConnection, $query);

        $quizzIds = array();
        $count = 0;

		while($row = mysqli_fetch_assoc($result)) {
		  $quizzIds[$count] = $row['Quizz_Id'];
		  $count++;
		}

		return $quizzIds;
	}

	public function getAllDoneQuizzResults() {
		//Hur många rader finns det på ett quizzId
		//Dela detta på antalet users

		$result = array();

		$query = "SELECT Quizz_Id FROM `finished_quizzes` ORDER BY `Quizz_ID`";
		$result = mysqli_query($this->dbConnection, $query);
		$storeArray = array();
		
		while($row = mysqli_fetch_assoc($result)) {

			if(array_key_exists((int) $row['Quizz_Id'], $storeArray)) {
				$storeArray[(int) $row['Quizz_Id']]++;
			} else {
				$storeArray[(int) $row['Quizz_Id']] = 1;
			}
		}

		return $storeArray;
	}

	public function getAmountDoneQuizzes() {

		$quizzDones = $this->getAllDoneQuizzResults();
		$query = "SELECT COUNT(User_Id) FROM `users` WHERE Role = 2 ";
		$result = mysqli_query($this->dbConnection, $query);
		$resultArray = mysqli_fetch_assoc($result);
		$userAmount = (int) $resultArray['COUNT(User_Id)'];

		foreach ($quizzDones as $key => $value) {
			$amountDoneUsers = round(($value / $userAmount)*100);
			$quizzDones[$key] = $amountDoneUsers;
		}

		return $quizzDones;
	}

	public function getAverageResultsQuizzes() {
		$result = array();

		$query = "SELECT Quizz_Id, ResultValue FROM `finished_quizzes` ORDER BY `Quizz_ID`";
		$result = mysqli_query($this->dbConnection, $query);
		$storeArray = array();
		$quizzArray = array();

		$count = 0;

		while($row = mysqli_fetch_assoc($result)) {

			$resultPercentage = round($row['ResultValue'] * 100);

			//var_dump($resultPercentage);

			if(array_key_exists((int) $row['Quizz_Id'], $storeArray)) {
				$tempArray = array();
				$tempArray = $storeArray[(int) $row['Quizz_Id']];
				array_push($tempArray, $resultPercentage);
				$storeArray[(int) $row['Quizz_Id']] = $tempArray;
			} else {
				$tempArray2 = array();
				array_push($tempArray2, $resultPercentage);
				$storeArray[(int) $row['Quizz_Id']] = $tempArray2;
			}
		}

		$lastKey = 0;

		ksort($storeArray);

		$newArray = array();

		foreach ($storeArray as $key => $value) {

			//Läs ut medelvärde i varje arrayfack
			if(sizeof($storeArray[$key])>1) {
				$collectedResultsInKey = 0;
				$count = 0;
				foreach ($storeArray[$key] as $key2 => $value2) {
					(int) $collectedResultsInKey += (int) $value2;
					$count++;
				}
				//var_dump($collectedResultsInKey);
				$averageResult = round($collectedResultsInKey / $count);
				$newArray[$key] = $averageResult;
			} else {
				$tempArray = $storeArray[$key];
				$newArray[$key] = (int) $tempArray[0];
			}
		}

		return $newArray;
	}
}





