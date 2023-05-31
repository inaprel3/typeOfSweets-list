<?php 
  include(__DIR__ . "/../swt/check-swt.php");

  require_once '../model/autorun.php';
  $myModel = Model\Data::makeModel(Model\Data::FILE);
  $myModel->setCurrentUser($_SESSION['user']);

  if ($_POST) {
    $typeOfSweets = (new \Model\TypeOfSweets())
      ->setId($_GET['file'])
      ->setSweetId($_GET['id_swt'])
      ->setTypeOfSweetsType($_POST['typeOfSweetsType'])
      ->setTypeOfSweetsDateCooked(new DateTime($_POST['typeOfSweetsDateCooked']))
			->setOrderStatus($_POST['orderStatus'])
      ->setIsSizeSmall();
		if ($_POST['typeOfSweetsSize'] == 'великий') {
			$typeOfSweets->setIsSizeBig();
		}
		if (!$myModel->writeTypeOfSweets($typeOfSweets)) {
			die ($myModel->getError());
		} else {
			header('Location: ../index.php?sweet=' . $_GET['sweet']);
		}
  }
  $typeOfSweets = $myModel->readTypeOfSweets($_GET['sweet'], $_GET['file']);

  require_once '../view/autorun.php';
  $myView = \View\SweetListView::makeView(\View\SweetListView::SIMPLEVIEW);
  $myView->setCurrentUser($myModel->getCurrentUser());
  $myView->showTypeOfSweetsEditForm($typeOfSweets);
?>