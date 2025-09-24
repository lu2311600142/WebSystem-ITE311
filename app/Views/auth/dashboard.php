<?php
// Safe defaults (avoid undefined var errors)
$role = $role ?? session('role') ?? 'student';
$username = $username ?? session('username') ?? 'User';

$totalUsers    = $totalUsers    ?? 0;
$totalAdmins   = $totalAdmins   ?? 0;
$totalTeachers = $totalTeachers ?? 0;
$totalStudents = $totalStudents ?? 0;
$recentUsers   = $recentUsers   ?? [];

$totalCourses       = $totalCourses       ?? 0;
$pendingAssignments = $pendingAssignments ?? 0;
$notifications      = $notifications      ?? [];

$enrolledCourses   = $enrolledCourses   ?? [];
$upcomingDeadlines = $upcomingDeadlines ?? [];
$recentGrades      = $recentGrades      ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= esc($title ?? 'Dashboard') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    /* Base */
    body {
      background-color: #eef2f7;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
    }

    /* Header */
    header {
      background: linear-gradient(90deg, #667eea, #764ba2);
      color: white;
      padding: 40px 0;
      text-align: center;
      margin-bottom: 30px;
    }
    header h1 { font-size: 2.2rem; letter-spacing: 1px; }

    /* Navigation */
    nav {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
    }
    nav a {
      text-decoration: none;
      color: #764ba2;
      font-weight: bold;
      padding: 8px 18px;
      border-radius: 20px;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: all .3s ease;
    }
    nav a:hover {
      background: #764ba2;
      color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Cards */
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      transition: transform .2s;
    }
    .card:hover { transform: translateY(-5px); }

    .stat-card small {
      font-size: .8rem;
      text-transform: uppercase;
      font-weight: bold;
      color: #764ba2;
    }
    .stat-card h4 { color: #333; }

    /* Notifications */
    .notification-item {
      border-left: 4px solid #764ba2;
      background: #f9f6ff;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      font-size: 0.9rem;
      color: #555;
    }
  </style>
</head>
<body>

<!-- Header -->
<header>
  <?php if($role === 'admin'): ?>
    <h1><i class="fas fa-shield-alt"></i> Admin Dashboard</h1>
    <p>Welcome back, <?= esc($username) ?>! Manage your system efficiently.</p>
  <?php elseif($role === 'teacher'): ?>
    <h1><i class="fas fa-chalkboard-teacher"></i> Teacher Dashboard</h1>
    <p>Welcome back, <?= esc($username) ?>! Ready to inspire minds today?</p>
  <?php else: ?>
    <h1><i class="fas fa-user-graduate"></i> Student Dashboard</h1>
    <p>Welcome back, <?= esc($username) ?>! Continue your learning journey.</p>
  <?php endif; ?>
</header>

<!-- Navigation -->
<nav>
  <a href="<?= base_url('dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

  <?php if($role === 'admin'): ?>
    <a href="#"><i class="fas fa-users"></i> Manage Users</a>
    <a href="#"><i class="fas fa-book"></i> Courses</a>
    <a href="#"><i class="fas fa-chart-bar"></i> Reports</a>

  <?php elseif($role === 'teacher'): ?>
    <a href="#"><i class="fas fa-book"></i> My Courses</a>
    <a href="#"><i class="fas fa-tasks"></i> Assignments</a>

  <?php else: ?>
    <a href="#"><i class="fas fa-book"></i> My Courses</a>
    <a href="#"><i class="fas fa-chart-bar"></i> Grades</a>
  <?php endif; ?>

  <a href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<!-- Main -->
<div class="container mb-5">
  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <!-- Admin content -->
  <?php if($role === 'admin'): ?>
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Total Users</small><h4><?= esc($totalUsers) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Admins</small><h4><?= esc($totalAdmins) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Teachers</small><h4><?= esc($totalTeachers) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Students</small><h4><?= esc($totalStudents) ?></h4></div>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header"><strong>Recent Users</strong></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
          <tbody>
            <?php if(!empty($recentUsers)): foreach($recentUsers as $u): ?>
              <tr>
                <td><?= esc($u['username']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= esc($u['role']) ?></td>
                <td><?= isset($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '-' ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="4" class="text-center">No users found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  <!-- Teacher content -->
  <?php elseif($role === 'teacher'): ?>
    <div class="row mb-4">
      <div class="col-md-3"><div class="card shadow p-3"><small>My Courses</small><h4><?= esc($totalCourses) ?></h4></div></div>
      <div class="col-md-3"><div class="card shadow p-3"><small>Students</small><h4><?= esc($totalStudents) ?></h4></div></div>
      <div class="col-md-3"><div class="card shadow p-3"><small>Pending Reviews</small><h4><?= esc($pendingAssignments) ?></h4></div></div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header"><strong>Notifications</strong></div>
      <div class="card-body">
        <?php if(!empty($notifications)): foreach($notifications as $n): ?>
          <div class="notification-item"><?= esc($n) ?></div>
        <?php endforeach; else: ?>
          <p class="text-muted">No notifications</p>
        <?php endif; ?>
      </div>
    </div>

  <!-- Student content -->
  <?php else: ?>
    <div class="row mb-4">
      <div class="col-md-3"><div class="card shadow p-3"><small>Enrolled Courses</small><h4><?= count($enrolledCourses) ?></h4></div></div>
      <div class="col-md-3"><div class="card shadow p-3"><small>Deadlines</small><h4><?= count($upcomingDeadlines) ?></h4></div></div>
      <div class="col-md-3"><div class="card shadow p-3"><small>Grades</small><h4><?= count($recentGrades) ?></h4></div></div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header"><strong>My Courses</strong></div>
      <div class="card-body">
        <?php if(!empty($enrolledCourses)): ?>
          <ul class="list-group list-group-flush">
            <?php foreach($enrolledCourses as $c): ?>
              <li class="list-group-item"><?= esc($c['name'] ?? $c['course_name'] ?? 'Course') ?> — Progress: <?= esc($c['progress'] ?? 'N/A') ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-muted">No enrolled courses yet.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- System Info -->
  <div class="card shadow">
    <div class="card-body">
      <h6>System Information</h6>
      <p class="mb-0"><strong>Note:</strong> This is a demo dashboard — extend with your models and data.</p>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  &copy; 2025 Your ITE311-LU. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
