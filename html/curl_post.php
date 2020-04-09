<?php ## Отправка данных методом POST с помощью curl

// Задаем адрес удаленного сервера
//$curl = curl_init("http://".$_SERVER['HTTP_HOST']."/handler.php");
$curl = curl_init("http://localhost:80/handler.php"); // 80 это порт внутри докер контейнера

// Устанавливаем реферер
$useragent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1";
curl_setopt($curl, CURLOPT_USERAGENT, $useragent);

// Передача данных осуществляется методом POST
curl_setopt($curl, CURLOPT_POST, true);

// Задаем POST-данные
$data = "name=".urlencode("Игорь").
    "&pass=".urlencode("пароль234aaswer")."\r\n\r\n";
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

// Выполняем запрос
if (curl_exec($curl) === false) {
    echo "Ошибка curl: <br>";
    var_dump(curl_error($curl));
};

// Закрываем CURL-соединение
curl_close($curl);

/*
Скрипт `curl_post.php` отправляет POST запрос посредством курл другому скрипту `handler.php`.
`$curl = curl_init("http://".$_SERVER['HTTP_HOST']."/handler.php");` и т.д.

Заливаю всё это на удаленный хостинг и там все работает. Т.е. скрипт `curl_post.php` благополучно отправляет,
а `hanlder.php` благополучно обрабатывает.

А вот на локалхосте выдает ошибку: `Failed to connect to localhost port 5555: Connection refused`

Но что интересно просто из linux bash консоли если выполняю такое, `curl --data "param1=asdf&param2=1234asdf" http://localhost:5555/handler.php`
то всё работает.
В чем причина?
*/