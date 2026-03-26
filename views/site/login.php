<h2>Авторизация</h2>

<h3><?= app()->auth->user()->name ?? ''; ?></h3>
<?php
if (!app()->auth::check()):
    ?>
    <form method="post">
        <label>Логин <input type="text" name="login"></label>
        <label>Пароль <input type="password" name="password"></label>
        <button>Войти</button>
        <h3 class="message"><?= $message ?? ''; ?></h3>
    </form>
<?php endif;