<?php

var_dump(getimagesize($_SERVER["DOCUMENT_ROOT"] . "/uploads/avatar_8.png"));

?>
<img src="View/button.php?Hello+world!">

<h1>Работа с полупрозрачными цветами</h1>
<img src="View/semitransp.php" alt="полупрозрачный рисунок">

<h1>Изменение пера</h1>
<img src="View/pen.php?<?php echo time(); ?>" alt="picture">

<h1>Пример работы с TTF шрифтом</h1>
<img src="View/ttf.php?<?php echo time(); ?>" alt="picture">


<footer style="height: 100px"></footer>