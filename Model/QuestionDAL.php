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

	public function addQuestion($quizzId, $questionText, $quizzOrderValue) {
		//Addera rad i table quizz
		$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO Questions
                                                            (Quizz_Id, QuestionText, QuizzOrderValue)
                                                            VALUES ('$quizzId', '$questionText', '$quizzOrderValue')");

		$this->questionId = $this->dbConnection->insert_id;
        
        $this->dbConnection->close();
	}

	public function getLatestQuizzId() {
		return $this->questionId;
	}

	public function getNumberOfQuestions($quizzId) {

		$query = "SELECT Question_Id FROM `questions` WHERE `Quizz_Id` = $quizzId";
		$result = mysqli_query($this->dbConnection, $query);

		$storeArray = Array();
		while ($row = mysqli_fetch_assoc($result)) {
		    $storeArray[] =  $row['Question_Id'];  
		}

        return $storeArray;
	}

	public function getQuestionText($questionId) {

		$query = "SELECT QuestionText FROM `questions` WHERE `Question_Id` = $questionId";
		$result = mysqli_query($this->dbConnection, $query);

		if(mysqli_num_rows($result) == 1) {

            $resultArray = mysqli_fetch_assoc($result);

            return $resultArray;

        } else {
            return false;
        }

		return $resultArray['QuestionText'];
	}

	public function getQuestionOrder($questionId) {
		$query = "SELECT QuizzOrderValue FROM `questions` WHERE `Question_Id` = $questionId";
		$result = mysqli_query($this->dbConnection, $query);

		if(mysqli_num_rows($result) == 1) {

            $resultArray = mysqli_fetch_assoc($result);

            return $resultArray;

        } else {
            return false;
        }

		return $resultArray['QuizzOrderValue'];			
	}

	public function getQuizzId($questionId) {
		$query = "SELECT Quizz_Id FROM `questions` WHERE `Question_Id` = $questionId";
		$result = mysqli_query($this->dbConnection, $query);

		if(mysqli_num_rows($result) == 1) {

            $resultArray = mysqli_fetch_assoc($result);

            return $resultArray;

        } else {
            return false;
        }

		return $resultArray['Quizz_Id'];
	}

	public function editQuestion($questionId, $questionText, $alternativeTexts, $correctAnswers) {

		$query = "UPDATE `questions` SET `QuestionText`='$questionText' WHERE `Question_Id`=$questionId";
		
		//$query = "UPDATE `questions` SET `QuestionText`=[value-4] WHERE 1";
		

		$result = mysqli_query($this->dbConnection, $query);
		
		for($i=0; $i<5; $i++) {

			$alternativeOrderValue = $i+1;

			$alternativeText = $alternativeTexts[$i];
			$correctAnswer = $correctAnswers[$i];

			$query2 = "UPDATE `answer_alternatives` SET `AnswerText`='$alternativeText', `CorrectAnswer`=$correctAnswer WHERE `Question_Id`= $questionId AND `AlternativeOrderValue`= $alternativeOrderValue ";
			$result2 = mysqli_query($this->dbConnection, $query2);

//			echo mysqli_errno($this->dbConnection) . ": " . mysqli_error($this->dbConnection) . "\n";
/*
			if(sizeof($alternativeTexts) == $i+1 ) {
				break;
			}*/
		}
	}

	public function getQuestionIdFromOrderAndQuizzId($orderValue, $quizzId) {
		$query = "SELECT Question_Id FROM `questions` WHERE `QuizzOrderValue` = $orderValue AND `Quizz_Id` = '$quizzId'";
		$result = mysqli_query($this->dbConnection, $query);

		if(mysqli_num_rows($result) == 1) {

            $resultArray = mysqli_fetch_assoc($result);

            return (int) $resultArray['Question_Id'];

        } else {
            return false;
        }
	}
}