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

    public static function getData($pass = "", $email = "")
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        if ( !self::isCorrectPassword($pass, $email) ) {
            return array();
        }

        // Достаем из базы данные пользователя по указанному паролю
        if ($stmt = $mysql->prepare("SELECT user_id, name, reg_date FROM Users WHERE email=?")) {
            $stmt->bind_param("s", $email );
            $stmt->execute();
            $stmt->bind_result($user_id,$name, $reg_date);
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
}