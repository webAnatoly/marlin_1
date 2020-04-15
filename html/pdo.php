<?php ## Постраничная навигация таблицы languages
// Временная автозагрузка классов
// Временная автозагрузка классов
spl_autoload_register(
    function ($class) {
        // project-specific namespace prefix
        $prefix = 'WebPager\\';

        // base directory for the namespace prefix
        $base_dir = $_SERVER['DOCUMENT_ROOT'].'/pager/src/';

        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    }
);
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