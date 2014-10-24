<?php

require_once("QuestionDAL.php");
require_once("alternativesDAL.php");

class Question {
	public $questionText;
	public $alternatives = array();
	public $questionOrder;

	private $questionDAL;
	private $alternativesDAL;

	public function __construct($questionId) {
		$this->questionDAL = new QuestionDAL();
		$this->alternativesDAL = new AlternativesDAL();

		$this->questionText = $this->questionDAL->getQuestionText($questionId);
		$this->questionOrder = $this->questionDAL->getQuestionORder($questionId);
		$this->alternatives = $this->alternativesDAL->getQuestionAlternatives($questionId);
	}
}