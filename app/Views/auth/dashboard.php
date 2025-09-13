<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">Dashboard</div>
        <div class="card-body">
            <h4>Welcome, <?= esc($user['name']) ?>!</h4>
            <p>Email: <?= esc($user['email']) ?></p>
            <p>Role: <?= esc($user['role']) ?></p>

            <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
