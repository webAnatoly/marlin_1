<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/view/nav.php";

        // Для гостей показываем сообщение о неоходимости авторизироваться и выходим
        if ($isUser !== true) {
                die ("<div style='width:90%; margin: 20px auto; text-align: center;'><div class=\"alert alert-success\" role=\"alert\">
                Для продолжения необходимо <a href='login.php'>авторизироваться</a>
                    </div></div></div></body></html>");
        }
    ?>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
                            <?php if (isset($_SESSION["isProfileUpdated"])): ?>
                                <div class="alert alert-success" role="alert" id="alertUpdatedSuccess">
                                    Профиль успешно обновлен
                                </div>
                            <?php elseif(isset($_SESSION["isErrorFileUpload"])): ?>
                                <div class="alert alert-danger" role="alert" id="alertUpdatedError">
                                    <?php echo $_SESSION["isErrorFileUpload"]["message"]; ?>
                                </div>
                            <?php endif; ?>

                            <form action="post.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="formControlInputProfileName1">Name</label>
                                            <?php if ( isset($_SESSION['isErrorProfile']['name']) ): ?>
                                                <input type="text" class="form-control is-invalid" name="name" id="formControlInputProfileName1" value="">
                                                <span class="text text-danger">
                                                    <? echo $_SESSION['isErrorProfile']['name'] ?>
                                                </span>
                                            <? else: ?>
                                                <input type="text" class="form-control" name="name" id="formControlInputProfileName1" value="<?php echo htmlspecialchars($user['name'])?>">
                                            <? endif; ?>

                                        </div>

                                        <div class="form-group">
                                            <label for="formControlInputProfileEmail1">Email</label>
                                            <?php if ( isset($_SESSION['isErrorProfile']['email']) ): ?>
                                                <input type="email" class="form-control is-invalid" name="email" id="formControlInputProfileEmail1" value="">
                                                <span class="text text-danger">
                                                    <? echo $_SESSION['isErrorProfile']['email'] ?>
                                                </span>
                                            <? else: ?>
                                                <input type="email" class="form-control" name="email" id="formControlInputProfileEmail1" value="<?php echo htmlspecialchars($user['email'])?>">
                                            <? endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                                            <label for="formControlInputProfileImage1">Аватар</label>
                                            <input type="file" class="form-control" name="image" id="formControlInputProfileImage1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if (isset($_SESSION["isProfileUpdated"])): ?>
                                            <img src="<?php echo $user["avatar"] . "?" . rand(); ?>" alt="" class="img-fluid">
                                        <?php else: ?>
                                            <img src="<?php echo $user["avatar"]; ?>" alt="" class="img-fluid">
                                        <?php endif; ?>
                                    </div>

                                    <input type="hidden" name="edit_profile">

                                    <div class="col-md-12">
                                        <button class="btn btn-warning">Edit profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
                            <?php if ( isset($_SESSION["isSuccessChangePassword"]) ): ?>
                                <div class="alert alert-success" role="alert">
                                    Пароль успешно обновлен
                                </div>
                            <?php elseif(isset($_SESSION["isErrorChangePassword"]["message"])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION["isErrorChangePassword"]["message"]; ?>
                                </div>
                            <?php endif; ?>

                            <form action="post.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="formControlInputProfilePasswordCurrent">Current password</label>
                                            <?php if ( isset($_SESSION["isErrorChangePassword"]["current"]) ): ?>
                                                <input type="password" class="form-control is-invalid" name="current" id="formControlInputProfilePasswordCurrent" value="">
                                                <span class="text text-danger">
                                                    <? echo $_SESSION["isErrorChangePassword"]["current"] ?>
                                                </span>
                                            <? else: ?>
                                                <input type="password" name="current" class="form-control" id="formControlInputProfilePasswordCurrent">
                                            <? endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="formControlInputProfilePasswordNew">New password</label>
                                            <?php if ( isset($_SESSION["isErrorChangePassword"]["password"])): ?>
                                                <input type="password" name="password" class="form-control is-invalid" id="formControlInputProfilePasswordNew">
                                                <span class="text text-danger">
                                                    <? echo $_SESSION["isErrorChangePassword"]["password"] ?>
                                                </span>
                                            <?php else: ?>
                                                <input type="password" name="password" class="form-control" id="formControlInputProfilePasswordNew">
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="formControlInputProfilePasswordConfirm">Password confirmation</label>
                                            <?php if ($_SESSION["isErrorChangePassword"]["password_confirmation"]): ?>
                                                <input type="password" name="password_confirmation" class="form-control is-invalid" id="formControlInputProfilePasswordConfirm">
                                                <span class="text text-danger">
                                                    <? echo $_SESSION["isErrorChangePassword"]["password_confirmation"] ?>
                                                </span>
                                            <?php else: ?>
                                                <input type="password" name="password_confirmation" class="form-control" id="formControlInputProfilePasswordConfirm">
                                            <?php endif; ?>
                                        </div>

                                        <input type="hidden" name="change_password">

                                        <button class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    const alertUpdatedSuccess = document.getElementById("alertUpdatedSuccess");
    const alertUpdatedError = document.getElementById("alertUpdatedError");
    if (alertUpdatedSuccess !== null) {
        setTimeout(() => alertUpdatedSuccess.style.display = "none", 2000);
    }
    if (alertUpdatedError !== null) {
        setTimeout(() => alertUpdatedError.style.display = "none", 2000);
    }
</script>
</body>
</html>
<?php unset($_SESSION['isErrorProfile'], $_SESSION["isProfileUpdated"], $_SESSION["isErrorFileUpload"],
    $_SESSION["isErrorChangePassword"], $_SESSION["isSuccessChangePassword"], $_SESSION["isErrorChangePassword"]); ?>
