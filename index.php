<?php

	require_once("HTMLView.php");
	require_once("Controller/LoginController.php");
	require_once("View/SiteView.php");
	require_once("Model/SiteModel.php");

	session_start();

	$loginController = new LoginController();

	$htmlBody = $loginController->doControll();

	$view = new HTMLView();
	$view->echoHTML($htmlBody);