<?php

namespace Index_Model;

/**
 * @return array возвращает массив с комментариями пользователе
 */
function getAllComments() {

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