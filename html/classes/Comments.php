<?php

namespace classes;

class Comments
{
    /**
     * @return array возвращает массив с комментариями пользователе
     */
    public static function getAllComments() {

        // Пока тут просто массив. В будущем этот массив будет формироваться на основе данных получаемех из базы.
        $comments = array(
            array(
                "username" => "John Doe",
                "date" => "12/10/2025",
                "text" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe aspernatur, ullam doloremque deleniti, sequi obcaecati.",
            ),
            array(
                "username" => "John Doe2",
                "date" => "13/10/2026",
                "text" => "Second Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe aspernatur, ullam doloremque deleniti, sequi obcaecati.",
            ),
            array(
                "username" => "John Doe3",
                "date" => "14/10/2027",
                "text" => "Third Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe aspernatur, ullam doloremque deleniti, sequi obcaecati.",
            ),
        );

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
        $config = require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
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
        mysqli_close($mysql);
    }
}
