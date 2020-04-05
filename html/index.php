<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$db = $config->db;
$dsn = "mysql:host={$db['host']};dbname={$db['database']}";

try {
    $pdo = new PDO($dsn, $db["user"], $db["password"]);
    $pdo = new PDO($dsn, $db["user"], $db["password"]);
} catch (PDOException $e) {
    var_dump($e);
    echo "Cannot connect to database";
}

