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
}