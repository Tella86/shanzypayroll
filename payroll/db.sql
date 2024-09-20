-- Employees Table
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    department VARCHAR(100),
    role ENUM('admin', 'staff') DEFAULT 'staff',
    basic_salary DECIMAL(10, 2)
);

-- Payroll Table
CREATE TABLE payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    month VARCHAR(20),
    basic_salary DECIMAL(10, 2),
    bonus DECIMAL(10, 2),
    deductions DECIMAL(10, 2),
    net_salary DECIMAL(10, 2),
    processed_date DATE,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action VARCHAR(255),
    details TEXT,
    log_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id)
);
-- Departments Table
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Insert predefined department names
INSERT INTO departments (name) VALUES
('Maintenance'),
('Kitchen'),
('Security'),
('Grounds'),
('Farm'),
('Accounts'),
('Supply Chain Management'),
('Registry'),
('Library'),
('Boarding');
-- Alter Employees Table to add Department ID
ALTER TABLE employees ADD department_id INT;

-- Set department_id as a foreign key referencing the departments table
ALTER TABLE employees 
ADD CONSTRAINT fk_department
FOREIGN KEY (department_id) 
REFERENCES departments(id);
-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'staff') DEFAULT 'staff',
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);
