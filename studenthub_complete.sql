-- ============================================================
--  StudentHub Portal – Complete Database Dump
--  Practical 15 – Final Portfolio Submission
--  Student : Krisha Patel
--  ID      : 25CS069
--  Date    : 2025-01-27
-- ============================================================

PRAGMA journal_mode = WAL;
PRAGMA foreign_keys = ON;

-- ── SCHEMA ────────────────────────────────────────────────────

-- Table: users  (Introduced: Practical 9 & 10)
CREATE TABLE IF NOT EXISTS users (
    id         INTEGER  PRIMARY KEY AUTOINCREMENT,
    name       TEXT     NOT NULL,
    email      TEXT     UNIQUE NOT NULL,
    password   TEXT     NOT NULL,            -- bcrypt hash
    role       TEXT     NOT NULL DEFAULT 'student',  -- 'admin' | 'student'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: students  (Introduced: Practical 11)
CREATE TABLE IF NOT EXISTS students (
    id         INTEGER  PRIMARY KEY AUTOINCREMENT,
    name       TEXT     NOT NULL,
    email      TEXT     UNIQUE NOT NULL,
    course     TEXT,
    year       INTEGER,
    gpa        REAL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: events  (Introduced: Practical 12)
CREATE TABLE IF NOT EXISTS events (
    id          INTEGER  PRIMARY KEY AUTOINCREMENT,
    title       TEXT     NOT NULL,
    description TEXT,
    event_date  DATE,
    venue       TEXT,
    created_by  INTEGER  REFERENCES users(id),
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: courses  (Introduced: Practical 13 – REST API)
CREATE TABLE IF NOT EXISTS courses (
    id         INTEGER  PRIMARY KEY AUTOINCREMENT,
    code       TEXT     UNIQUE NOT NULL,
    title      TEXT     NOT NULL,
    credits    INTEGER  DEFAULT 3,
    department TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: audit_logs  (Introduced: Practical 14)
CREATE TABLE IF NOT EXISTS audit_logs (
    id         INTEGER  PRIMARY KEY AUTOINCREMENT,
    user_id    INTEGER  REFERENCES users(id),
    action     TEXT     NOT NULL,
    ip_address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ── SEED DATA ─────────────────────────────────────────────────

-- Users (passwords are bcrypt of: admin123 / student123)
INSERT OR IGNORE INTO users (name, email, password, role) VALUES
    ('Admin User',    'admin@studenthub.edu',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
    ('Krisha Patel',  'krisha@studenthub.edu',  '$2y$10$TKh8H1.PFkk34OM9JxomxO5j0z4MlVPKsQZX4m8YQbSXV1vl8m8V.', 'student'),
    ('Raj Shah',      'raj@studenthub.edu',     '$2y$10$TKh8H1.PFkk34OM9JxomxO5j0z4MlVPKsQZX4m8YQbSXV1vl8m8V.', 'student'),
    ('Priya Mehta',   'priya@studenthub.edu',   '$2y$10$TKh8H1.PFkk34OM9JxomxO5j0z4MlVPKsQZX4m8YQbSXV1vl8m8V.', 'student'),
    ('Arjun Nair',    'arjun@studenthub.edu',   '$2y$10$TKh8H1.PFkk34OM9JxomxO5j0z4MlVPKsQZX4m8YQbSXV1vl8m8V.', 'student');

-- Students
INSERT OR IGNORE INTO students (name, email, course, year, gpa) VALUES
    ('Krisha Patel', 'krisha@studenthub.edu', 'B.Tech CSE',  2, 9.1),
    ('Raj Shah',     'raj@studenthub.edu',    'B.Tech IT',   3, 8.7),
    ('Priya Mehta',  'priya@studenthub.edu',  'BCA',         1, 9.3),
    ('Arjun Nair',   'arjun@studenthub.edu',  'MCA',         2, 8.5),
    ('Sneha Patel',  'sneha@studenthub.edu',  'B.Tech CSE',  4, 9.0);

-- Events
INSERT OR IGNORE INTO events (title, description, event_date, venue, created_by) VALUES
    ('Tech Fest 2025',        'Annual technology festival with hackathons',     '2025-02-15', 'Main Auditorium',  1),
    ('Placement Drive',       'Campus placement drive for final year students', '2025-03-10', 'Seminar Hall A',   1),
    ('Cultural Night',        'Annual cultural celebration with performances',  '2025-04-05', 'Open Ground',      1),
    ('Research Symposium',    'Undergraduate research paper presentations',     '2025-05-20', 'Conference Room',  1),
    ('Hackathon 2025',        '24-hour hackathon open to all students',         '2025-06-01', 'IT Lab Block',     1);

-- Courses
INSERT OR IGNORE INTO courses (code, title, credits, department) VALUES
    ('WDF101',  'Web Development Fundamentals',  4, 'CSE'),
    ('DBMS201', 'Database Management Systems',   4, 'CSE'),
    ('OS301',   'Operating Systems',             3, 'CSE'),
    ('CN401',   'Computer Networks',             3, 'CSE'),
    ('AI501',   'Artificial Intelligence',       4, 'CSE'),
    ('DS601',   'Data Structures & Algorithms',  4, 'CSE'),
    ('SE701',   'Software Engineering',          3, 'CSE');

-- Audit Logs
INSERT OR IGNORE INTO audit_logs (user_id, action, ip_address) VALUES
    (1, 'Admin logged in',              '127.0.0.1'),
    (1, 'Viewed student list',          '127.0.0.1'),
    (2, 'Student Krisha Patel logged in','127.0.0.1'),
    (1, 'Created event: Tech Fest 2025','127.0.0.1'),
    (1, 'Viewed audit logs',            '127.0.0.1'),
    (3, 'Student Raj Shah logged in',   '127.0.0.1'),
    (1, 'Updated student record id=2',  '127.0.0.1'),
    (1, 'Viewed course list via API',   '127.0.0.1');

-- ── END OF DUMP ───────────────────────────────────────────────
