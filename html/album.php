<?php ## Простейший фотоальбом с возможностью закачки
$imgDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/img"; // каталог для хранения изображений
@mkdir($imgDir, 0777); // создаем, если его еще нет
// Проверяем, нажата ли кнопка добавления фотографии
if ($_REQUEST['doUpload']) {
    $data = $_FILES['file'];
    $tmp = $data['tmp_name'];
    // Проверяем, принят ли файл
    if (is_uploaded_file($tmp)) {
        $info = getimagesize($tmp);
        // Проверяем, является ли файл изображением
        if (preg_match('{image/(.*)}is', $info['mime'], $p)) {
            // Имя берем равным текущему времени в секундах, а расширение - как часть MIME-типа после "image/"
            $name = "$imgDir/".time().".".$p[1];
            // Добавляем файл в каталог с фотографиями
            move_uploaded_file($tmp, $name);
        } else {
            echo "<h2>Попытка добавить файл недопустимого формата!</h2>";
        }
    } else {
        echo "<h2>Ошибка закачки #{$data['error']}!</h2>";
    }
}
// Теперь считываем в массив наш фотоальбом
$photos = array();
foreach (glob("$imgDir/*") as $path) {
    $sz = getimagesize($path); // размер
    $tm = filemtime($path); // время добавления
    $basename = basename($path);
    // Вставляем изображение в массив $photos
    $photos[$tm] = [
        'time' => $tm, // время добавления
        'name' => $basename, // имя файла
        'url'  => 'uploads/img/' . $basename, // его URI
        'w'    => $sz[0], // ширина картинки
        'h'    => $sz[1], // ее высота
        'wh'   => $sz[3] // "width=xxx height=yyy"
    ];
}
// Ключи массива $photos - время в секундах, когда была добавлена
// та или иная фотография. Сортируем массив: наиболее "свежие"
// фотографии располагаем ближе к его началу.
krsort($photos);
// Данные для вывода готовы. Дело за малым - оформить страницу.
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Простейший фотоальбом с возможностью закачки</title>
</head>
<body>
    Выберите какой-нибудь файл в форме ниже:
    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" name="doUpload" value="Закачать новую фотографию">
    </form>
    <?php if (isset($photos) && count($photos) !== 0) {
        foreach($photos as $n=>$img) { ?>
            <p><img
                    src="<?=$img['url']?>"
                    <?=$img['wh']?>
                    alt="Добавлена <?=date("d.m.Y H:i:s", $img['time'])?>"
                ></p>
        <?php }
    } else { ?>
        <p>Еще не добавлено ни одной фотографии</p>
    <?php
    } ?>
</body>
</html>
