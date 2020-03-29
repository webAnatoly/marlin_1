<?php

use classes\User, classes\Comments;

$config = require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

// Определяем залогинен ли пользователь, если да, то сохраняем его данные в массив
$user = array();
$user = User::getData($_COOKIE["_auth_key"]);
$isUser = (isset($user["user_id"]) && $user["user_id"] > 0);

/**
 * Обработчик POST запросов
 */

/* === Регистрация. Обработка POST запроса регистрации нового пользователя === */
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
        $_SESSION["successReg"] = "Пользователь успешно зарегистрирован";
        header("Location: register.php");
        exit;
    }

    header("Location: register.php");
    exit;

/* === Авторизация. Обработка POST запроса авторизации пользователя === */
} elseif (isset($_POST['authorisation']) && $_POST['authorisation'] === "") {

    session_start();
    unset($_SESSION['isErrorAuth']);

    $auth_data = array(
        'email' => isset($_POST['email']) ? htmlentities(trim($_POST['email'])) : "",
        'password' => isset($_POST['password']) ? htmlentities(trim($_POST['password'])) : "",
    );

    // валидация емейла
    if (filter_var($auth_data['email'], FILTER_VALIDATE_EMAIL) === false) {
        $_SESSION['isErrorAuth']['email'] = "Ошибка валидации: невалидный емейл";
    }

    // валидация пароля
    if ($auth_data['password'] === '' || mb_strlen($auth_data['password']) < 4) {
        $_SESSION['isErrorAuth']['password'] = "Ошибка валидации: слишком короткий пароль";
    } elseif (mb_strlen($auth_data['password']) > 255) {
        $_SESSION['isErrorAuth']['password'] = "Ошибка валидации: превышена допустимая длина пароля";
    }

    $_SESSION["tmp_auth_fields"] = $auth_data;

    // Если есть ошибки валидации, то перенаправляем и выходим
    if (isset($_SESSION['isErrorAuth'])) {
        header("Location: login.php");
        exit;
    }

    // Проверка существования пользователя
    if (User::isExists($auth_data["email"]) !== true) {
        $_SESSION['isErrorAuth']['email'] = "Пользователя с таким емейлом не существует";
        header("Location: login.php");
        exit;
    }

    // Проверка пароля
    if (User::isCorrectPassword($auth_data["password"], $auth_data["email"]) !== true) {
        $_SESSION['isErrorAuth']['password'] = "неверный пароль";
        header("Location: login.php");
        exit;
    }

    // Если всё ОК, то вызываем метод authorisation(), который генерит токен и ставит его в куку браузеру.
    if (User::authorisation($auth_data["email"]) === true) {
        header("Location: index.php");
        exit;
    } else {
        die("Unexpected error while authorisation <a href='index.php'>На главную</a>");
    }

/* === Обработка POST запроса редактирования профила === */
} elseif(isset($_POST["edit_profile"])) {
    session_start();

    // Обработка загрузки картинки профиля
    if ($_FILES["image"]["error"] === 2) { // быстрая проверка на превышение размера файла для удобства пользователей на основе скрытого инпута <input type="hidden" name="MAX_FILE_SIZE" value="30000" />

        $_SESSION["isErrorFileUpload"]["message"] = "Превышен допустимый размер файла";
        header("Location: profile.php");
        exit;

    } elseif ($_FILES["image"]["error"] === 0) {

        // основная проверка на превышение размера файла
        $max_file_size = 30000;
        if ($_FILES["image"]["size"] > $max_file_size) {
            $_SESSION["isErrorFileUpload"]["message"] = "Exceeded max file size";
            header("Location: profile.php");
            exit;
        }

        // определение mime типа файла
        $mime_type = mime_content_type($_FILES["image"]["tmp_name"]);
        $mime_type = array_pop(explode("/", $mime_type));

        $allowed_types = array("png", "jpg", "jpeg", "bmp");
        if(!in_array($mime_type, $allowed_types)) {
            $_SESSION["isErrorFileUpload"]["message"] = "Недопустипый формат картинки";
            header("Location: profile.php");
            exit;
        }

        // генерация имени и сохранение файла
        $tmp_name = $_FILES["image"]["tmp_name"];
        $name = "avatar_" . $user["user_id"] . "." . $mime_type;

        if (move_uploaded_file($tmp_name, "uploads/$name")) {

            // запись пути к файлу в базу
            $url_img = "http://" . $_SERVER["HTTP_HOST"] . "/uploads/" . $name;
            User::insertFile($_COOKIE["_auth_key"], $url_img);
            $_SESSION["isProfileUpdated"] = true;
            header("Location: profile.php");
            exit;

        }

    }

    $new_data = array(
        "name" => isset($_POST["name"]) ? htmlentities(trim($_POST["name"])) : "",
        "email" => isset($_POST["email"]) ? htmlentities(trim($_POST["email"])) : "",
    );

    // Проверка обязательных полей на пустоту
    if (mb_strlen($new_data["name"]) < 1) { $_SESSION['isErrorProfile']['name'] = "Поле не должно быть пустым"; }
    if (mb_strlen($new_data["name"]) > 255) { $_SESSION['isErrorProfile']['name'] = "Превышена допустимая длина"; }

    // Валидация емейла
    if (    filter_var($new_data['email'], FILTER_VALIDATE_EMAIL) === false
            || mb_strlen($new_data['email']) > 255  ) {

        $_SESSION['isErrorProfile']['email'] = "Пустой или некорректный емейл";

    }

    // Проверка емейла на дублирование.
    if (User::isExists($new_data["email"]) === true &&
        User::getData($_COOKIE["_auth_key"])["email"] !== $new_data["email"] // Если старый емейл совпадает с новым, то всё ОК.
    ) {
        $_SESSION['isErrorProfile']['email'] = "Емейл уже используется";
    }

    // Если были ошибки, то выходим
    if (isset($_SESSION['isErrorProfile'])) {
        header("Location: profile.php");
        exit;
    } else { // Если ошибок не было, то обновляем данные
        User::updateData($_COOKIE["_auth_key"], $new_data["name"], $new_data["email"]);
        $_SESSION["isProfileUpdated"] = true;
        header("Location: profile.php");
        exit;
    }

/* === Обработка POST запроса добавления нового комментария === */
} elseif (isset($_POST['add_new_comment'])) {
    session_start();
    // Обработка POST запроса добавления нового комментария
    $name = isset($user["name"]) ? htmlentities(trim($user["name"])) : "---";
    $message = isset($_POST['message']) ? htmlentities(trim($_POST["message"])) : "";

    // Временно сохраняем в сессию
    $_SESSION['tmp_fields']['message'] = $message;

    // Если имя и сообщение не пусты, то записываем комментарий в базу
    if ($message !== "" && isset($user["user_id"])) {
        if(Comments::save($user["user_id"], $name, $message) === true) {
            unset($_SESSION['isErrorForm'], $_SESSION['tmp_fields']);
            echo ("success");
            exit;
        } else {
            echo ("error");
            exit;
        }
    }

    if ($message === "") { $_SESSION['isErrorForm']['message'] = "Пустое сообщение"; }

    if ($name === "" ||  $message === "") {

        die("errorInput");

    }

    exit;


}

exit;
