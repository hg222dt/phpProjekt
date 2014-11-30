<?php

require_once("databaseCred.php");

/*
 * Dataaccesslager för allt relaterat till quizz-tabellen i databasen
 *
 **/

class QuizzDAL {
	
    private $databaseCred;
	private $dbConnection;
	private $quizzId;

	public function __construct() {

		$this->databaseCred = new DatabaseCred();

		$this->dbConnection = mysqli_connect($this->databaseCred->host, $this->databaseCred->username, $this->databaseCred->password, $this->databaseCred->databaseName);

        if(!$this->dbConnection) {

            die('Connectionfailure3: ' . mysql_error());
        }
	}

	//Skapar nytt quizz
	public function createNewQuizz($quizzName, $username) {
		//Addera rad i table quizz
		$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO quizzes
                                                            (Name, User)
                                                            VALUES ('$quizzName', '$username')");

		$this->quizzId = $this->dbConnection->insert_id;

        $this->dbConnection->close();

	}

	public function getLatestQuizzId(){
		return $this->quizzId;
	}

	//Hämtar alla namn på quizz 
	public function getUserQuizzNames($userId) {

		$query = "SELECT Name FROM `quizzes` WHERE `User` = '$userId' ORDER BY Quizz_ID";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = Array();
		while ($row = mysqli_fetch_assoc($result)) {
		    $storeArray[] =  $row['Name'];  
		}

        return $storeArray;
	}

	public function getUserQuizzIds($userId) {

		$query = "SELECT Quizz_Id FROM `quizzes` WHERE `User` = '$userId' ORDER BY Quizz_ID";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = Array();
		while ($row = mysqli_fetch_assoc($result)) {
		    $storeArray[] =  $row['Quizz_Id'];  
		}

        return $storeArray;
	}

	public function getAllQuizzNames() {

		$query = "SELECT Name FROM `quizzes` ORDER BY Quizz_ID";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = Array();
		while ($row = mysqli_fetch_assoc($result)) {
		    $storeArray[] =  $row['Name'];  
		}

        return $storeArray;
	}

	public function getAllQuizzIds() {

		$query = "SELECT Quizz_Id FROM `quizzes` ORDER BY Quizz_ID";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = Array();
		while ($row = mysqli_fetch_assoc($result)) {
		    $storeArray[] =  $row['Quizz_Id'];  
		}

        return $storeArray;
	}

	public function deleteQuizz($quizzId) {
		$query = "DELETE FROM `quizzes` WHERE `Quizz_Id` = $quizzId";
		$result = mysqli_query($this->dbConnection, $query);
	}


	public function getWholeQuizzAsArray($quizzId) {

		$query = "SELECT * FROM `questions` WHERE Quizz_Id = $quizzId";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = array();
		while ($row = mysqli_fetch_assoc($result)) {
		    //$storeArray[] =  $row['Name'];  

			$tempQuestionObject = new QuestionObject($row['Quesion_Id'], $row['Quizz_Id'], $row['QuesionName'], $row['QuesionText'], $row['QuizzOrderValue']);

			$storeArray[$row['Quesion_Id']] = $tempQuestionObject;

		}

        return $storeArray;

	}


}