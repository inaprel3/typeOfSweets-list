<?php 
	include(__DIR__ . "/../swt/check-swt.php");

	require_once '../model/autorun.php';
	$myModel = Model\Data::makeModel(Model\Data::FILE);
	$myModel->setCurrentUser($_SESSION['user']);

	$typeOfSweets = (new \Model\TypeOfSweets())->setId($_GET['file'])->setSweetId($_GET['sweet']);
	if (!$myModel->removeTypeOfSweets($typeOfSweets)) {
		die($myModel->getError());
	} else {
		header('Location: ../index.php?sweet=' . $_GET['sweet']);
	}
?>