<?php

require_once("QuestionDAL.php");
require_once("AlternativesDAL.php");

class Question {
	public $questionText;
	public $alternatives = array();
	public $questionOrder;
	public $questionId;

	private $questionDAL;
	private $alternativesDAL;

	private $answers = array();

	public function __construct($questionId) {
		$this->questionDAL = new QuestionDAL();
		$this->alternativesDAL = new AlternativesDAL();

		$this->questionId = $questionId;
		$this->questionText = $this->questionDAL->getQuestionText($questionId);
		$this->questionOrder = $this->questionDAL->getQuestionORder($questionId);
		$this->alternatives = $this->alternativesDAL->getQuestionAlternatives($questionId);
	}
}