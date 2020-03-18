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

        return $fetched_pass_hash === $pass;

    }
}