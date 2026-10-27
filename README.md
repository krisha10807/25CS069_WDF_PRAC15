# StudentHub Portal — Web Development Fundamentals

**Student:** Krisha Patel  
**Student ID:** 25CS069  
**Course:** Web Development Fundamentals (WDF)  
**Practical:** 15 — Final Portfolio Submission

---

## 📋 Project Overview

StudentHub Portal is a comprehensive web application built across **7 practicals (P9–P15)** that demonstrates full-stack web development with PHP, SQLite, Bootstrap 5, and REST APIs.

### Practicals Covered

| # | Practical | Description |
|---|-----------|-------------|
| 9 | User Registration & Authentication | Secure sign-up with bcrypt, session management |
| 10 | Role-Based Access Control (Sessions) | Admin/student roles, CSRF tokens, session security |
| 11 | Student Records CRUD | Create, Read, Update, Delete operations with pagination |
| 12 | Event Management & File Upload | Event scheduling, file attachment upload, Bootstrap UI |
| 13 | PHP REST API + Fetch SPA | RESTful JSON API consumed by a single-page Fetch app |
| 14 | RBAC Dashboard & Audit Trail | Dynamic admin dashboard, role menus, audit logging |
| 15 | Final Portfolio Submission | Integrated overview, DB dump, README, test report |

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.x with PDO
- **Database:** SQLite 3 (zero-config, portable)
- **Frontend:** Bootstrap 5.3, HTML5, CSS3, JavaScript (Fetch API)
- **Security:** bcrypt, CSRF tokens, prepared statements, RBAC, audit logging

---

## 🚀 Setup & Installation

### Prerequisites

- PHP 8.0+ with SQLite PDO extension (bundled with XAMPP)
- A web browser (Chrome, Edge, Firefox)

### Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/krisha-patel-25CS069/studenthub-wdf.git
cd studenthub-wdf

# 2. Start PHP built-in server for any practical
cd Practical_15
php -S localhost:8100

# 3. Open in browser
# http://localhost:8100/
```

### Per-Practical Ports (for testing)

| Practical | Port | Command |
|-----------|------|---------|
| P9  | 8089 | `php -S localhost:8089` |
| P10 | 8094 | `php -S localhost:8094` |
| P12 | 8097 | `php -S localhost:8097` |
| P13 | 8098 | `php -S localhost:8098` |
| P14 | 8099 | `php -S localhost:8099` |
| P15 | 8100 | `php -S localhost:8100` |

### Default Credentials

| Role    | Email                   | Password    |
|---------|-------------------------|-------------|
| Admin   | admin@studenthub.edu    | admin123    |
| Student | krisha@studenthub.edu   | student123  |

---

## 🗄️ Database

The database is **SQLite** and is created automatically on first run. A complete SQL dump is available at `studenthub_complete.sql`.

### Tables

| Table | Records | Purpose | Introduced |
|-------|---------|---------|------------|
| `users` | 4+ | Authentication & role management | P9, P10 |
| `students` | 5+ | Student profile records | P11 |
| `events` | 4+ | Campus event management | P12 |
| `courses` | 5+ | Course catalogue (REST API) | P13 |
| `audit_logs` | 5+ | Action audit trail | P14 |

---

## 🔒 Security Features

- ✅ Password hashing with `password_hash()` / `password_verify()` (bcrypt)
- ✅ SQL injection prevention via PDO prepared statements
- ✅ XSS prevention with `htmlspecialchars()`
- ✅ CSRF token protection on all POST forms
- ✅ Session fixation prevention (`session_regenerate_id()`)
- ✅ Role-Based Access Control (admin/student)
- ✅ HTTP 403 Forbidden for unauthorised access
- ✅ File upload MIME type & size validation
- ✅ Audit trail logging with IP & timestamp
- ✅ HTTP-only session cookies

---

## 🧪 Testing

All **55 test cases** across 7 practicals pass successfully (100% pass rate).

See `test_security_report.php` for the full interactive report, or `Practical_15_Test_Report.docx` for the Word document version.

---

## 📁 Project Structure

```
StudentHub/
├── Practical_9/     — User Registration & Authentication
├── Practical_10/    — Role-Based Sessions & Security
├── Practical_11/    — Student Records CRUD
├── Practical_12/    — Event Management & File Upload
├── Practical_13/    — REST API + Fetch SPA
├── Practical_14/    — RBAC Dashboard & Audit Trail
└── Practical_15/    — Final Portfolio (this folder)
    ├── index.php                  — Integrated portal dashboard
    ├── test_security_report.php   — Test & security report page
    ├── db.php                     — Database connection & seeding
    ├── studenthub_complete.sql    — Complete database dump
    ├── README.md                  — This file
    ├── screenshots/               — Auto-generated screenshots
    └── Practical_15_25CS069.zip   — Submission package
```

---

## 🔗 GitHub Repository

**[https://github.com/krisha-patel-25CS069/studenthub-wdf](https://github.com/krisha-patel-25CS069/studenthub-wdf)**

---

## 📝 License

This project was developed as coursework for the Web Development Fundamentals module.

**© 2025 Krisha Patel (25CS069) — All rights reserved.**
