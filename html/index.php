<?php
session_start();
/* Подключение автозагрузчика Composer
 * Его включение при помощи директив require или require_once предоставляет доступ ко всем компонентам,
 * загруженным посредством Composer*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

/* Подключение моего автозагрузчика для моих классов */
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$db = $config->db;

$m = new Memcached();
$m->addServer('memcached_1', 11211);

try {
    $m->set("key", "asdf2222");
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

# Использование компонента Monolog
$log = new Monolog\Logger('name');
$handler = new Monolog\Handler\StreamHandler($_SERVER['DOCUMENT_ROOT'] . '/logs/app.log', Monolog\Logger::WARNING);
$log->pushHandler($handler);
$log->warning('Предупреждение');

//Задача: Есть три переменные
//- если только одна истинна, она выводится и к ней прибавляется число 1
//- если только 2 истинных, оба выводятся отдельно и к обеим приавляется число 5
//- если все три истинны, все выводятся по отдельности и ко всем прибавляется число 12

function checker ($vars = [false, false, false])
{
    $tmp = [];
    $result = [];

    foreach ($vars as $v) {
        if ($v) {
            $tmp[] = $v;
        }
    }

    $tmp_length = count($tmp);

    if ($tmp_length === 1) {
        $result = [$tmp[0] + 1];
    } elseif ($tmp_length === 2 ) {
        $result = [$tmp[0] + 5, $tmp[1] + 5];
    } elseif ($tmp_length === 3 ) {
        $result = [$tmp[0] + 12, $tmp[1] + 12, $tmp[2] + 12];
    }

    return $result;
}

echo "<p>result: </p>";
var_dump(checker([1,0,0])); // 2
var_dump(checker([1,2,0])); // 6, 7
var_dump(checker([1,2,3])); // 13, 14, 15
var_dump(checker([1,0,3])); // 6, 8



//$log = date('Y-m-d H:i:s') . " тестовая лог запись";
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/app.log', $log . PHP_EOL, FILE_APPEND);
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

