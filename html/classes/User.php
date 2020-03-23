<?php


namespace classes;


class User
{
    /**
     * Проверяет существует ли в базе пользователь с указанным емейлом
     * @param string $email
     * @return bool
     */
    public static function isExists($email = "")
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // Пытаемся найти в базе указанный емейл
        if ($stmt = $mysql->prepare("SELECT email FROM Users WHERE email=?")) {
            $stmt->bind_param("s", $email );
            $stmt->execute();
            $stmt->bind_result($fetched_email);
            $stmt->fetch();
            $stmt->close();
        } else {
            die("Database access error");
        }
        $mysql->close();

        return $fetched_email === $email;
    }

    /**
     * Проверяем соответствие полученного пароля, хешу паролю хранимому в базе.
     * @param string $pass
     * @param string $email
     * @return bool
     */
    public static function isCorrectPassword($pass = "", $email ="")
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // Достаем из базы хеш пароля по указанному емейлу
        if ($stmt = $mysql->prepare("SELECT pass_hash FROM Users WHERE email=?")) {
            $stmt->bind_param("s", $email );
            $stmt->execute();
            $stmt->bind_result($fetched_pass_hash);
            $stmt->fetch();
            $stmt->close();
        } else {
            die("Database access error");
        }
        $mysql->close();

        return password_verify($pass, $fetched_pass_hash);

    }

    /**
     * Получает данные о пользователе по токену и возвращает их в массиве.
     * @param string $token
     * @return array
     */
    public static function getData($token)
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // Достаем из базы данные пользователя по указанному паролю
        if ($stmt = $mysql->prepare("SELECT user_id, name, email, reg_date FROM Users WHERE token=?")) {
            $stmt->bind_param("s", $token );
            $stmt->execute();
            $stmt->bind_result($user_id,$name, $email, $reg_date);
            $stmt->fetch();
            $stmt->close();
        } else {
            die("Database access error");
        }
        $mysql->close();
        return array(
            "user_id" => isset($user_id) ? $user_id : 0,
            "name" => isset($name) ? $name : "",
            "email" => isset($email) ? $email : "",
            "reg_date" => isset($reg_date) ? $reg_date : "",
        );
    }

    /**
     * Устанавливает токен доступа в куку и случае успеха возвращает true иначе false.
     * @param $email
     * @return bool
     */
    public static function authorisation($email)
    {
        $token = self::createToken($email); // генерация токена доступа
        if ( self::insertToken($token, $email)  === true ) { // запись токена в базу
            // ставим куку
            return setcookie("_auth_key", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
        }
        return false;
    }

    /**
     * Генерация токена на основе емейла
     * @param $email
     * @return string
     */
    private static function createToken($email)
    {

        // генерация токена
        $random_int = rand(1, 9);
        $string = $random_int . $email;
        $token = base64_encode($string);
        $token = md5($token);

        return $token;

    }

    /**
     * Запись токена в базу
     * @param $token
     * @param $email
     * @return bool
     */
    private static function insertToken($token, $email)
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // Запись токена в базу
        if ($stmt = $mysql->prepare("UPDATE Users SET token=? WHERE email=?")) {
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();
            $stmt->close();
        } else {
            die ("database error connection");
        }

        // Проверка записался ли токен
        if ($stmt = $mysql->prepare("SELECT email FROM Users WHERE token=?")) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($fetched_email);
            $stmt->fetch();
            $stmt->close();
        } else {
            die ("database error connection");
        }

        $mysql->close();

        return $fetched_email === $email;
    }
}