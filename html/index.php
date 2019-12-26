<?php
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

    <!-- Scripts -->
    <script src="js/main.js"></script>
    <style>
        /*    Стили для flash сообщения    */
        @keyframes show-and-hide {
          0%
          {
            opacity: 0;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: inherit;
            color: inherit;
            height: inherit;
            font-size: inherit;
            visibility: inherit;
          }
          50% {
            opacity: 1;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: inherit;
            color: inherit;
            height: inherit;
            font-size: inherit;
            visibility: inherit;
          }
          100% {
            opacity: 0.2;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: inherit;
            color: inherit;
            height: inherit;
            font-size: inherit;
            visibility: inherit;
          }
        }
        .animate-alert {
          animation: show-and-hide 3s;
          opacity: 1;
          padding: 0 0;
          margin-bottom: 0;
          border: none;
          color: transparent;
          height: 1px;
          font-size: 1px;
          visibility: hidden;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.html">
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
                                <a class="nav-link" href="register.html">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Комментарии</h3></div>

                            <div class="card-body" id="allCommentsCard">
                                <template id="alert-template">
                                  <div class="alert alert-success animate-alert" role="alert" id="alert-block">
                                    Комментарий успешно добавлен
                                  </div>
                                </template>

                                <?php $comments = classes\Comments::getAllComments(["sort"=>"reverse"]); if (isset($comments)): ?>
                                <?php foreach ($comments as $data): ?>

                                <div class="media">
                                  <img src="img/no-user-small.jpg" class="mr-3" alt="..." width="64" height="64">
                                  <div class="media-body">
                                    <h5 class="mt-0"><?=$data["username"]?></h5>
                                    <span><small><?=$data["date"]?></small></span>
                                    <p>
                                        <?=$data["text"]?>
                                    </p>
                                  </div>
                                </div>

                                <?php endforeach ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3></div>

                            <div class="card-body">
                                <form action="" onsubmit="return false" name="addComment">
                                    <div class="form-group">
                                    <label for="exampleFormControlInput1">Имя</label>
                                    <input name="name" class="form-control" id="exampleFormControlInput1" />
                                  </div>
                                  <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Сообщение</label>
                                    <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                  </div>
                                  <button type="button" class="btn btn-success" id="sendCommentBtn">Отправить</button>
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
