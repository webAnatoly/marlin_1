<?php


namespace classes;


class User
{
    private static $data = array("test"=>1234);
    public static function get()
    {
        return self::$data;
    }

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
        if ($stmt = $mysql->prepare("SELECT user_id, name, email, reg_date, avatar FROM Users WHERE token=?")) {
            $stmt->bind_param("s", $token );
            $stmt->execute();
            $stmt->bind_result($user_id,$name, $email, $reg_date, $avatar);
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
            "avatar" => isset($avatar) ? $avatar : "img/no-user.jpg",
        );
    }

    /**
     * Обновляет данные пользователя (имя, емейл и т.д.) в соответствии с переданными параметрами.
     * Например, если передано только имя, то обновит только имя и т.д.
     * @param $token
     * @param string $name
     * @param string $email
     * @param array $files
     * @return bool
     */
    public static function updateData($token, $name="", $email="", $files=array())
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        if ($name !== "" && $email === "") { // обновить только имя

            if ($stmt = $mysql->prepare("UPDATE Users SET name=? WHERE token=?")) {
                $stmt->bind_param("ss", $name, $token);
                $stmt->execute();
                $stmt->close();
            } else {
                die ("database error connection");
            }
            return true;

        } elseif ($name === "" && $email !== "") { // обновить только емейл

            if ($stmt = $mysql->prepare("UPDATE Users SET email=? WHERE token=?")) {
                $stmt->bind_param("ss", $email, $token);
                $stmt->execute();
                $stmt->close();
            } else {
                die ("database error connection");
            }
            return true;

        } elseif ($name !== "" && $email !== "") { // обновить имя и емейл

            if ($stmt = $mysql->prepare("UPDATE Users SET name=?, email=? WHERE token=?")) {
                $stmt->bind_param("sss", $name, $email, $token);
                $stmt->execute();
                $stmt->close();
            } else {
                die ("database error connection");
            }
            return true;

        }
        return false;

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
     * Меняет старый пароль на новый
     * @param string $token токен пользователя приходящий в куках браузера
     * @param string $current текущий пароль
     * @param string $password новый пароль
     * @param string $password_confirmation подтверждение нового пароля
     * @return bool
     */
    public static function changePassword($token, $current, string $password, string $password_confirmation) {

        if ($password !== $password_confirmation) { return false; }

        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // По полученному токену достаем из базы хеш текущего пароля и сравниванем его с паролем в параметре $current,
        // т.е. с паролем пришедшим из формы
        if ($stmt = $mysql->prepare("SELECT pass_hash, email FROM Users WHERE token=?")) {
            $stmt->bind_param("s", $token );
            $stmt->execute();
            $stmt->bind_result($fetched_current_hash, $user_email);
            $stmt->fetch();
            $stmt->close();
        } else {
            return false;
        }

        // Если присланный текущий пароль не совпадает с текущим паролем из базы, то выходим
        if( !password_verify($current, $fetched_current_hash) ) { return false; };

        // Сохраняем новый пароль
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt = $mysql->prepare("UPDATE Users SET pass_hash=? WHERE token=?")) {
            $stmt->bind_param("ss", $hash, $token);
            $stmt->execute();
            $stmt->close();
        } else {
            die ("database error connection");
        }


        // Проверка обновился ли пароль
        if ($stmt = $mysql->prepare("SELECT pass_hash FROM Users WHERE token=?")) {
            $stmt->bind_param("s", $token );
            $stmt->execute();
            $stmt->bind_result($fetched_new_password_hash);
            $stmt->fetch();
            $stmt->close();
        } else {
            return false;
        }
        $mysql->close();

        if( password_verify($password, $fetched_new_password_hash) ) { return true; }

        return false;
    }

    /**
     * Сохраняет путь к картинке пользователя в базу
     * @param string $token токен пользователя, который приходит в куках браузера
     * @param string $path путь к файлу
     */
    public static function insertFile($token, $path)
    {
        // Подключение к базе
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        $user_id = self::getData($token)["user_id"];

        // Запись пути к файлу в базу
        if ($stmt = $mysql->prepare("UPDATE Users SET avatar=? WHERE user_id=?")) {
            $stmt->bind_param("sd", $path, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            die ("database error connection");
        }

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