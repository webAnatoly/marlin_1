<?php

/**
 * Автозагрузка классов из корневой папки проекта. И из любых вложенных папок.
 * В корневой папке классы можно писать и с namespace и без него. Главное, чтобы имя файла совпадало с именем класса.
 * Во вложенных папках в namespace нужно указывать имя папки, т.е. namespace должны совпадать с названиями папок
 * например для папки classes с файлом Foo.php namespace должен быть таким namespace classes;
 * а для папки classes/subclass c файлом Foo.php namespace должен быть таким namespace classes\subclass;
 * @return void
 *
 * Пример использования spl_autoload_register("myAutoloader");
 * spl_autoload_register - это стандартная функция для регистрации загрузчиков.
 * @param $name string имя класса
 */
function myAutoloader($name){

    $parts = explode ("\\", $name);

    // проверка на массив
    if (!(isset($parts) && is_array($parts))) { return; }

    if (count($parts) === 1) { // Если пришло имя вида Foo

        include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $parts[0] . '.php';

    } elseif (count($parts) === 2 && $parts[0] === $parts[1]) { // Если пришло имя вида Foo\Foo

        include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $parts[0] . '.php';

    } elseif (count($parts) === 2 && $parts[0] !== $parts[1]) { // Если пришло имя вида dirname\Foo

        include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $parts[0] . DIRECTORY_SEPARATOR . $parts[1] . '.php';

    } elseif (count($parts) > 2) { // Если пришло имя вида dirname\[любая вложенность папок]\Foo

        $path = "";

        // Если последние две части пути совпадают
        if ($parts[count($parts) - 1] === $parts[count($parts) - 2]) {

            for ($i = 0; $i < count($parts) - 1; $i+=1) {
                $path .= DIRECTORY_SEPARATOR . "$parts[$i]";
            }

            require_once $_SERVER['DOCUMENT_ROOT'] . $path . '.php';

        } else { // Если последние две части пути не совпадают

            for ($i = 0; $i < count($parts); $i+=1) {
                $path .= DIRECTORY_SEPARATOR . "$parts[$i]";
            }

            require_once $_SERVER['DOCUMENT_ROOT'] . $path . '.php';

        }

    }

}

/*
[TO DO] Добавить в функцию myAutoloader проверку на существование файла file_exists().
Идея такая: получаем имя класса в параметре $className на его основе формируем путь к файлу. И если файл существует, то загружаем его.
*/
