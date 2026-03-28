<h2>Все сотрудники</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>ИНН</th>
            <th>СНИЛС</th>
            <th>Номер счёта</th>
            <th>Подразделение</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($employees): ?>
        <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?= $employee['id'] ?></td>
                <td><?= $employee->getFullName() ?></td>
                <td><?= $employee->inn ?></td>
                <td><?= $employee->snils ?></td>
                <td><?= $employee->account_number ?></td>
                <td><?= $employee->getDepartmentName() ?></td>
                <td style="display: flex; flex-direction: column; gap: 4px">
                    <a class="button" href="<?= app()->route->getUrl('admin/delete').'?id='.$employee->id ?>">Управление надбавками</a>
                    <a class="button" href="<?= app()->route->getUrl('admin/edit').'?id='.$employee->id ?>">Управление вычетами</a>
                    <a class="button ghost" href="<?= app()->route->getUrl('financist/stats').'?id='.$employee->id.'&period=1' ?>">Сформировать расчётный лист</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>