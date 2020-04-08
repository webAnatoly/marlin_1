<?php

function get_all_headers($hostname)
{
    $curl = curl_init($hostname);  // Задаем адрес удаленного сервера

    if ($curl) {
        // Устанавливаем параметры соединения

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   // Вернуть результат в виде строки
        curl_setopt($curl, CURLOPT_HEADER, true);           // Включить в результат HTTP-заголовки
        curl_setopt($curl, CURLOPT_NOBODY, true);           // Исключить тело HTTP-документа
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 2);

        $content = curl_exec($curl);    // Получаем HTTP-заголовки

        curl_close($curl);     // Закрываем CURL-соединение

        $result = explode("\r\n", $content);     // Преобразуем строку $content в массив

    } else {
        $result = "не удалось установить соединение";
    }

    return $result;
}

echo "<pre>";
print_r(get_all_headers("https://php.net"));
echo "</pre>";

echo "<pre>";
print_r(get_all_headers("https://example.com"));
echo "</pre>";

/*HTTP-заголовки, начинающиеся с префикса X-, не являются стандартными, в них, как правило, серверы и клиенты передают
дополнительную информацию от модулей сервера, антивируса, систем антиспама и т. п.*/

## Обращение к серверу точного времени
// Задаем адрес удаленного сервера
$curl = curl_init("http://wwv.nist.gov:13");
// Получаем содержимое страницы
echo curl_exec($curl);
// Закрываем CURL-соединение
curl_close($curl);
