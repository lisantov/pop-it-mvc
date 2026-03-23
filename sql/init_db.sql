CREATE TABLE roles(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE departments(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE posts(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    salary NUMERIC NOT NULL,
    salary_bonus NUMERIC NOT NULL,
    salary_penalty NUMERIC NOT NULL
);

CREATE TABLE deduction_types(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE accrual_types(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE employees(
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    patronymic VARCHAR(255),
    inn VARCHAR(12) NOT NULL,
    snils VARCHAR(11) NOT NULL,
    account_number VARCHAR(255) NOT NULL,
    login VARCHAR(255),
    password VARCHAR(255),
    department_id INT,
    role_id INT NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments (id),
    FOREIGN KEY (role_id) REFERENCES roles (id)
);

CREATE TABLE employee_posts(
    employee_id INT NOT NULL,
    post_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees (id),
    FOREIGN KEY  (post_id) REFERENCES posts (id)
);

CREATE TABLE deductions(
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    type_id INT NOT NULL,
    amount NUMERIC NOT NULL,
    month DATE NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees (id),
    FOREIGN KEY (type_id) REFERENCES deduction_types (id)
);

CREATE TABLE accruals(
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    type_id INT NOT NULL,
    amount NUMERIC NOT NULL,
    month DATE NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees (id),
    FOREIGN KEY (type_id) REFERENCES accrual_types (id)
);