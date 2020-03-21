<?php session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");
?>
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
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/view/nav.php"?>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Login</div>

                            <div class="card-body">
                                <form method="POST" action="post.php">

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <?php if (isset($_SESSION['isErrorAuth']['email'])): ?>
                                                <input id="email" type="email" class="form-control is-invalid " name="email"  autocomplete="email" autofocus
                                                       value="<?php echo isset($_SESSION["tmp_auth_fields"]["email"]) ? $_SESSION["tmp_auth_fields"]["email"] : ''; ?>">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo $_SESSION['isErrorAuth']['email'] ?></strong>
                                                </span>
                                            <?php else: ?>
                                                <input id="email" type="email" class="form-control" name="email"  autocomplete="email" autofocus
                                                       value="<?php echo isset($_SESSION["tmp_auth_fields"]["email"]) ? $_SESSION["tmp_auth_fields"]["email"] : ''; ?>">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <?php if (isset($_SESSION['isErrorAuth']['password'])): ?>
                                                <input id="password" type="password" class="form-control is-invalid " name="password"  autocomplete="current-password">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo $_SESSION['isErrorAuth']['password'] ?></strong>
                                                </span>
                                            <?php else: ?>
                                                <input id="password" type="password" class="form-control " name="password"  autocomplete="current-password">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                                <label class="form-check-label" for="remember">
                                                    Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="authorisation">

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                               Login
                                            </button>
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
</body>
</html>
<?php unset($_SESSION['isErrorAuth'], $_SESSION["tmp_auth_fields"]); ?>
