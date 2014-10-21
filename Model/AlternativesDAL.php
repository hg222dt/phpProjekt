<?php

class AlternativesDAL {
	
	private $dbConnection;

	public function __construct() {

		$this->dbConnection = mysqli_connect("localhost", "root", "", "quizzgamez");

        if(!$this->dbConnection) {

            die('Connectionfailure: ' . mysql_error());
        }

	}

	public function addAlternatives($alternatives, $questionId) {

		foreach ($alternatives as $answerText => $correctAnswer) {
			
			//Addera rad i table quizz
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO Answer_alternatives
        	                                                    (Question_Id, AnswerText, CorrectAnswer)
        	                                                    VALUES ('$questionId', '$answerText', '$correctAnswer')");
		}


        $this->dbConnection->close();
	}

}