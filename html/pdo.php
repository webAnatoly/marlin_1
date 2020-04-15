<?php ## Постраничная навигация таблицы languages
/* Подключение автозагрузчика Composer
 * Его включение при помощи директив require или require_once предоставляет доступ ко всем компонентам,
 * загруженным посредством Composer*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
try {
    $pdo = new PDO(
        'mysql:host=mysql_my_marlin_project_1;dbname=forum',
        'root',
        'test',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $obj = new Pagination\PdoPager(
        new Pagination\PagesList(),
        $pdo,
        'languages');
// Содержимое текущей страницы
    foreach($obj->getItems() as $language) {
        echo htmlspecialchars($language['name'])."<br /> ";
    }
// Постраничная навигация
    echo "<p>$obj</p>";
}
catch (PDOException $e) {
    var_dump($e->getMessage());
    echo "Невозможно установить соединение с базой данных";
}