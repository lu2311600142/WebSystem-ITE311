<?php
// Variables for header
$title = 'Courses';
$userRole = session('role') ?? 'guest';
$username = session('username') ?? 'Guest';
$isLoggedIn = session('isLoggedIn') ?? false;

echo view('templates/header', [
    'title' => $title,
    'userRole' => $userRole,
    'username' => $username,
    'isLoggedIn' => $isLoggedIn,
    'role' => $userRole
]);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fas fa-book"></i> All Courses</h3>
        <?php if ($userRole === 'admin' || $userRole === 'teacher'): ?>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($courses)): ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1"><?= esc($course['title'] ?? $course->title ?? 'Untitled') ?></h5>
                            <p class="text-muted flex-grow-1"><?= esc($course['description'] ?? $course->description ?? '') ?></p>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('materials/view/' . ($course['id'] ?? $course->id)) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-folder-open"></i> View Materials
                                </a>
                                <?php if ($userRole === 'admin' || $userRole === 'teacher'): ?>
                                    <?php $uploadBase = $userRole === 'admin' ? 'admin' : 'teacher'; ?>
                                    <a href="<?= base_url($uploadBase . '/course/' . ($course['id'] ?? $course->id) . '/upload') ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-upload"></i> Upload Material
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No courses available.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
