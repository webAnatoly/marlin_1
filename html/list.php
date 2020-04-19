<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Список пользователей</title>
    <link rel="stylesheet" href="css/list.css">
    <script type="text/javascript" src="/js/libs/jquery.min.js" ></script>
    <script>
        // Назначить обработчики события click после загрузки документа
        $(function(){
            $(".jumbotron > div > a").on("click", function(){
                // Формируем ссылку для AJAX-обращения
                let url = "user.php?id=" + $(this).data('id');
                // Отправляем AJAX-запрос и выводим результат
                $.ajax({
                    url: encodeURI(url),
                    error: function(e) { console.log(e); $('#error').html('Ошибка').removeClass("hidden") },
                }).done(function(data){
                    $('#info').html(data).removeClass("hidden");
                });
            })
        });
    </script>
</head>
<body>
<div id="list">
<?php
// Устанавливаем соединение с базой данных
require_once($_SERVER["DOCUMENT_ROOT"] . "/db.php");
$query = "SELECT * FROM users ORDER BY name";
$usr = $pdo->query($query);
try {
    echo "<div class='jumbotron'>";
    while($user = $usr->fetch()) {
        echo "<div><a href='#' ".
             "data-id='".$user['id']."'>".
             htmlspecialchars($user['name'])."</a></div>";
    }
    echo "</div>";
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса: " . $e->getMessage();
}
?>
</div>
<div id='info' class='hidden'></div>
<div id='error' class='hidden' style="color:red"></div>
</body>
</html>
