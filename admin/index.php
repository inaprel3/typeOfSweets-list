<?php 
    require '../swt/check-swt.php';

    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel->setCurrentUser($_SESSION['user']);
    if (!$data['users'] = $myModel->readUsers()) {
        die($myModel->getError());  
    }

    require_once '../view/autorun.php';
    $myView = \View\SweetListView::makeView(\View\SweetListView::SIMPLEVIEW);
    $myView->showAdminForm($data['users']);
?>