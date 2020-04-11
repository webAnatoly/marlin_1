<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$db = $config->db;

$m = new Memcached();
$m->addServer('memcached_1', 11211);

try {
    $m->set("key", "asdf2222");
    $m->increment("number", 1, 0);
    var_dump($m->get("key"));
    var_dump($m->getResultMessage());
    var_dump($m->getResultCode());
} catch (Throwable $e) {
    echo $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host={$db['host']};dbname={$db['database']}", $db["user"], $db["password"]);
} catch (PDOException $e) {
    var_dump($e);
    echo "Cannot connect to database";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Форма</title>
    <meta charset='utf-8'>
</head>
<body>
<form action='handler.php' method='post'>
    Имя : <input type='text' name='name'><br />
    Пароль : <input type='text' name='pass'><br />
    <input type='submit' value='Отправить'>
</form>
</body>
</html>

<?php

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

