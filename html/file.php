<?php ## Постраничная навигация по файлу
/* Подключение автозагрузчика Composer
 * Его включение при помощи директив require или require_once предоставляет доступ ко всем компонентам,
 * загруженным посредством Composer*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
$obj = new Pagination\FilePager(
    new Pagination\PagesList(),
    'uploads/largetextfile.txt');
// Содержимое текущей страницы
foreach($obj->getItems() as $line) {
    echo htmlspecialchars($line)."<br /> ";
}
// Постраничная навигация
echo "<p>$obj</p>";
