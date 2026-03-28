<h2>Расчётный лист за <?= $period ?> <?= $period == 1 ? 'месяц' : ($period > 1 && $period < 5 ? 'месяца' : 'месяцев') ?> сотрудника: <?= $employee->getFullName() ?></h2>
<form method="GET">
    <input type="text" hidden value="<?= $employee->id ?>" name="id">
    <label for="period">За сколько месяцев отображать расчёт?</label>
    <div style="display: flex; align-items: center; gap: 4px">
        <span>1</span>
        <input min="1" max="12" id="period" type="range" value="<?= $period ?>" name="period">
        <span>12</span>
    </div>
    <button>Поиск</button>
</form>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Операция</th>
        <th>Объём</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($transactions): ?>
        <?php foreach ($transactions as $key => $transaction): ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= $transaction['name'] ?></td>
                <td><?= $transaction['result'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>