<?php 
	include(__DIR__ . "/../swt/check-swt.php");

	require_once '../model/autorun.php';
	$myModel = Model\Data::makeModel(Model\Data::FILE);
	$myModel->setCurrentUser($_SESSION['user']);

	if ($_POST) {
		if (!$myModel->writeSweet((new \Model\Sweet())
			->setId(trim($_GET['sweet']))
			->setSweetName(trim($_GET['sweetName']))
			->setSweetQuantity(trim($_GET['sweetQuantity']))
			->setSweetShop(trim($_GET['sweetShop']))
		)) {
			die ($myModel->getError());
		} else {
			header('Location: ../index.php?sweet=' . trim($_GET['sweet']));
		}
	}
	if (!$sweet = $myModel->readSweet(trim($_GET['sweet']))) { 
		die ($myModel->getError());
	}

	require_once '../view/autorun.php';
	$myView = \View\SweetListView::makeView(\View\SweetListView::SIMPLEVIEW);
	$myView->setCurrentUser($myModel->getCurrentUser());
	$myView->showSweetEditForm($sweet);
?>