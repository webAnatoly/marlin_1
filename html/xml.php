<?php

## Чтение XML-файла
$content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/example.xml');
$rss     = new SimpleXMLElement($content);
echo $rss->channel->title . "<br />";
echo $rss->channel->description . "<br />";
var_dump($rss);

/* XPath */
// XPath выполняет для XML ту же роль, что регулярные выражения (см. главу 20) для строк

// Извлечение всех тегов <enclosure> при помощи XPath-выражения
foreach($rss->xpath('//enclosure') as $enclosure) {
    echo $enclosure['url'].'<br />';
}

echo "<br />";

/* Использование XPath позволяет значительно сократить цепочки вызовов и устранить
вложенные циклы. Например вызов: $rss->channel->item[0]->enclosure->attributes()
можно переписать с использованием XPath-выражения */
foreach($rss->xpath('//item[1]/enclosure/@*') as $attr) {
    echo "{$attr}<br />";
}

// Формирование XML-файла
$content = '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"></rss>';
$xml = new SimpleXMLElement($content);
$rss = $xml->addChild('channel');
$rss->addChild('title', 'PHP');
$rss->addChild('link', 'http://exmaple.com/');
$rss->addChild('description', 'Портал, посвященный PHP');
$rss->addChild('language', 'ru');
$rss->addChild('pubDate', date('r'));

// Установка соединения с базой данных
$pdo = new PDO(
    'mysql:host=mysql_my_marlin_project_1;dbname=forum',
    'root',
    'test',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

try {
    $query = "SELECT *
        FROM news
        ORDER BY putdate DESC
        LIMIT 20";
    $itm = $pdo->query($query);
    while($news = $itm->fetch()) {
        $item = $rss->addChild('item');
        $item->addChild('title', $news['name']);
        $item->addChild('description', $news['content']);
        $item->addChild('link', "http://example.com/news/{$news['id']}");
        $item->addChild('guid', "news/{$news['id']}");
        $item->addChild('pubDate', date('r', strtotime($news['putdate'])));
        if(!empty($news['media'])) {
            $enclosure = $item->addChild('enclosure');
            $url = "http://example.com/images/{$news['id']}/{$news['media']}";
            $enclosure->addAttribute('url', $url);
            $enclosure->addAttribute('type', 'image/jpeg');
        }
    }
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}
$xml->asXML($_SERVER['DOCUMENT_ROOT'] . '/uploads/build.xml');