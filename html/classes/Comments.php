<?php

namespace classes;

class Comments
{
    /**
     * @return array возвращает массив с комментариями пользователе
     */
    public static function getAllComments() {

        $comments = array(
//            array(
//                "username" => "John Doe",
//                "date" => "12/10/2025",
//                "text" => "Текст коммента",
//            ),
        );

        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        $sql = "SELECT * FROM COMMENTS WHERE id < 1000";

        $result = $mysql->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $comments[] = array('username'=>$row['name'], 'text'=>$row['message']);
            }
        }

        $mysql->close();

        return $comments;
    }

    /**
     * Записывает комментарий в базу. Если таблицы с комментариями нет то создает её.
     * @param   $username string
     * @param   $message string
     * @return  void
     */
    public static function save($username, $message)
    {
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // Если в базе данных еще нет таблицы, то создаем
        $sql = "CREATE TABLE IF NOT EXISTS COMMENTS (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) CHARACTER SET utf8,
            message TEXT CHARACTER SET utf8
        )";

        if ($mysql->query($sql) !== true) {
            die("Error creating table: " . $mysql->error);
        }

        // Подгатавливаем SQL запрос на сохранение комментария
        if ($stmt = $mysql->prepare("INSERT INTO COMMENTS (name, message) VALUES (?, ?)")) {
            $stmt->bind_param("ss", $username, $message);
            $stmt->execute();
            $stmt->close();
        } else {
            die ("database error connection");
        }
        $mysql->close();
    }
}
