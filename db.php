<?php
/**
 * Practical 15 – Final Portfolio Submission
 * StudentHub Portal – Database Connection
 * Student: Krisha Patel | ID: 25CS069
 */

define('STUDENT_NAME', 'Krisha Patel');
define('STUDENT_ID',   '25CS069');
define('PRACTICAL_NO', 15);

function getDB(): PDO {
    $dbPath = __DIR__ . '/studenthub_p15.db';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    initDB($pdo);
    return $pdo;
}

function initDB(PDO $pdo): void {
    $pdo->exec("PRAGMA journal_mode=WAL;");

    // Users table (from P9/P10)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'student',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Students table (from P11)
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        course TEXT,
        year INTEGER,
        gpa REAL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Events table (from P12)
    $pdo->exec("CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT,
        event_date DATE,
        venue TEXT,
        created_by INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Courses table (from P13)
    $pdo->exec("CREATE TABLE IF NOT EXISTS courses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT UNIQUE NOT NULL,
        title TEXT NOT NULL,
        credits INTEGER DEFAULT 3,
        department TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Audit logs table (from P14)
    $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        action TEXT NOT NULL,
        ip_address TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed data
    seedData($pdo);
}

function seedData(PDO $pdo): void {
    // Seed admin user
    $count = $pdo->query("SELECT COUNT(*) as c FROM users")->fetch()['c'];
    if ($count == 0) {
        $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
        $studentPass = password_hash('student123', PASSWORD_BCRYPT);
        $pdo->exec("INSERT INTO users (name, email, password, role) VALUES
            ('Admin User', 'admin@studenthub.edu', '$adminPass', 'admin'),
            ('Krisha Patel', 'krisha@studenthub.edu', '$studentPass', 'student'),
            ('Raj Shah', 'raj@studenthub.edu', '$studentPass', 'student'),
            ('Priya Mehta', 'priya@studenthub.edu', '$studentPass', 'student')");
    }

    $sc = $pdo->query("SELECT COUNT(*) as c FROM students")->fetch()['c'];
    if ($sc == 0) {
        $pdo->exec("INSERT INTO students (name, email, course, year, gpa) VALUES
            ('Krisha Patel', 'krisha@studenthub.edu', 'B.Tech CSE', 2, 9.1),
            ('Raj Shah', 'raj@studenthub.edu', 'B.Tech IT', 3, 8.7),
            ('Priya Mehta', 'priya@studenthub.edu', 'BCA', 1, 9.3),
            ('Arjun Nair', 'arjun@studenthub.edu', 'MCA', 2, 8.5),
            ('Sneha Patel', 'sneha@studenthub.edu', 'B.Tech CSE', 4, 9.0)");
    }

    $ec = $pdo->query("SELECT COUNT(*) as c FROM events")->fetch()['c'];
    if ($ec == 0) {
        $pdo->exec("INSERT INTO events (title, description, event_date, venue, created_by) VALUES
            ('Tech Fest 2025', 'Annual technology festival with hackathons and workshops', '2025-02-15', 'Main Auditorium', 1),
            ('Placement Drive', 'Campus placement drive for final year students', '2025-03-10', 'Seminar Hall A', 1),
            ('Cultural Night', 'Annual cultural celebration with performances', '2025-04-05', 'Open Ground', 1),
            ('Research Symposium', 'Undergraduate research paper presentations', '2025-05-20', 'Conference Room', 1)");
    }

    $cc = $pdo->query("SELECT COUNT(*) as c FROM courses")->fetch()['c'];
    if ($cc == 0) {
        $pdo->exec("INSERT INTO courses (code, title, credits, department) VALUES
            ('WDF101', 'Web Development Fundamentals', 4, 'CSE'),
            ('DBMS201', 'Database Management Systems', 4, 'CSE'),
            ('OS301', 'Operating Systems', 3, 'CSE'),
            ('CN401', 'Computer Networks', 3, 'CSE'),
            ('AI501', 'Artificial Intelligence', 4, 'CSE')");
    }

    $al = $pdo->query("SELECT COUNT(*) as c FROM audit_logs")->fetch()['c'];
    if ($al == 0) {
        $pdo->exec("INSERT INTO audit_logs (user_id, action, ip_address) VALUES
            (1, 'Admin logged in', '127.0.0.1'),
            (1, 'Viewed student list', '127.0.0.1'),
            (2, 'Student logged in', '127.0.0.1'),
            (1, 'Created event: Tech Fest 2025', '127.0.0.1'),
            (1, 'Viewed audit logs', '127.0.0.1')");
    }
}
