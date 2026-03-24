<h2>Регистрация нового пользователя</h2>
<h3><?= $message ?? ''; ?></h3>
<form method="post">
    <label>Логин <input type="text" name="login"></label>
    <label>Пароль <input type="password" name="password"></label>
    <label>Роль <input type="text" name="role_id"></label>
    <label>Сотрудник <input type="text" name="employee_id"></label>
    <button>Зарегистрироваться</button>
</form>