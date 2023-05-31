<?php
    session_start();
    unset($_SESSION['user']);
    header('Location: /typeOfSweets-list/swt/login.php');
?>