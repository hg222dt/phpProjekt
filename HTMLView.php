<?php

/*
 * Klass för att visa den slutliga html-sidan för användaren
 *
 **/
class HTMLView {

	public function echoHTML($body) {
		if($body === null) {
			throw new Exception();
		}

		echo "
		<!DOCTYPE html>
		<html>
			<head>
				<title>Quizz-siten!</title>
				<meta http-equiv='content-type' content='text/html; charset=utf-8'>
				<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'>
				<link rel='stylesheet' type='text/css' href='css/styles.css'>
			</head>
			<body>
				<div class='container'>
					$body
				</div>
			</body>
			</html>
		";
	}
}