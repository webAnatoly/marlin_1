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
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                Project
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>

                        <div class="card-body">
                            <form method="POST" action="post.php">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                    <div class="col-md-6">
                                        <?php if(isset($_SESSION['isErrorReg']['username'])): ?>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="username">

                                        <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo $_SESSION['isErrorReg']['username'] ?></strong>
                                                </span>
                                        <?php else: ?>
                                            <input id="name" type="text" class="form-control" name="username"
                                                   value="<?php echo isset($_SESSION['tmp_reg_fields']['username']) ? $_SESSION['tmp_reg_fields']['username'] : ''; ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <?php if(isset($_SESSION['isErrorReg']['email'])): ?>
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control is-invalid" name="email"
                                                   style="color: #e3342f;"
                                                   value="<?php echo isset($_SESSION['tmp_reg_fields']['email']) ? $_SESSION['tmp_reg_fields']['email'] : ''; ?>">
                                            <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo $_SESSION['isErrorReg']['email'] ?></strong>
                                                    </span>
                                        </div>
                                    <?php else: ?>
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email"
                                                   value="<?php echo isset($_SESSION['tmp_reg_fields']['email']) ? $_SESSION['tmp_reg_fields']['email'] : ''; ?>">
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <div class="form-group row">
                                    <?php if (isset($_SESSION['isErrorReg']['password'])): ?>
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control is-invalid" name="password"  autocomplete="new-password">
                                            <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo $_SESSION['isErrorReg']['password'] ?></strong>
                                                    </span>
                                        </div>
                                    <?php else: ?>
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control " name="password"  autocomplete="new-password">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                    </div>
                                </div>

                                <input type="hidden" name="registration">

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
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
<?php unset($_SESSION['isErrorReg'], $_SESSION['tmp_reg_fields']); ?>
