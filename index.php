<?php

	require_once("HTMLView.php");
	require_once("Controller/LoginController.php");
	//require_once("LoginView.php");
	//require_once("LoginModel.php");

	session_start();


	$loginController = new LoginController();

	$htmlBody = $loginController->doControll();

	$view = new HTMLView();
	$view->echoHTML($htmlBody);