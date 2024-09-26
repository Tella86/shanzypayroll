-- General Tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'student', 'parent', 'librarian') NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    PRIMARY KEY(user_id, role_id),
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(role_id) REFERENCES roles(id)
);

-- Human Resource Management
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    position VARCHAR(100),
    salary DECIMAL(10, 2),
    hire_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE employee_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    attendance_date DATE,
    status ENUM('present', 'absent', 'on_leave') DEFAULT 'present',
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    leave_type ENUM('sick', 'vacation', 'other'),
    start_date DATE,
    end_date DATE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    salary DECIMAL(10, 2),
    bonuses DECIMAL(10, 2) DEFAULT 0,
    deductions DECIMAL(10, 2) DEFAULT 0,
    total_pay DECIMAL(10, 2) GENERATED ALWAYS AS (salary + bonuses - deductions) STORED,
    payment_date DATE,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Students Management
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    admission_date DATE,
    guardian_name VARCHAR(255),
    guardian_phone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE student_enrollment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    class_id INT,
    enrollment_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- Teachers/Lecturer Management
CREATE TABLE teacher_subject (
    teacher_id INT,
    subject_id INT,
    PRIMARY KEY (teacher_id, subject_id),
    FOREIGN KEY (teacher_id) REFERENCES employees(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Classes Management
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(100) NOT NULL,
    teacher_id INT,
    FOREIGN KEY (teacher_id) REFERENCES employees(id)
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- Finance & Fees Management
CREATE TABLE fees_structure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    total_fees DECIMAL(10, 2),
    due_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    amount DECIMAL(10, 2),
    payment_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE financial_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('income', 'expense'),
    amount DECIMAL(10, 2),
    report_date DATE
);

-- Exams Module
CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_name VARCHAR(100),
    class_id INT,
    exam_date DATE,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

CREATE TABLE exam_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    exam_id INT,
    marks_obtained DECIMAL(5, 2),
    grade VARCHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (exam_id) REFERENCES exams(id)
);

-- Class Register/Attendance
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    class_id INT,
    attendance_date DATE,
    status ENUM('present', 'absent') DEFAULT 'present',
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- Library Module
CREATE TABLE library_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_title VARCHAR(255),
    author VARCHAR(255),
    isbn VARCHAR(20),
    quantity INT DEFAULT 1
);

CREATE TABLE issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    student_id INT,
    issue_date DATE,
    return_date DATE,
    status ENUM('issued', 'returned') DEFAULT 'issued',
    FOREIGN KEY (book_id) REFERENCES library_books(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Hostel Management
CREATE TABLE hostel_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) UNIQUE,
    capacity INT,
    available INT
);

CREATE TABLE hostel_allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    room_id INT,
    allocation_date DATE,
    checkout_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (room_id) REFERENCES hostel_rooms(id)
);

-- Transport Management
CREATE TABLE routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(255),
    starting_point VARCHAR(255),
    destination VARCHAR(255)
);

CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_number VARCHAR(20),
    driver_name VARCHAR(255)
);

CREATE TABLE transport_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT,
    vehicle_id INT,
    departure_time TIME,
    arrival_time TIME,
    FOREIGN KEY (route_id) REFERENCES routes(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);

-- Communication (SMS/Email)
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    notification_type ENUM('email', 'sms'),
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Admissions Module
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    application_date DATE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Reports & Timetable
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('attendance', 'exam', 'finance'),
    report_data TEXT,
    report_date DATE
);

CREATE TABLE timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    subject_id INT,
    day ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);
