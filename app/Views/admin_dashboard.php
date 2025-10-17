<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, Admin!</h2>
        <p>You have successfully logged in as an administrator.</p>
        <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
        <a href="<?= base_url('announcements') ?>" class="btn btn-primary">View Announcements</a>
    </div>
</body>
</html>