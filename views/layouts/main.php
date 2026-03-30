<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>бухгалтерский учёт</title>
    <link href="/css/main.css" rel="stylesheet">
</head>
<body>
<header class="container-fluid">
    <nav class="container-fluid row row-cols-12 g-4">
        <a class="col text-center" href="<?= app()->route->getUrl('') ?>">Главная</a>
        <?php
        if (!app()->auth::check()):
            ?>
            <a class="col text-center" href="<?= app()->route->getUrl('login') ?>">Вход</a>
        <?php
        else:
            ?>

            <?php
            if (app()->auth::user()->isAdmin()):
                ?>
                <a class="col text-center" href="<?= app()->route->getUrl('admin') ?>">Админ-панель</a>
            <?php
            endif;
            ?>
            <?php
            if (app()->auth::user()->isFinancist()):
                ?>
                <a class="col text-center" href="<?= app()->route->getUrl('financist') ?>">Управление сотрудниками</a>
                <a class="col text-center" href="<?= app()->route->getUrl('financist/departments') ?>">Подразделения</a>
            <?php
            endif;
            ?>

            <a class="col text-center" href="<?= app()->route->getUrl('logout') ?>">Выход (<?= app()->auth::user()->login ?>)</a>
        <?php
        endif;
        ?>
    </nav>
</header>
<main>
    <?= $content ?? '' ?>
</main>

</body>
</html>