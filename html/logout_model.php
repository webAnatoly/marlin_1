<?php
/* Логаут */
if (isset($_GET['logout'])) {
    setcookie("_auth_key", "", 1);
    header( "Location: login.php" );
    exit;
}