<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$db = $config->db;

try {
    $pdo = new PDO("mysql:host={$db['host']};dbname={$db['database']}", $db["user"], $db["password"]);
} catch (PDOException $e) {
    var_dump($e);
    echo "Cannot connect to database";
}

// Выполняем запрос
$query = "SELECT VERSION() AS version";
$ver = $pdo->query($query);
// Извлекаем результат
$version = $ver->fetch();
echo "версия mysql: " . $version['version']; // 5.5.46-0ubuntu0.14.04.2

try {
    // Формируем и выполняем SQL-запрос
    $query = "CREATE TABLE catalogs (
        catalog_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        name TINYTEXT NOT NULL,
        PRIMARY KEY (catalog_id))";

    $count = $pdo->exec($query);

    if ($count !== false) {
        echo "<p>Таблица успешно создана</p>";
    } else {
        echo "<p>Не удалось создать таблицу</p>";
    }

} catch (PDOException $e) {
    echo "<pre><br>Исключение!!!!!!!<br>";
    print_r($e->getMessage());
    echo "<pre>";
} catch (Throwable $e) {
    echo "непредвиденная ошибка";
    var_dump($e);
}


echo "<p></p>";

try {
    $query = "SELECT * FROM catalogs WHERE catalog_id = ?";
    $cat = $pdo->prepare($query);
    $cat->execute([2]);
    echo "<p>" . $cat->fetch()['name'] . "</p>"; // Процессоры
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}

phpinfo();

