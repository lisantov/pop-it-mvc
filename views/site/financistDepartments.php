<h2>Средняя зарплата по подразделениям:</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Средняя зарплата</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($departments): ?>
        <?php foreach ($departments as $key => $department): ?>
            <tr style="background-color: <?= (int)$key % 2 === 0 ? '#EFEFEF' : '#FFFFFF' ?>">
                <td><?= $key ?></td>
                <td><?= $department['name'] ?></td>
                <td><?= $department['average'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>