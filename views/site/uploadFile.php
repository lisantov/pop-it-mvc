<h2>Добавить файл к <?= $name ?> сотрудника (<?= $target ?>)</h2>

<form method="POST" enctype="multipart/form-data">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <input type="file" name="file" accept="image/*">
    <button type="submit">Прикрепить</button>
    <a href="<?= $rollback ?>" class="button ghost">Отменить</a>
</form>