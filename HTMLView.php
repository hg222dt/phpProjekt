<?php

class HTMLView {

	public function echoHTML($body) {
		if($body === null) {
			//kasta exception här
		}

		echo "
		<!DOCTYPE html>
		<html>
			<head>
				<title>Quizz-siten!</title>
				<meta http-equiv='content-type' content='text/html; charset=utf-8'>
			</head>
			<body>
				$body
			</body>
			</html>
		";
	}	

}