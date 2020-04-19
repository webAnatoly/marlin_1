<?php

namespace Pagination;

## Постраничная навигация по папке
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
$obj = new \Pagination\DirPager(
    new \Pagination\PagesList(),
    'uploads',
    1,
    2
);
// Содержимое текущей страницы
foreach ($obj->getItems() as $img) {
    echo "<img src='$img' alt='picture' /> ";
}
// Постраничная навигация
echo "<p>$obj</p>";