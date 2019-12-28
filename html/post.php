<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");
/**
 * Обработчик POST запросов
 */

// Обработка POST запроса регистрации нового пользователя
if (isset($_POST['registration'])) {

    $reg_data = array(
        'username' => isset($_POST['username']) ? htmlentities(trim($_POST['username'])) : "",
        'email' => isset($_POST['email']) ? htmlentities(trim($_POST['email'])) : "",
        'password' => isset($_POST['password']) ? htmlentities(trim($_POST['password'])) : "",
        'password_confirmation' => isset($_POST['password_confirmation']) ? htmlentities(trim($_POST['password_confirmation'])) : "",
    );

    if ($reg_data['username'] === "" ) {
        $_SESSION['isErrorReg']['username'] = "Ошибка валидации: пустое имя пользователя";
    } elseif (mb_strlen($reg_data['username']) > 100) {
        $_SESSION['isErrorReg']['username'] = "Ошибка валидации: превышена допустимая длина имени";
    }

    if ( filter_var($reg_data['email'], FILTER_VALIDATE_EMAIL) ) {
        $_SESSION['isErrorReg']['email'] = "Ошибка валидации: невалидный емейл";
    }

    if (mb_strlen($reg_data['password']) > 100) {
        $_SESSION['isErrorReg']['password'] = "Ошибка валидации: превышена допустимая длина пароля";
    } elseif ($reg_data['password'] !== $reg_data['password_confirmation']) {
        $_SESSION['isErrorReg']['password'] = "Ошибка валидации: пароли не совпадают";
    }




    var_dump($reg_data);
//    header("Location: register.php");
    exit;

} elseif (isset($_POST['add_new_comment'])) { // Обработка POST запроса добавления нового комментария
    session_start();
    // Обработка POST запроса добавления нового комментария
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


}

exit;
