<h2>Админ панель</h2>
<div style="margin-top: 20px;display: grid; grid-template-columns: 1fr 2fr; gap: 60px">
    <div style="display: flex; flex-direction: column; gap: 10px">
        <h3>Добавить бухгалтера</h3>
        <form action="" method="POST">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <label>Логин <input type="text" name="login"></label>
            <span class="error"><?= $errors['login'][0] ?></span>
            <label>Пароль <input type="password" name="password"></label>
            <span class="error"><?= $errors['password'][0] ?></span>
            <label>Сотрудник
                <select name="employee_id">
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee->id ?>"><?= $employee->getFullName() ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <span class="error"><?= $errors['employee_id'][0] ?></span>
            <button type="submit">Добавить</button>
            <h3 class="message"><?= $message ?? ''; ?></h3>
        </form>
    </div>
    <div style="display: flex; flex-direction: column; gap: 10px">
        <h3>Все бухгалтеры</h3>
        <form>
            <label style="width: 100%; display: grid; grid-template-columns: auto 1fr; gap: 16px; align-items: center">Поиск<input value="<?= $search ?>" type="search" name="search"></label>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Пароль</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($financists): ?>
                <?php foreach ($financists as $financist): ?>
                    <tr>
                        <td><?= $financist['id'] ?></td>
                        <td><?= htmlspecialchars($financist['login']) ?></td>
                        <td><?= $financist['password'] ?></td>
                        <td>
                            <a class="button danger" href="<?= app()->route->getUrl('admin/delete').'?id='.$financist->id ?>" type="submit">Удалить</a>
                            <a class="button" href="<?= app()->route->getUrl('admin/edit').'?id='.$financist->id ?>" type="submit">Редактировать</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Бухгалтеров с логином '<?= $search ?>' не найдено</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>