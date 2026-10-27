<?php
/**
 * Practical 15 – Final Portfolio Dashboard
 * StudentHub Portal – Integrated Overview
 * Student: Krisha Patel | ID: 25CS069
 */
require_once __DIR__ . '/db.php';

$pdo = getDB();

// Stats
$stats = [
    'users'    => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'students' => $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    'events'   => $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn(),
    'courses'  => $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
];

$recentLogs = $pdo->query("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentStudents = $pdo->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 4")->fetchAll();
$upcomingEvents = $pdo->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 3")->fetchAll();
$courses = $pdo->query("SELECT * FROM courses LIMIT 5")->fetchAll();

$practicals = [
    ['no'=>9,  'title'=>'User Registration & Authentication',  'desc'=>'Secure registration with bcrypt, email validation, session management', 'icon'=>'🔐', 'color'=>'primary'],
    ['no'=>10, 'title'=>'Role-Based Access Control (Session)', 'desc'=>'Session security, role checks, CSRF tokens, HTTP-only cookies',          'icon'=>'🛡️', 'color'=>'success'],
    ['no'=>11, 'title'=>'Student Records CRUD',                'desc'=>'Full Create/Read/Update/Delete for student records with pagination',      'icon'=>'📋', 'color'=>'info'],
    ['no'=>12, 'title'=>'Event Management & File Upload',      'desc'=>'Event scheduling, file attachment uploads, Bootstrap 5 UI',              'icon'=>'📅', 'color'=>'warning'],
    ['no'=>13, 'title'=>'PHP REST API + Fetch SPA',            'desc'=>'RESTful JSON API consumed by a Fetch-based Single Page Application',     'icon'=>'⚡', 'color'=>'danger'],
    ['no'=>14, 'title'=>'RBAC Dashboard & Audit Trail',        'desc'=>'Dynamic admin dashboard, role menus, audit logging, 403 enforcement',    'icon'=>'📊', 'color'=>'dark'],
    ['no'=>15, 'title'=>'Final Portfolio Submission',          'desc'=>'Integrated portal overview, database dump, README, test & security report','icon'=>'🎓', 'color'=>'secondary'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>StudentHub Portal – Final Portfolio | <?= STUDENT_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root { --primary: #1a237e; --accent: #0288d1; }
body  { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }

.hero {
    background: linear-gradient(135deg, #1a237e 0%, #0288d1 100%);
    color: #fff; padding: 3rem 0 2rem; text-align: center;
}
.hero h1 { font-size: 2.6rem; font-weight: 800; letter-spacing: -0.5px; }
.hero p  { opacity: .85; font-size: 1.1rem; }
.badge-student { background: rgba(255,255,255,.2); border-radius: 50px; padding: .4rem 1.2rem; font-size: .95rem; }

.stat-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.08); transition: transform .2s; }
.stat-card:hover { transform: translateY(-4px); }
.stat-icon { font-size: 2.5rem; }

.practical-card { border-radius: 12px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.07); transition: transform .2s; }
.practical-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px rgba(0,0,0,.12); }
.practical-icon { font-size: 2rem; }

.section-title { font-weight: 700; color: #1a237e; border-left: 4px solid #0288d1; padding-left: .75rem; margin-bottom: 1.5rem; }
.github-banner { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); color:#fff; border-radius:16px; padding:2rem; }

.table th { background: #1a237e; color: #fff; }
footer { background: #1a237e; color: rgba(255,255,255,.75); padding: 1.5rem 0; font-size: .88rem; text-align: center; margin-top: 3rem; }
</style>
</head>
<body>

<!-- Hero -->
<div class="hero">
  <div class="container">
    <div class="mb-2">🎓</div>
    <h1>StudentHub Portal</h1>
    <p class="mb-3">Web Development Fundamentals — Final Portfolio Submission</p>
    <span class="badge-student">
      <i class="bi bi-person-badge"></i> <?= STUDENT_NAME ?> &nbsp;|&nbsp; <?= STUDENT_ID ?>
    </span>
    <div class="mt-3">
      <span class="badge bg-light text-dark me-2">Practical <?= PRACTICAL_NO ?></span>
      <span class="badge bg-success">All Practicals Completed ✓</span>
    </div>
  </div>
</div>

<div class="container py-5">

  <!-- Stats Row -->
  <h2 class="section-title">📊 Live Database Statistics</h2>
  <div class="row g-4 mb-5">
    <?php
    $statConfig = [
      'users'    => ['icon'=>'bi-people-fill',    'label'=>'Registered Users',  'color'=>'primary'],
      'students' => ['icon'=>'bi-mortarboard',    'label'=>'Students',          'color'=>'success'],
      'events'   => ['icon'=>'bi-calendar-event', 'label'=>'Events',            'color'=>'warning'],
      'courses'  => ['icon'=>'bi-book-fill',      'label'=>'Courses',           'color'=>'danger'],
    ];
    foreach ($statConfig as $key => $cfg):
    ?>
    <div class="col-6 col-md-3">
      <div class="card stat-card text-center p-4">
        <div class="stat-icon text-<?= $cfg['color'] ?>"><i class="bi <?= $cfg['icon'] ?>"></i></div>
        <div class="fw-bold fs-2 mt-2"><?= $stats[$key] ?></div>
        <div class="text-muted small"><?= $cfg['label'] ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- GitHub Repo -->
  <h2 class="section-title">🔗 GitHub Repository</h2>
  <div class="github-banner mb-5">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-github me-2"></i>studenthub-wdf</h4>
        <p class="mb-2 opacity-75">Complete StudentHub Portal — Practical 9 through 15</p>
        <code class="text-warning">https://github.com/krisha-patel-25CS069/studenthub-wdf</code>
        <div class="mt-3">
          <span class="badge bg-primary me-1">PHP</span>
          <span class="badge bg-warning text-dark me-1">Bootstrap 5</span>
          <span class="badge bg-success me-1">SQLite</span>
          <span class="badge bg-info text-dark me-1">REST API</span>
          <span class="badge bg-danger">RBAC</span>
        </div>
      </div>
      <div class="col-md-4 text-center mt-3 mt-md-0">
        <div class="text-white-50 mb-1" style="font-size:4rem;"><i class="bi bi-git"></i></div>
        <a href="https://github.com/krisha-patel-25CS069/studenthub-wdf"
           class="btn btn-outline-light btn-sm" target="_blank">
          <i class="bi bi-box-arrow-up-right"></i> View on GitHub
        </a>
      </div>
    </div>
  </div>

  <!-- Practical Listing -->
  <h2 class="section-title">📚 All Practicals Overview</h2>
  <div class="row g-3 mb-5">
    <?php foreach ($practicals as $p): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card practical-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <span class="practical-icon me-2"><?= $p['icon'] ?></span>
            <span class="badge bg-<?= $p['color'] ?>">Practical <?= $p['no'] ?></span>
          </div>
          <h6 class="card-title fw-bold"><?= htmlspecialchars($p['title']) ?></h6>
          <p class="card-text text-muted small"><?= htmlspecialchars($p['desc']) ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <?php if ($p['no'] == 15): ?>
          <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Current</span>
          <?php else: ?>
          <span class="badge bg-success"><i class="bi bi-check-circle"></i> Completed</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Data Tables Row -->
  <div class="row g-4 mb-5">
    <!-- Recent Students -->
    <div class="col-md-6">
      <h5 class="section-title">👥 Recent Students</h5>
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm shadow-sm rounded">
          <thead><tr><th>Name</th><th>Course</th><th>GPA</th></tr></thead>
          <tbody>
            <?php foreach ($recentStudents as $s): ?>
            <tr>
              <td><?= htmlspecialchars($s['name']) ?></td>
              <td><small><?= htmlspecialchars($s['course']) ?></small></td>
              <td><span class="badge bg-success"><?= $s['gpa'] ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Upcoming Events -->
    <div class="col-md-6">
      <h5 class="section-title">📅 Upcoming Events</h5>
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm shadow-sm">
          <thead><tr><th>Event</th><th>Date</th><th>Venue</th></tr></thead>
          <tbody>
            <?php foreach ($upcomingEvents as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['title']) ?></td>
              <td><small><?= $e['event_date'] ?></small></td>
              <td><small><?= htmlspecialchars($e['venue']) ?></small></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Courses & Audit -->
  <div class="row g-4 mb-5">
    <!-- Courses -->
    <div class="col-md-6">
      <h5 class="section-title">📖 Courses</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-sm shadow-sm">
          <thead><tr><th>Code</th><th>Title</th><th>Credits</th></tr></thead>
          <tbody>
            <?php foreach ($courses as $c): ?>
            <tr>
              <td><code><?= htmlspecialchars($c['code']) ?></code></td>
              <td><?= htmlspecialchars($c['title']) ?></td>
              <td class="text-center"><?= $c['credits'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Audit Log -->
    <div class="col-md-6">
      <h5 class="section-title">🔍 Recent Audit Logs</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-sm shadow-sm">
          <thead><tr><th>Action</th><th>IP</th><th>Time</th></tr></thead>
          <tbody>
            <?php foreach ($recentLogs as $log): ?>
            <tr>
              <td><?= htmlspecialchars($log['action']) ?></td>
              <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
              <td><small><?= $log['created_at'] ?></small></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Security Summary -->
  <h2 class="section-title">🔒 Security & Testing Summary</h2>
  <div class="row g-3 mb-4">
    <?php
    $checks = [
      ['label'=>'Password Hashing (bcrypt)',      'status'=>true],
      ['label'=>'SQL Injection Prevention (PDO)',  'status'=>true],
      ['label'=>'XSS Prevention (htmlspecialchars)','status'=>true],
      ['label'=>'CSRF Token Protection',           'status'=>true],
      ['label'=>'Session Security & Regeneration', 'status'=>true],
      ['label'=>'Role-Based Access Control',       'status'=>true],
      ['label'=>'HTTP-Only / Secure Cookies',      'status'=>true],
      ['label'=>'Audit Trail Logging',             'status'=>true],
      ['label'=>'Input Validation & Sanitization', 'status'=>true],
      ['label'=>'Responsive Bootstrap 5 UI',       'status'=>true],
      ['label'=>'REST API JSON Security',          'status'=>true],
      ['label'=>'File Upload Validation',          'status'=>true],
    ];
    foreach ($checks as $c): ?>
    <div class="col-md-4">
      <div class="d-flex align-items-center">
        <span class="me-2 fs-5"><?= $c['status'] ? '✅' : '❌' ?></span>
        <span class="small"><?= htmlspecialchars($c['label']) ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- DB Dump info -->
  <div class="alert alert-info d-flex align-items-center mt-4">
    <i class="bi bi-database-fill me-3 fs-4"></i>
    <div>
      <strong>Database Dump:</strong> <code>studenthub_complete.sql</code> — Contains complete schema
      (users, students, events, courses, audit_logs) with sample seed data for all practicals.
    </div>
  </div>

</div><!-- /container -->

<footer>
  <div class="container">
    <strong>StudentHub Portal</strong> — Web Development Fundamentals &nbsp;|&nbsp;
    <?= STUDENT_NAME ?> &nbsp;|&nbsp; <?= STUDENT_ID ?> &nbsp;|&nbsp;
    Practical <?= PRACTICAL_NO ?> — Final Portfolio Submission
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
