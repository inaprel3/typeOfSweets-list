<?php 
    if ($_POST) { 
        require_once '../model/autorun.php'; 
        $myModel = Model\Data::makeModel(Model\Data::FILE);
        if ($user = $myModel->readUser($_POST['username'])) {
            if ($user->checkPassword($_POST['password'])) {
                session_start();
                $_SESSION['user'] = $user->getUserName();
                header('Location: ../index.php');
            }
        }
    }

    require_once '../view/autorun.php';
    $myView = \View\SweetListView::makeView(\View\SweetListView::SIMPLEVIEW);
    $myView->showLoginForm();
?>