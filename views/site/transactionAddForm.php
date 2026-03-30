<h2>Добавление <?= $name ?> сотруднику: <?= $employee->getFullName() ?></h2>
<form method="POST">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <input name="employee_id" type="hidden" value="<?= $id ?>"/>
    <label>Название <input type="text" name="name" value="<?= $transaction->name ?>"></label>
    <span class="error"><?= $errors['name'][0] ?></span>
    <label>Сумма <input type="number" name="amount" min="1" value="<?= $transaction->amount ?>"></label>
    <span class="error"><?= $errors['amount'][0] ?></span>
    <label>Осталось месяцев <input type="number" name="month_left" min="1" value="<?= $transaction->month_left ?>"></label>
    <span class="error"><?= $errors['month_left'][0] ?></span>
    <button>создать</button>
    <h3 class="message"><?= $message ?? ''; ?></h3>
</form>