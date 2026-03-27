<h2>Вы действительно хотите удалить <?= $name ?? 'объект' ?>? (<?= $target ?? '' ?>)</h2>
<form method="POST" style="flex-direction: row; align-items: center">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <button type="submit">Да</button>
    <a style="display: block; color: black" href="<?= $rollback ?>">Отменить</a>
</form>