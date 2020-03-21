<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/myAutoloader.php';
spl_autoload_register("myAutoloader");

$user = array();
$user = classes\User::getData($_COOKIE["_auth_key"]);
$isUser = (isset($user["user_id"]) && $user["user_id"] > 0);

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
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/view/nav.php"?>

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
                                    <span><small><?=date("d/m/Y", $data["ts"])?></small></span>
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
                            <?php if ($isUser): ?>
                                <div class="card-header"><h3>Оставить комментарий</h3></div>

                                <div class="card-body">
                                    <form action="" onsubmit="return false" name="addComment">
                                      <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Сообщение</label>
                                        <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="3"><?=isset($_SESSION['tmp_fields']['message']) ? $_SESSION['tmp_fields']['message'] : ''?></textarea>
                                        <?php if(isset($_SESSION['isErrorForm']['message'])): ?>
                                            <p style="color: red"><?=$_SESSION['isErrorForm']['message']?></p>
                                        <?php endif; ?>
                                      </div>
                                      <button type="button" class="btn btn-success" id="sendCommentBtn">Отправить</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="card-header"><p>Чтобы оставить комментарий
                                        <a href="login.php">войдите</a> или <a href="register.php">зарегистрируйтесь</a>.</p></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php unset($_SESSION['isErrorForm'], $_SESSION['tmp_fields']); ?>
