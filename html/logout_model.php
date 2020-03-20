<?php
/* Логаут */
if (isset($_GET['logout'])) {
    session_start();
    unset($_SESSION['success_authorisation']);
    header( "Location: login.php" );
    exit;
}