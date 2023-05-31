<?php 
	include(__DIR__ . "/../swt/check-swt.php");

	if ($_POST) {
		require_once '../model/autorun.php';
		$myModel = Model\Data::makeModel(Model\Data::FILE);
		$myModel->setCurrentUser($_SESSION['user']);

		$typeOfSweets = (new \Model\TypeOfSweets())
			->setSweetId($_GET['id_swt'])
			->setTypeOfSweetsType($_POST['typeOfSweetsType'])
			->setTypeOfSweetsDateCooked(new DateTime($_POST['typeOfSweetsDateCooked']))
			->setOrderStatus($_POST['orderStatus'])
			->setIsSizeSmall();
		if ($_POST['typeOfSweetsSize'] == 'великий') { 
			$typeOfSweets->setIsSizeBig();
		}
		if (!$myModel->addTypeOfSweets($typeOfSweets)) {
			die ($myModel->getError());
		} else {
			header('Location: ../index.php?sweet=' . $_GET['sweet']);
		}
	}

	require_once '../view/autorun.php';
	$myView = \View\SweetListView::makeView(\View\SweetListView::SIMPLEVIEW);
	$myView->showTypeOfSweetsCreateForm();
?>