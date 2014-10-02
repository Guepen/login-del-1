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
				<title>Laboration 4</title>
				<meta charset=\"utf-8\">
				</head>
				<body>
					$body
					$date
				</body>
				</html>";
		}
}

