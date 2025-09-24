<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Teacher Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #fd7e14, #e55a00);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-left: 4px solid #fd7e14;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .navbar-teacher {
            background: linear-gradient(90deg, #fd7e14, #e55a00);
        }
        .notification-item {
            border-left: 3px solid #fd7e14;
            background: #fff3cd;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-teacher">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-chalkboard-teacher"></i> Teacher Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/teacher/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-book"></i> My Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-plus-circle"></i> Create Course</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-tasks"></i> Assignments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Grades</a>
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
            <h1><i class="fas fa-chalkboard-teacher"></i> Teacher Dashboard</h1>
            <p class="lead">Welcome back, <?= esc($username) ?>! Ready to inspire minds today?</p>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">My Courses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalCourses ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-warning"></i>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalStudents ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-warning"></i>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Reviews</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pendingAssignments ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-warning"></i>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">This Week</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                <small>New submissions</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="row">
            <!-- My Courses -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-warning">My Courses</h6>
                        <button class="btn btn-warning btn-sm">
                            <i class="fas fa-plus"></i> Create New Course
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title">Chemistry Basics</h6>
                                        <p class="card-text text-muted">Basic Chemistry Concepts</p>
                                        <p class="mb-2"><small><i class="fas fa-users"></i> 18 Students</small></p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-warning btn-sm">View Course</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications & Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Notifications</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach($notifications as $notification): ?>
                            <div class="notification-item">
                                <i class="fas fa-bell text-warning"></i>
                                <?= esc($notification) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus-circle"></i> Create New Course
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-tasks"></i> Create Assignment
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-file-upload"></i> Upload Materials
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-line"></i> Grade Students
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>d-title">Mathematics 101</h6>
                                        <p class="card-text text-muted">Basic Mathematics</p>
                                        <p class="mb-2"><small><i class="fas fa-users"></i> 15 Students</small></p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-warning btn-sm">View Course</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title">Physics Fundamentals</h6>
                                        <p class="card-text text-muted">Introduction to Physics</p>
                                        <p class="mb-2"><small><i class="fas fa-users"></i> 12 Students</small></p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-warning btn-sm">View Course</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="car