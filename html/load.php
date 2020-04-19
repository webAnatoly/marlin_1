<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Использование метода load()</title>
    <script type="text/javascript" src="/js/libs/jquery.min.js" ></script>
    <script>
        // Назначить обработчики события click после загрузки документа
        $(function(){
            $("#btn1").on("click", function(){
                $('#info').load("time.php");
            })
        });
    </script>
</head>
<body>
<div><a href='#' id='btn1'>Получить время</a></div>
<div id='info'></div>
</body>
</html>
