<?php ## Компонентный подход. Компонент показа гостевой книги.
if(!defined("GBook")) {
    define("GBook", "{$_SERVER['DOCUMENT_ROOT']}/uploads/guest_book/gbook.txt"); // имя файла с данными гостевой книги
}
require_once $_SERVER["DOCUMENT_ROOT"] . "/Model/model_guest_book.php"; // подключаем Модель (ядро)
// Загрузка гостевой книги
$data = loadBook(GBook);
// Переменная $data теперь доступна вызывающему Шаблону (см. /comp/view.php)

/*Хотя наш Компонент и называется "показом гостевой книги", в действительности он ничего
не печатает, поручая этот процесс вызывающему Шаблону. Вообще, Компонент, как и Модель, не должен ничего выводить в браузер напрямую
(за исключением, может быть, отладочных сообщений, которые не должны присутствовать в окончательной версии сайта),
в противном случае это считается грубым нарушением компонентного подхода*/