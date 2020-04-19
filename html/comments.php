<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AJAX-запрос методом POST</title>
    <script type="text/javascript" src="/js/libs/jquery.min.js" ></script>
    <script>
        // Назначить обработчики события click после загрузки документа
        $(document).ready(function(){
            $("#submit-id").on("click", function(){
                // Проверяем корректность заполнения полей
                if($.trim($("#nickname").val()) === "") {
                    alert('Пожалуйста, заполните поле "Автор"');
                    return false;
                }
                if($.trim($("#content").val()) === "") {
                    alert('Пожалуйста, заполните поле "Сообщение"');
                    return false;
                }
                // Блокируем кнопку отправки
                $("#submit-id").prop("disabled", true);
                // AJAX-запрос
                $.ajax({
                    url: "addcom.php",
                    method: 'post',
                    data: {nickname: $("#nickname").val(),
                        content: $("#content").val()}
                }).done(function(data){
                    // Успешное получение ответа
                    $("#info").html(data);
                    $("#submit-id").prop("disabled", false);
                });
            })
        });
    </script>
</head>
<body>
    <div id='info'>
        <?php
            require_once("addcom.php");
        ?>
    </div>
    <form id='form' onsubmit="return false">
        <p>
            <span class='ttl'>Автор:</span>
            <span class='fld'><input id='nickname' type='text' /></span>
        </p>
        <p>
            <span class='ttl'>Сообщение:</span>
            <span class='fld'><textarea rows='5' id='content' type='text'></textarea></span>
        </p>
        <p>
            <span class='ttl'>&nbsp;</span>
            <span class='fld'><input id='submit-id' type='submit' value='Отправить' /></span>
        </p>
    </form>
</body>
</html>
