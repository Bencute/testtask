<?php
/**
 * @param string $content
 * @var string $content
 */
?>
<!doctype html>
<html lang="<?=Sys::getApp()->getLanguage()?>" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="application-name" content="TestTask">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="<?=Sys::getApp()->getWebUser()->getCsrfKey()?>"
          content="<?=Sys::getApp()->getWebUser()->getCsrfToken()?>" type="csrf">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">

    <title>
        <?=Sys::mId('app', 'nameSite')?>
    </title>
</head>
<body>
    <?=$this->renderView('_topMenu')?>

    <?=$this->renderView('_languageSelect')?>

    <?=$this->renderView('_alerts')?>

    <main role="main" class="container">
        <?=$content?>
        <hr>
    </main>

    <footer class="footer" id="footer">
        <p class="text-center">&copy; <span itemprop="name"><?=Sys::mId('app', 'nameSite')?></span> <?=date('Y')?></p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
            integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
            crossorigin="anonymous"></script>

    <!--    <script src="/js/main.js"></script>-->
    <script src="/js/main.min.js"></script>
</body>
</html>
