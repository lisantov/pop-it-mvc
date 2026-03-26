<h3>Редактирование бухгалтера: <?= $target ?></h3>
<form method="POST">
    <label>Логин <input type="text" name="login"></label>
    <input type="text" hidden name="id" value="<?= $id ?>">
    <button>Обновить</button>
    <h3 class="message"><?= $message ?? ''; ?></h3>
</form>