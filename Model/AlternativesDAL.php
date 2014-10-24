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

		$counter = 0;

		foreach ($alternatives as $answerText => $correctAnswer) {
			
			$counter++;

			//Addera rad i table quizz
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO Answer_alternatives
        	                                                    (Question_Id, AnswerText, CorrectAnswer, AlternativeOrderValue)
        	                                                    VALUES ('$questionId', '$answerText', '$correctAnswer', '$counter')");
		}

        $this->dbConnection->close();
	}

	public function getQuestionAlternatives($questionId) {
		$query = "SELECT AnswerText, CorrectAnswer FROM `answer_alternatives` WHERE `Question_Id` = $questionId";
		$result = mysqli_query($this->dbConnection, $query);

		$texts = array("","","","","");
		$corrects = array("","","","","");

		$counter = 0;

		while($row = mysqli_fetch_assoc($result)) {
		  $texts[$counter] = $row['AnswerText'];
		  $corrects[$counter] = $row['CorrectAnswer'];
		  $counter++;
		}
		
		$storeArray = array($texts, $corrects);

		return $storeArray;
	}

}