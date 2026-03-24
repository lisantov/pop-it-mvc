INSERT INTO posts (id, name, salary, salary_bonus, salary_penalty) VALUES
    (1, "Бухгалтер", 40000, 5000, 3000),
    (2, "Работяга", 35000, 10000, 5000),
    (3, "Директор", 100000, 15000, 1000);

INSERT INTO roles (id, name) VALUES
    (1, "Админ"),
    (2, "Бухгалтер");

INSERT INTO departments (id, name) VALUES
    (1, "Отдел разработки"),
    (2, "Отдел офисных крыс"),
    (3, "Отдел бухгалтеров"),
    (4, "Высшие чины");

INSERT INTO deduction_types (id, name) VALUES
    (1, "Кредит"),
    (2, "Ипотека"),
    (3, "Алименты"),
    (4, "Налог на налог");

INSERT INTO accrual_types (id, name) VALUES
    (1, "Премия"),
    (2, "Грант"),
    (3, "Щедрость директора");

INSERT INTO employees (
    id,
    first_name,
    last_name,
    patronymic,
    inn,
    snils,
    account_number,
    department_id
) VALUES
    (1, "Работник", "Работников", "Работникович", "123123123123", "12312312312",  "1234123412341234", 1),
    (2, "Бухгалтер", "Бухгалтеров", "Бухгалтерович", "321321321321", "32132132132",  "4321432143214321", 3),
    (3, "Админ", "Админов", "Админович", "999999999999", "99999999999",  "4444444444444444", 4);

INSERT INTO employee_posts (employee_id, post_id) VALUES
    (1, 2),
    (2, 1),
    (3, 3);

INSERT INTO accruals (id, employee_id, type_id, amount, month) VALUES
    (1, 1, 3, 15000, "2026-05-09");

INSERT INTO deductions (id, employee_id, type_id, amount, month) VALUES
    (1, 1, 3, 5000, "2026-05-09");

INSERT INTO users (id, login, password, role_id, employee_id) VALUES
    (1, "buhgalter", "buhgalter", 2, 2),
    (2, "admin", "admin", 1, 3);