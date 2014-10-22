<?php

class QuizzDAL {
	
	private $dbConnection;

	private $quizzId;

	public function __construct() {

		$this->dbConnection = mysqli_connect("localhost", "root", "", "quizzgamez");

        if(!$this->dbConnection) {

            die('Connectionfailure: ' . mysql_error());
        }
	}

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

	public function getAllQuizzNames($userId) {

		var_dump($userId);
		$query = "SELECT Name FROM `quizzes` WHERE `User` = '$userId' ORDER BY Quizz_ID";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = Array();
		while ($row = mysqli_fetch_assoc($result)) {
		    $storeArray[] =  $row['Name'];  
		}

        return $storeArray;
	}

}