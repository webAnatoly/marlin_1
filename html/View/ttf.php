<?php ## Пример работы с TTF-шрифтом

/*Cценарий генерирует изображение оттененной строки "Привет, мир!"
на фоне JPEG-изображения, загруженного с диска. При этом используется TrueTypeшрифт.
Угол поворота строки зависит от текущего системного времени — попробуйте
нажать несколько раз кнопку Обновить в браузере, и вы увидите, что строка будет все
время поворачиваться против часовой стрелки. Кроме того, размер текста подбирается
так, чтобы он занимал максимально возможную площадь, не выходя при этом за края
картинки (см. определение функции imageTtfGetMaxSize() )*/

require_once $_SERVER['DOCUMENT_ROOT']."/lib/imagettf.php";
// Выводимая строка
$string = "Привет, мир!";
// Шрифт
$font = $_SERVER['DOCUMENT_ROOT']."/fonts/CyrilicOld.TTF";
// Загружаем фоновой рисунок
$im = imageCreateFromPng($_SERVER['DOCUMENT_ROOT']."/uploads/avatar_8.png");
// Угол поворота зависит от текущего времени
$angle = (microtime(true) * 10) % 360;
// Если хотите, чтобы текст шел из угла в угол,
// раскомментируйте строчку:
# $angle = rad2deg(atan2(imageSY($im), imageSX($im)));
// Подгоняем размер текста под размер изображения
$size = imageTtfGetMaxSize(
    $angle, $font, $string,
    imageSX($im), imageSY($im)
);
// Создаем в палитре новые цвета
$shadow = imageColorAllocate($im, 0, 0, 0);
$color = imageColorAllocate($im, 128, 255, 0);
// Вычисляем координаты вывода, чтобы текст оказался в центре
$sz = imageTtfSize($size, $angle, $font, $string);
$x = (imageSX($im) - $sz[0]) / 2 + $sz[2];
$y = (imageSY($im) - $sz[1]) / 2 + $sz[3];
// Рисуем строку текста вначале черным со сдвигом, а затем
// основным цветом поверх (чтобы создать эффект тени)
imageTtfText($im, $size, $angle, $x + 3, $y + 2, $shadow, $font, $string);
imageTtfText($im, $size, $angle, $x, $y, $color, $font, $string);
// Сообщаем о том, что далее следует рисунок PNG
Header("Content-type: image/png");
// Выводим рисунок
imagePng($im);