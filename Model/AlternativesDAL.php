<?php

require_once("databaseCred.php");

class AlternativesDAL {
	
    private $databaseCred;

	private $dbConnection;

	public function __construct() {

		$this->databaseCred = new DatabaseCred();

		$this->dbConnection = mysqli_connect($this->databaseCred->host, $this->databaseCred->username, $this->databaseCred->password, $this->databaseCred->databaseName);

        if(!$this->dbConnection) {

            die('Connectionfailure2: ' . mysql_error());
        }

	}

	public function addAlternatives($alternativeTexts, $correctAnswers, $questionId) {

		$counter = 0;

		for($i=0; $i<5; $i++) {

			$alternativeText = $alternativeTexts[$i];
			$correctAnswer = $correctAnswers[$i];
			$counter++;

			//Addera rad i table quizz
			$sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO `answer_alternatives`
        	                                                    (`Question_Id`, `AnswerText`, `CorrectAnswer`, `AlternativeOrderValue`)
        	                                                    VALUES ($questionId, '$alternativeText', $correctAnswer, $counter)");
		}

        $this->dbConnection->close();
	}

	public function getQuestionAlternatives($questionId) {
		$query = "SELECT Alternative_Id, AnswerText, CorrectAnswer FROM `answer_alternatives` WHERE `Question_Id` = $questionId";
		$result = mysqli_query($this->dbConnection, $query);

		$texts = array("","","","","");
		$corrects = array("","","","","");
		$ids = array("","","","","");

		$counter = 0;

		while($row = mysqli_fetch_assoc($result)) {
		  $texts[$counter] = $row['AnswerText'];
		  $corrects[$counter] = $row['CorrectAnswer'];
		  $ids[$counter] = $row['Alternative_Id'];

		  $counter++;
		}
		
		$storeArray = array($texts, $corrects, $ids);

		return $storeArray;
	}


	public function getCorrects($questionId) {
		$query = "SELECT Alternative_Id FROM `answer_alternatives` WHERE `Question_Id` = $questionId AND `CorrectAnswer` = 1";
		$result = mysqli_query($this->dbConnection, $query);

		$ids = array("","","","","");

		$counter = 0;

		while($row = mysqli_fetch_assoc($result)) {
		  $ids[$counter] = $row['Alternative_Id'];
		  $counter++;
		}
		
		return $ids;
	}

}