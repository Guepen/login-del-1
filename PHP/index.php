<?php

	require_once("common/HTMLView.php");
	require_once("src/controller/loginController.php");

    session_start();

 	$view = new HTMLView();
 	$loginControl = new \controller\loginControll();

 	$htmlBody = $loginControl->render();
	$view->echoHTML($htmlBody);

    setlocale(LC_ALL, "sv_SE.utf8");
    $date = strftime("%A, den %#d %B %Y. Klockan är [%X]");
    $date = ucfirst($date);
 	echo $date;