<h2>Админ панель</h2>
<div style="margin-top: 20px;display: grid; grid-template-columns: 1fr 1fr; gap: 60px">
    <div style="display: flex; flex-direction: column; gap: 10px">
        <h3>Добавить бухгалтера</h3>
        <form action="" method="POST">
            <label>Логин <input type="text" name="login" required></label>
            <label>Пароль <input type="password" name="password" required></label>
            <label>Сотрудник
                <select name="employee_id" required>
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee->id ?>"><?= $employee->getFullName() ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Добавить</button>
            <h3 class="message"><?= $message ?? ''; ?></h3>
        </form>
    </div>
    <div style="display: flex; flex-direction: column; gap: 10px">
        <h3>Все бухгалтеры</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Пароль</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($financists as $financist): ?>
                <tr>
                    <td><?= $financist->id ?></td>
                    <td><?= htmlspecialchars($financist->login) ?></td>
                    <td><?= $financist->password ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>