<?php
if(!empty($_FILES))
{
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    exit();
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="/js/libs/jquery.min.js" ></script>
    <script type="text/javascript">
        // Назначить обработчики события click после загрузки документа
        $(function() {
            $(document).on("click", "input[type='button'][data-btn-type='addMultiFiles'][value!='+']", remove_field);
            $(document).on("click", "input[type='button'][data-btn-type='addMultiFiles'][value!='-']", add_field);
        });
        // Обработчик для кнопки +
        function add_field() {
            // Добавляем новое поле в конец
            $("form[name='addFiles'] p:last").clone().insertAfter("form[name='addFiles'] p:last");
        }
        // Обработчик для кнопки -
        function remove_field(){
            if (count_fields('p') <= 1) return; // запрет удаления последнего поля
            // Удаляем последнее поле
            $("form[name='addFiles'] p:last").remove();
        }
        // Подсчет кол-ва полей ввода внутри формы
        function count_fields(elem) {
            return $("form " + elem).length;
        }
    </script>
    <title>Форма для загрузки произвольного количества файлов</title>
</head>
<body>
<form enctype='multipart/form-data' method="post" name="addFiles">
    <p><input type="file" name="filename[]" />
        <input type="button" value="+" data-btn-type="addMultiFiles">
        <input type="button" value="-" data-btn-type="addMultiFiles"></p>
    <div><input type="submit" value="Загрузить"></div>
</form>
</body>
</html>