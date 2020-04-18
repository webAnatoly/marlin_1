<?php ## MVC. Контроллер (генератор данных) гостевой книги
define("GBook", "{$_SERVER['DOCUMENT_ROOT']}/uploads/guest_book/gbook.txt"); // имя файла с данными гостевой книги
require_once $_SERVER["DOCUMENT_ROOT"] . "/Model/model_guest_book.php"; // подключаем Модель (ядро)

// Исполняемая часть сценария.
// Сначала загрузка гостевой книги.
$book = loadBook(GBook);
// Обработка формы, если сценарий вызван через нее.
// Если сценарий запущен после нажатия кнопки Добавить...
if (!empty($_REQUEST['doAdd'])) {
    // Добавить в книгу запись пользователя - она у нас хранится
    // в массиве $_REQUEST['new'], см. форму в Шаблоне.
    // Запись добавляется, как водится, в начало книги.
    $book = [time() => $_REQUEST['new']] + $book;
    // Записать книгу на диск.
    saveBook(GBook, $book);
}
/* Все. Теперь у нас в $book хранится содержимое книги в формате:
array (
    время_добавления => array(
    name => имя_пользователя,
    text => текст_пользователя
   ),
   . . .
);
*/
// Загружаем Шаблон страницы.
require_once $_SERVER["DOCUMENT_ROOT"] . "/View/view_guest_book.php";