<h2>Авторизация</h2>

<h3><?= app()->auth->user()->name ?? ''; ?></h3>
<?php
if (!app()->auth::check()):
    ?>
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <label>Логин <input type="text" name="login"></label>
        <label>Пароль <input type="password" name="password"></label>
        <button>Войти</button>
        <h3 class="message"><?= $message ?? ''; ?></h3>
    </form>
    <h3>Тестовые пользователи</h3>
    <div>
        <p>Login: <span style="font-weight: bold">admin</span></p>
        <p>Password: <span style="font-weight: bold">admin</span></p>
    </div>
    <div>
        <p>Login: <span style="font-weight: bold">buhgalter</span></p>
        <p>Password: <span style="font-weight: bold">buhgalter</span></p>
    </div>
<?php else: ?>
    Вы уже вошли в аккаунт
    <a href="<?= app()->route->getUrl('') ?>">На главную</a>
<?php endif;