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

CREATE TABLE employees(
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    patronymic VARCHAR(255),
    inn VARCHAR(12) NOT NULL,
    snils VARCHAR(11) NOT NULL,
    account_number VARCHAR(255) NOT NULL,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments (id)
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
    name VARCHAR(255) NOT NULL,
    amount NUMERIC NOT NULL,
    month_left INT NOT NULL,
    file VARCHAR(255),
    FOREIGN KEY (employee_id) REFERENCES employees (id)
);

CREATE TABLE accruals(
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    amount NUMERIC NOT NULL,
    month_left INT NOT NULL,
    file VARCHAR(255),
    FOREIGN KEY (employee_id) REFERENCES employees (id)
);

CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    employee_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles (id),
    FOREIGN KEY (employee_id) REFERENCES employees (id)
);