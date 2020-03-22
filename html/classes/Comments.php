<?php

namespace classes;

class Comments
{
    /**
     * @param $opt array массив опций
     * @return array возвращает массив с комментариями пользователе
     */
    public static function getAllComments($opt=[]) {

        $comments = array(
//            array(
//                "username" => "John Doe",
//                "date" => "12/10/2025",
//                "text" => "Текст коммента",
//            ),
        );

        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

//      $sql = "SELECT * FROM Comments WHERE id < 1000";
        $sql = "SELECT Comments.message, Comments.ts, Users.name
                FROM Comments INNER JOIN Users ON Comments.user_id=Users.user_id";

        // Если передан параметр $opt["sort"=>"reverse"]
        if (isset($opt["sort"]) && $opt["sort"] === "reverse") {
            $sql = "SELECT Comments.message, Comments.ts, Users.name
                    From Comments INNER JOIN Users ON Comments.user_id=Users.user_id
                    ORDER BY Comments.id DESC"; // сортировка в обратном порядке
        }

        $result = $mysql->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $comments[] = array('username'=>$row['name'], 'text'=>$row['message'], 'ts'=>strtotime($row['ts']));
            }
        }

        $mysql->close();

        // Если передан параметр $opt["sort"=>"last_to_first"], и кол-во комментов больше двух
        // то последний комментарий из таблицы ставим в начало массива
        if ( isset($opt["sort"])
            && $opt["sort"] === "last_to_first"
            && isset($comments[1]) ) {

            $lastComment = array_pop($comments);
            array_unshift($comments, $lastComment);

        }

        return $comments;
    }

    /**
     * Записывает комментарий в базу. Если таблицы с комментариями нет то создает её.
     * @param   $user_id string
     * @param   $username string
     * @param   $message string
     * @return  true в случае успеха вернет true
     */
    public static function save($user_id, $username, $message)
    {
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $mysql = mysqli_connect($config->db["host"], $config->db["user"], $config->db["password"], $config->db["database"]);

        // Если в базе данных еще нет таблицы, то создаем
        $sql = "CREATE TABLE IF NOT EXISTS Comments (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) CHARACTER SET utf8,
            message TEXT CHARACTER SET utf8
        )";

        if ($mysql->query($sql) !== true) {
            die("Error creating table: " . $mysql->error);
        }

        // Подгатавливаем SQL запрос на сохранение комментария
        if ($stmt = $mysql->prepare("INSERT INTO Comments (name, message, user_id) VALUES (?, ?, ?)")) {
            $stmt->bind_param("ssd", $username, $message, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            die ("database error connection");
        }
        $mysql->close();
        return true;
    }
}
