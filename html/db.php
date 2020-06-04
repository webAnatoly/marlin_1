<?php

$host       = $_SERVER['SERVER_NAME'] == "localhost" ? "mysql_my_marlin_project_1" : "localhost";
$username   = $_SERVER['SERVER_NAME'] == "localhost" ? "root" : "remote_user";
$pass       = $_SERVER['SERVER_NAME'] == "localhost" ? "test" : "remote_password";
$dbname     = $_SERVER['SERVER_NAME'] == "localhost" ? "forum" : "remote_database_name";

$pdo = new PDO(
    "mysql:host={$host};dbname={$dbname}",
    "{$username}",
    "{$pass}");
