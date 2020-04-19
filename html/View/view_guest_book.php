<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Гостевая книга</title>
</head>
<body>
<h1>Добавьте свое сообщение:</h1>
<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/Controller/controller_guest_book.php'?>" method="post">
    Ваше имя: <input type="text" name="new[name]"><br />
    Комментарий:<br />
    <textarea name="new[text]" cols="60" rows="5"></textarea><br />
    <input type="submit" name="doAdd" value="Добавить!">
</form>
<h2>Гостевая книга:</h2>
<?php foreach ($book as $id => $e) { ?>
    Имя человека: <?=$e['name']?><br />
    Его комментарий:<br /> <?=nl2br($e['text'])?><hr />
<?php } ?>
</body>
</html>