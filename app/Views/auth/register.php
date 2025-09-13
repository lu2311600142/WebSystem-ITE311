<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">Register</div>
        <div class="card-body">

            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('register') ?>">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= old('name') ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password (Password must be at least 6 characters long)</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Confirm Password (Password must be at least 6 characters long)</label>
                    <input type="password" name="password_confirm" class="form-control" required>
                </div>

                <button type="submit" name="reg" class="btn btn-success">Register</button>
                <a href="<?= base_url('login') ?>" class="btn btn-link">Login</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
