<h2>Управление надбавками сотрудника: <?= $employee->getFullName() ?></h2>
<div style="display: flex; gap: 12px">
    <a class="button" href="<?= app()->route->getUrl('financist/'.$entities.'/add').'?id='.$employee->id ?>">Добавить</a>
</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Тип</th>
        <th>Объём</th>
        <th>Осталось месяцев</th>
        <th>Действия</th>
        <th>Связанный файл</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($transactions): ?>
        <?php foreach ($transactions as $key => $transaction): ?>
            <tr style="background-color: <?= (int)$key % 2 === 0 ? '#EFEFEF' : '#FFFFFF' ?>">
                <td><?= $key ?></td>
                <td><?= $transaction->name ?></td>
                <td><?= $transaction->amount ?></td>
                <td><?= $transaction->month_left ?></td>
                <td style="display: flex; gap: 4px">
                    <a class="button danger" href="<?= app()->route->getUrl('financist/'.$entities.'/delete?id=').$transaction->id ?>">Удалить</a>
                    <a class="button" href="<?= app()->route->getUrl('financist/'.$entities.'/edit?id=').$transaction->id ?>">Редактировать</a>
                </td>
                <td>
                    <?php if ($transaction->file): ?>
                        <a class="button" href="<?= app()->settings->getRootPath().$transaction->file ?>">Перейти</a>
                    <?php else: ?>
                        <a class="button" href="<?= app()->route->getUrl('financist/'.$entities.'/upload?id=').$transaction->id ?>">Добавить файл</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <tr>
        <td>Итого</td>
        <td></td>
        <td><?= $sum ?></td>
    </tr>
    </tbody>
</table>