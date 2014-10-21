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

	public function createNewQuizz($quizzName) {
		//Addera rad i table quizz
		$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO quizzes
                                                            (Name)
                                                            VALUES ('$quizzName')");

		$this->quizzId = $this->dbConnection->insert_id;

        $this->dbConnection->close();

	}

	public function getLatestQuizzId(){
		return $this->quizzId;
	}
}