<?php 
    session_start();
    if(!$_SESSION['user']) {
        header('Location: /typeOfSweets-list/swt/login.php');
    }
?>