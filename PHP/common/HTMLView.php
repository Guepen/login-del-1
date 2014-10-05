<?php

namespace view;

class HTMLView {


		public function echoHTML($body) {
            setlocale(LC_ALL, "sv_SE.utf8");
            $date = strftime("%A, den %#d %B %Y. Klockan är [%X]");
            $date = ucfirst($date);

			if ($body === NULL) {
				throw new \Exception("HTMLView::echoHTML does not allow body to be null");
			}

			
			echo "
				<!DOCTYPE html>
				<html>
				<head>
				<link href='../../css/bootstrap.css' type='text/css' rel='stylesheet'>
                <link rel='stylesheet' type='text/css' href='../../css/bootstrap.min.css'>
                <link rel='stylesheet' type='text/css' href='../../css/bootstrap-theme.min.css'>
                <link rel='stylesheet' type='text/css' href='../../css/bootstrap-theme.css'>
                <link rel='stylesheet' type='text/css' href='../../css/bootstrap-theme.css.map'>
				<meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
				<title>Tobias Holst - Laboration 4</title>
				<meta charset=\"utf-8\">
				</head>
				<body>
				 <div class='container'>
				  <h1 class='text-center'>Laborationskod th222fa</h1>
					$body
					<p class='text-center'>$date</p>
					 </div>
                  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                  <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
                 <!-- Include all compiled plugins (below), or include individual files as needed -->
                 <script src='../../js/bootstrap.min.js'></script>
                 <script src='../../js/bootstrap.js'></script>
				</body>
				</html>";
		}
}

