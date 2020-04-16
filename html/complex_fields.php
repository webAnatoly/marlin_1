<?php
var_dump($_POST);
//var_dump($_FILES);
echo "<pre>";
print_r($_FILES);
echo "</pre>";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="<?php echo $_SERVER['SCRIPT_NAME']?>" enctype="multipart/form-data" method="post">
    <h3>Выберите тип файлов:</h3>
    Текстовый файл: <input type="file" name="input[a][text]"><br />
    Бинарный файл: <input type="file" name="input[a][bin]"><br />
    <input type="submit" value="Upload" name="doUpload">
</form>
</body>
</html>
