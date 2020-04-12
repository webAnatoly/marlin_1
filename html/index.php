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

ordering by creation time
PDOException: SQLSTATE[HY000] [2002] Connection refused in /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php:80
Stack trace:
#0 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(80): PDO->__construct('mysql:host=mysq...', 'root', 'test', Array)
#1 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/MysqlAdapter.php(130): Phinx\Db\Adapter\PdoAdapter->createPdoConnection('mysql:host=mysq...', 'root', 'test', Array)
#2 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(149): Phinx\Db\Adapter\MysqlAdapter->connect()
#3 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(213): Phinx\Db\Adapter\PdoAdapter->getConnection()
#4 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(232): Phinx\Db\Adapter\PdoAdapter->query('SELECT * FROM `...')
#5 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(377): Phinx\Db\Adapter\PdoAdapter->fetchAll('SELECT * FROM `...')
#6 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(349): Phinx\Db\Adapter\PdoAdapter->getVersionLog()
#7 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/AdapterWrapper.php(212): Phinx\Db\Adapter\PdoAdapter->getVersions()
#8 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Migration/Manager/Environment.php(259): Phinx\Db\Adapter\AdapterWrapper->getVersions()
#9 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Migration/Manager.php(306): Phinx\Migration\Manager\Environment->getVersions()
#10 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Console/Command/Migrate.php(123): Phinx\Migration\Manager->migrate('development', NULL, false)
#11 /var/www/html/vendor/symfony/console/Command/Command.php(255): Phinx\Console\Command\Migrate->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#12 /var/www/html/vendor/symfony/console/Application.php(912): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#13 /var/www/html/vendor/symfony/console/Application.php(264): Symfony\Component\Console\Application->doRunCommand(Object(Phinx\Console\Command\Migrate), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#14 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Console/PhinxApplication.php(69): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#15 /var/www/html/vendor/symfony/console/Application.php(140): Phinx\Console\PhinxApplication->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#16 /var/www/html/vendor/robmorgan/phinx/bin/phinx(28): Symfony\Component\Console\Application->run()
#17 {main}

Next InvalidArgumentException: There was a problem connecting to the database: SQLSTATE[HY000] [2002] Connection refused in /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php:83
Stack trace:
#0 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/MysqlAdapter.php(130): Phinx\Db\Adapter\PdoAdapter->createPdoConnection('mysql:host=mysq...', 'root', 'test', Array)
#1 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(149): Phinx\Db\Adapter\MysqlAdapter->connect()
#2 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(213): Phinx\Db\Adapter\PdoAdapter->getConnection()
#3 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(232): Phinx\Db\Adapter\PdoAdapter->query('SELECT * FROM `...')
#4 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(377): Phinx\Db\Adapter\PdoAdapter->fetchAll('SELECT * FROM `...')
#5 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/PdoAdapter.php(349): Phinx\Db\Adapter\PdoAdapter->getVersionLog()
#6 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Db/Adapter/AdapterWrapper.php(212): Phinx\Db\Adapter\PdoAdapter->getVersions()
#7 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Migration/Manager/Environment.php(259): Phinx\Db\Adapter\AdapterWrapper->getVersions()
#8 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Migration/Manager.php(306): Phinx\Migration\Manager\Environment->getVersions()
#9 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Console/Command/Migrate.php(123): Phinx\Migration\Manager->migrate('development', NULL, false)
#10 /var/www/html/vendor/symfony/console/Command/Command.php(255): Phinx\Console\Command\Migrate->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#11 /var/www/html/vendor/symfony/console/Application.php(912): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#12 /var/www/html/vendor/symfony/console/Application.php(264): Symfony\Component\Console\Application->doRunCommand(Object(Phinx\Console\Command\Migrate), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#13 /var/www/html/vendor/robmorgan/phinx/src/Phinx/Console/PhinxApplication.php(69): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#14 /var/www/html/vendor/symfony/console/Application.php(140): Phinx\Console\PhinxApplication->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#15 /var/www/html/vendor/robmorgan/phinx/bin/phinx(28): Symfony\Component\Console\Application->run()
#16 {main}
root@efcc8eb6beb8:/var/www/db#


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

