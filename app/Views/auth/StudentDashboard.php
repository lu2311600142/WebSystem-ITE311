<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Student Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .navbar-student {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
        }
        .course-progress {
            height: 10px;
            border-radius: 5px;
        }
        .deadline-item {
            border-left: 3px solid #0d6efd;
            background: #cff4fc;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .grade-badge {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-student">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-user-graduate"></i> Student Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/student/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-book"></i> My Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-tasks"></i> Assignments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Grades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-calendar"></i> Schedule</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?= esc($username) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <h1><i class="fas fa-user-graduate"></i> Student Dashboard</h1>
            <p class="lead">Welcome back, <?= esc($username) ?>! Let's continue your learning journey.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Enrolled Courses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($enrolledCourses) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pending Tasks</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($upcomingDeadlines) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Average Grade</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">B+</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">85%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="row">
            <!-- Enrolled Courses -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">My Courses</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach($enrolledCourses as $course): ?>
                            <div class="mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><?= esc($course['name']) ?></h6>
                                    <span class="badge bg-primary"><?= esc($course['grade']) ?></span>
                                </div>
                                <div class="progress course-progress mb-2">
                                    <div class="progress-bar" role="progressbar" style="width: <?= esc($course['progress']) ?>" aria-valuenow="<?= intval($course['progress']) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">Progress: <?= esc($course['progress']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Upcoming Deadlines</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach($upcomingDeadlines as $deadline): ?>
                            <div class="deadline-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= esc($deadline['assignment']) ?></strong><br>
                                        <small class="text-muted"><?= esc($deadline['course']) ?></small>
                                    </div>
                                    <span class="badge bg-warning text-dark">
                                        <?= date('M d', strtotime($deadline['due'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Grades -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Grades</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Grade</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentGrades as $grade): ?>
                                        <tr>
                                            <td><?= esc($grade['assignment']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $grade['grade'][0] === 'A' ? 'success' : ($grade['grade'][0] === 'B' ? 'info' : 'warning') ?>">
                                                    <?= esc($grade['grade']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($grade['date'])) ?></td>
                                            <td><span class="badge bg-success">Graded</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-book-open"></i> View All Courses
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-upload"></i> Submit Assignment
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-calendar-check"></i> View Schedule
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-question-circle"></i> Help & Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>