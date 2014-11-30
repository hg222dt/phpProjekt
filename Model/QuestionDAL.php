<?php

require_once("databaseCred.php");

class QuestionDAL {

    private $databaseCred;	

	private $dbConnection;
	public $questionId;

	public function __construct() {

		$this->databaseCred = new DatabaseCred();

		$this->dbConnection = mysqli_connect($this->databaseCred->host, $this->databaseCred->username, $this->databaseCred->password, $this->databaseCred->databaseName);

        if(!$this->dbConnection) {
            die('Connectionfailure1: ' . mysql_error());
        }

	}

	public function addQuestion($quizzId, $questionText, $quizzOrderValue) {
		//Addera rad i table quizz
		$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO `questions`
                                                            (`Quizz_Id`, `QuestionText`, `QuizzOrderValue`)
                                                            VALUES ($quizzId, '$questionText', $quizzOrderValue)");

		$this->questionId = $this->dbConnection->insert_id;
        
        $this->dbConnection->close();
	}

	public function getLatestQuestionId() {
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
//            return false;
        	throw new Exception("Detta quizz har inga frågor.");
        	
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



	public function getQuestionObject($questionId) {
		$query = "SELECT * FROM `questions` WHERE Question_Id = $questionId";
		$result = mysqli_query($this->dbConnection, $query);

		$row = mysqli_fetch_assoc($result);

		$tempQuestionObject = new QuestionObject($row['Quesion_Id'], $row['Quizz_Id'], $row['QuesionName'], $row['QuesionText'], $row['QuizzOrderValue']);

        return $tempQuestionObject;
	}
}