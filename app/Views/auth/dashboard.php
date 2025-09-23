<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #eef2f7;
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

        header h1 {
            font-size: 2.5rem;
            letter-spacing: 1px;
        }

        /* Dashboard container */
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .welcome-card h2 {
            margin-bottom: 15px;
            font-size: 2rem;
        }

        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .user-info h3 {
            color: #764ba2;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #6c757d;
        }

        .info-value {
            color: #495057;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: capitalize;
        }

        .role-student {
            background-color: #d4edda;
            color: #155724;
        }

        .role-instructor {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .role-admin {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-logout {
            background: linear-gradient(90deg, #dc3545, #c82333);
            border: none;
            border-radius: 25px;
            padding: 12px 25px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-logout:hover {
            background: linear-gradient(90deg, #c82333, #bd2130);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }

        .actions-card {
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <h1>Dashboard</h1>
    </header>

    <div class="dashboard-container">
        <!-- Welcome Card -->
        <div class="dashboard-card welcome-card">
            <h2>Welcome Back!</h2>
            <p>Hello <strong><?= esc($username) ?></strong>, you're successfully logged in to your dashboard.</p>
        </div>

        <!-- User Information Card -->
        <div class="dashboard-card">
            <div class="user-info">
                <h3>Your Information</h3>
                
                <div class="info-item">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?= esc($username) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($email) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Role:</span>
                    <span class="info-value">
                        <span class="role-badge role-<?= esc($role) ?>"><?= esc($role) ?></span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Login Status:</span>
                    <span class="info-value">
                        <span class="badge bg-success">Active</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="dashboard-card actions-card">
            <h3>Quick Actions</h3>
            <p class="text-muted mb-4">Manage your account and access system features</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <button class="btn btn-outline-primary w-100" disabled>
                        Profile Settings
                    </button>
                </div>
                <div class="col-md-6 mb-3">
                    <button class="btn btn-outline-success w-100" disabled>
                        View Reports
                    </button>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="<?= base_url('logout') ?>" class="btn-logout">
                    Logout
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>