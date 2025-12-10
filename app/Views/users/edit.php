<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Edit User' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-user-edit"></i> Edit User</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('users') ?>"><i class="fas fa-arrow-left"></i> Back to Users</a>
                <a class="nav-link" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user-edit"></i> Edit User</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('errors')): ?>
                            <?php $flashErrors = session()->getFlashdata('errors'); ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php if (is_array($flashErrors)): ?>
                                    <ul class="mb-0">
                                        <?php foreach ($flashErrors as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <?= esc($flashErrors) ?>
                                <?php endif; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($errors) && is_array($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $field => $error): ?>
                                        <li><?= esc(is_array($error) ? implode(', ', $error) : $error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= base_url('users/update/' . $user['id']) ?>">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= old('name', $user['name'] ?? $user['username'] ?? '') ?>" 
                                       required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?= old('email', $user['email']) ?>" 
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" 
                                           id="current_password" 
                                           name="current_password"
                                           placeholder="Enter current password to verify"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword" title="Show/Hide Current Password">
                                        <i class="fas fa-eye" id="toggleCurrentPasswordIcon"></i>
                                    </button>
                                </div>
                                <?php if (isset($errors['current_password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['current_password'] ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Enter the current password to verify your identity before making changes.</small>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           id="password" 
                                           name="password"
                                           placeholder="Enter new password or leave blank to keep current">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Show/Hide New Password">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Leave blank to keep current password. Minimum 6 characters if changing.</small>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Select Role</option>
                                    <option value="admin" <?= (old('role', $user['role']) === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    <option value="teacher" <?= (old('role', $user['role']) === 'teacher') ? 'selected' : '' ?>>Teacher</option>
                                    <option value="student" <?= (old('role', $user['role']) === 'student') ? 'selected' : '' ?>>Student</option>
                                </select>
                                <?php if (isset($errors['role'])): ?>
                                    <div class="invalid-feedback"><?= $errors['role'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility functions
        function setupPasswordToggle(toggleId, inputId, iconId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (toggle && input && icon) {
                toggle.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    // Toggle icon
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
        }

        // Initialize password toggles
        document.addEventListener('DOMContentLoaded', function() {
            setupPasswordToggle('toggleCurrentPassword', 'current_password', 'toggleCurrentPasswordIcon');
            setupPasswordToggle('togglePassword', 'password', 'togglePasswordIcon');
        });
    </script>
</body>
</html>

