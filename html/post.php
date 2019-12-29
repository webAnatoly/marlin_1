<?php
$config = require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

/**
 * Обработчик POST запросов
 */

// Обработка POST запроса регистрации нового пользователя
if (isset($_POST['registration'])) {
    session_start();
    unset($_SESSION['isErrorReg']);

    $reg_data = array(
        'username' => isset($_POST['username']) ? htmlentities(trim($_POST['username'])) : "",
        'email' => isset($_POST['email']) ? htmlentities(trim($_POST['email'])) : "",
        'password' => isset($_POST['password']) ? htmlentities(trim($_POST['password'])) : "",
        'password_confirmation' => isset($_POST['password_confirmation']) ? htmlentities(trim($_POST['password_confirmation'])) : "",
    );

    $_SESSION['tmp_reg_fields'] = $reg_data;

    // валидация имени
    if ($reg_data['username'] === '' ) {
        $_SESSION['isErrorReg']['username'] = "Ошибка валидации: пустое имя пользователя";
    } elseif (mb_strlen($reg_data['username']) > 100) {
        $_SESSION['isErrorReg']['username'] = "Ошибка валидации: превышена допустимая длина имени";
    }

    // валидация емейла
    if ( filter_var($reg_data['email'], FILTER_VALIDATE_EMAIL) === false ) {
        $_SESSION['isErrorReg']['email'] = "Ошибка валидации: невалидный емейл";
    }

    // валидация пароля
    if($reg_data['password'] === '' || mb_strlen($reg_data['password']) < 4) {
        $_SESSION['isErrorReg']['password'] = "Ошибка валидации: слишком короткий пароль";
    } elseif (mb_strlen($reg_data['password']) > 255) {
        $_SESSION['isErrorReg']['password'] = "Ошибка валидации: превышена допустимая длина пароля";
    } elseif ($reg_data['password'] !== $reg_data['password_confirmation']) {
        $_SESSION['isErrorReg']['password'] = "Ошибка валидации: пароли не совпадают";
    }

    // Если есть ошибки валидации, то перенаправляем и выходим
    if (isset($_SESSION['isErrorReg'])) {
        header("Location: register.php");
        exit;
    }

    // Проверка не занят ли емейл
    $sql = "SELECT user_id FROM Users WHERE email='". $reg_data['email'] ."'";
    $result = $mysql->query($sql);

    if (isset($result->num_rows) && $result->num_rows > 0) {
        $_SESSION['isErrorReg']['email'] = "Ошибка валидации: емейл занят";
        header("Location: register.php");
        exit;
    }

    // Создаем нового пользователя
    $password_hash = password_hash($reg_data["password"], PASSWORD_DEFAULT);
    if ($stmt = $mysql->prepare("INSERT INTO Users (name, email, pass_hash) VALUES (?, ?, ?)")) {
        $stmt->bind_param("sss", $reg_data['username'], $reg_data['email'], $password_hash);
        $stmt->execute();
        $last_insert_id = $stmt->insert_id;
        $stmt->close();
    } else {
        die ("database error connection");
    }
    $mysql->close();

    // проверка успешно ли прошла запись в базу
    if (is_int($last_insert_id) && $last_insert_id > 0) {
        echo "Пользователь успешно зарегистрирован";
        exit;
    }

    header("Location: register.php");
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
