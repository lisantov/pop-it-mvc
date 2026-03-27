<h3>Редактирование бухгалтера: <?= $target ?></h3>
<form method="POST">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <label>Логин <input type="text" name="login"></label>
    <span class="error"><?= $errors['login'][0] ?></span>
    <input type="text" hidden name="id" value="<?= $id ?>">
    <button>Обновить</button>
    <h3 class="message"><?= $message ?? ''; ?></h3>
</form>