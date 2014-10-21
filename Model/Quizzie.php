<?php

require_once("QuizzDAL.php");
require_once("QuestionDAL.php");

class Quizzie {

	public $quizzList;
	public $alternatives;
	public $quizzDAL;

	public function __construct() {


		//Starta skapa nytt quizz

		$this->alternatives = array();
		$this->quizzList = array();

		$quizzDAL = new QuizzDAL();

	}

	public function addAlternativeToArray($text, $correctAnswer) {
		$this->alternatives[$text] = $correctAnswer;


	}


	public function getSpecQuestion($questionId) {
		//Visa speciell fråga att redigera


	}


	//Ska anropas när användaren postar fromulär för en fråga
	public function saveQuestion($questionText, $alternatives) {

		//Spara persistent via lämpliga DAL-lager

	}
}