<?php

	require_once("HTMLView.php");
	require_once("Controller/SiteController.php");
	require_once("View/SiteView.php");
	require_once("Model/SiteModel.php");

	session_start();

	$siteController = new SiteController();

	$htmlBody = $siteController->doControll();

	$view = new HTMLView();
	$view->echoHTML($htmlBody);