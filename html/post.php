<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");
/**
 * Обработчик POST запросов
 */

// Обработка POST запроса
$name = isset($_POST['name']) ? htmlentities(trim($_POST['name'])) : "";
$message = isset($_POST['message']) ? htmlentities(trim($_POST['message'])) : "";

// Временно сохраняем в сессию
$_SESSION['tmp_fields']['name'] = $name;
$_SESSION['tmp_fields']['message'] = $message;

// Если имя и сообщение не пусты, то записываем комментарий в базу
if ($name !== "" && $message !== "") {
    if(classes\Comments::save($name, $message) === true) {
        unset($_SESSION['isErrorForm'], $_SESSION['tmp_fields']);
        echo ("success");
        exit;
    }
}

if ($name === "") { $_SESSION['isErrorForm']['name'] = "Пустое имя"; }
if ($message === "") { $_SESSION['isErrorForm']['message'] = "Пустое сообщение"; }

if ($name === "" ||  $message === "") {

    die("errorInput");

}

exit;
