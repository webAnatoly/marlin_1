<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Двойной выпадающий список</title>
    <script type="text/javascript" src="/js/libs/jquery.min.js" ></script>
    <script>
        $(function(){
            $("#fst").on("change", function(){
                // AJAX-запрос
                $.ajax({
                    url: "select.php?id=" + $('#fst').val(),
                    error: function(e){console.log(e.statusText);}
                }).done(function(data){
                    $('#snd').html(data);
                    $("#snd").prop("disabled", false);
                });
            });
        });
    </script>
</head>
<body>
<?php
// Устанавливаем соединение с базой данных
require_once $_SERVER["DOCUMENT_ROOT"] . "/db.php";
// Формируем выпадающий список корневых разделов
$query = "SELECT * FROM catalogs WHERE parent_id = 0 AND is_active = 1 ORDER BY pos";
echo "<select id='fst'>";
echo "<option value='0'>Выберите раздел</option>";
$com = $pdo->query($query);
while($catalog = $com->fetch()) {
    echo "<option value='{$catalog['id']}'>{$catalog['name']}</option>";
}
echo "</select>";
?>
<select id='snd' disabled='disabled'>
    <option value='0'>Выберите подраздел</option>
</select>
</body>
</html>
