<?php


     require_once("./src/ImportFiles.php");

    session_start();

 	$view = new \view\HTMLView();

 	$loginControl = new \controller\LoginControl();
 	$htmlBody = $loginControl->render();
	$view->echoHTML($htmlBody);
