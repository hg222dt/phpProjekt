<?php

class QuestionDAL {
	

	private $dbConnection;
	public $questionId;

	public function __construct() {

		$this->dbConnection = mysqli_connect("localhost", "root", "", "quizzgamez");

        if(!$this->dbConnection) {

            die('Connectionfailure: ' . mysql_error());
        }

	}

	public function addQuestion($quizzId, $questionText) {
		//Addera rad i table quizz
		$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO Questions
                                                            (Quizz_Id, QuestionText)
                                                            VALUES ('$quizzId', '$questionText')");

		$this->questionId = $this->dbConnection->insert_id;

		var_dump($this->questionId);

        $this->dbConnection->close();
	}

	public function getLatestQuizzId() {
		return $this->questionId;
	}

}