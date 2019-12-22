<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");
/**
 * Обработчик пост запросов
 */

// Обработка POST запроса
$name = isset($_POST['name']) ? htmlentities(trim($_POST['name'])) : "";
$message = isset($_POST['message']) ? htmlentities(trim($_POST['message'])) : "";

// Если имя и сообщение не пусты, то записываем комментарий в базу
if ($name !== "" && $message !== "") {
    if(classes\Comments::save($name, $message) === true) {
        header("Location: index.php?isNewCommentAdded");
        exit;
    }
}

header("Location: index.php");
exit;
