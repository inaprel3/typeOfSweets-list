<?php 
	include(__DIR__ . "/../swt/check-swt.php");

	require_once '../model/autorun.php';
	$myModel = Model\Data::makeModel(Model\Data::FILE);
	$myModel->setCurrentUser($_SESSION['user']);

	if (!$myModel->addSweet()) {
		die($myModel->getError());
	} else {
		header('Location: ../index.php?sweet=' . $newSweetsName);
	}
?>