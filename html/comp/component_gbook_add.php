<?php ## Компонентный подход. Компонент добавления записи.
if(!defined("GBook")) {
    define("GBook", "{$_SERVER['DOCUMENT_ROOT']}/uploads/guest_book/gbook.txt"); // имя файла с данными гостевой книги
}
require_once $_SERVER["DOCUMENT_ROOT"] . "/Model/model_guest_book.php"; // подключаем Модель (ядро)
// Обработка формы, если Шаблон запущен при отправке формы.
// Если нажата кнопка Добавить...
if (!empty($_REQUEST['doAdd'])) {
    // Сначала - загрузка гостевой книги.
    $tmpBook = loadBook(GBook);
    // Добавить в книгу запись пользователя - она у нас хранится в массиве $New, см. форму в шаблоне.
    // Запись добавляется, как водится, в начало книги.
    $tmpBook = [time() => $_REQUEST['new']] + $tmpBook;
    // Записать книгу на диск.
    saveBook(GBook, $tmpBook);
    header("Location: http://{$_SERVER['HTTP_HOST']}/comp/view.php");
}
// Данный компонент не генерирует никаких данных.
$data = null;