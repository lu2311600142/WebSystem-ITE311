<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-left: 4px solid #dc3545;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .recent-activity {
            max-height: 400px;
            overflow-y: auto;
        }
        .navbar-admin {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-shield-alt"></i> Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-users"></i> Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-book"></i> Manage Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?= esc($username) ?>
                        </a>
                        <ul class="dropdown-menu">
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
            <h1><i class="fas fa-shield-alt"></i> Admin Dashboard</h1>
            <p class="lead">Welcome back, <?= esc($username) ?>! Manage your system efficiently.</p>
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
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalUsers ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-danger"></i>
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
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Admins</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAdmins ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shield-alt fa-2x text-danger"></i>
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
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Teachers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTeachers ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
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
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalStudents ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Recent Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($recentUsers)): ?>
                                        <?php foreach($recentUsers as $user): ?>
                                            <tr>
                                                <td><?= esc($user['username']) ?></td>
                                                <td><?= esc($user['email']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'warning' : 'info') ?>">
                                                        <?= ucfirst(esc($user['role'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No users found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-plus"></i> Add New User
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-book-plus"></i> Create Course
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-line"></i> View Reports
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-cog"></i> System Settings
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