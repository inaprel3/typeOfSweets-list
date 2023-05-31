<?php
	require_once 'data/config.php';
	require_once 'controller/autorun.php';
	$controller = new \Controller\SweetListApp(Config::$modelType, Config::$viewType);
	$controller->run();
	//Цимбал Анастасія, @inaprel3, 302, Курсова робота 6 сем, ЧНУ ім. Петра Могили, 2023.
?>