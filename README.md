Учебный проект больше связан с настройкой окружения для разработки на php.
Пробую в докере настаивать php, mysql конфигурировать apache и т.д.

Но так же тут планирую делать учебные проекты на php, ну или по крайней мере испытывать всякие штуки.

Долго пыталя запустить docker сборку php с использованием Composer. Наконец-то получилось.  

Composer тут запускается из докер образа с указанием папки куда мапятся зависимости из контейнера на хост машину.  

Пример команд для запуска композера из образа:  
docker run --rm -v $PWD:/app composer require --dev phpunit/phpunit ^8  
docker run --rm -v $PWD:/app composer require tightenco/collect  

Пример установки микрофреймворка Slim
docker run --rm -v $PWD:/app composer require slim/slim:^4.0

$PWD - это переменная окружения содержащая путь откуда запускается команда.
/app - это имя папки проекта внутри докер образа куда мы мапим наш проект.

docker run --rm composer dump-autoload

Интересный момент:  

Когда докер из контейнера вольюмит папку на хост, то получается, что папка эта создается из под root пользователя и принадлежит группе root (потому что внутри контейнер запускается как root). Что-бы спокойно редактировать эти папки/файлы на хосте нужно изменить права доступа и добавить в свою группу. 
Команда для этого  

sudo chown -R username:groupname .  

Точка в конце команды означает путь от текущей папки.

Как уже стало понятно docker run --rm -v $PWD:/app это запуск образа докера, а composer <параметры и т.д> это запуск композера.

Еще команды:
Узнать место, где composer хранит ссылки на исполняемые файлы локально подключаемых пакетов (бинарники)
docker run --rm -v $PWD:/app composer config bin-dir

По умолчанию PhpStorm переменную $_SERVER['DOCUMENT_ROOT'] вычисляет как корень проекта.
Изменить это можно в настройках. 
File | Settings | Languages & Frameworks | PHP | Analysis | Include Analysis | $_SERVER['DOCUMENT_ROOT'] <указать путь к папке, которую нужно считать корнем сайта>
https://www.jetbrains.com/help/phpstorm/php.html#include-analysis

Как настроить подключение PhpStorm к базе данных запущенной в докер контейнере.
[Подробнее](https://www.jetbrains.com/help/idea/running-a-dbms-image.html)
