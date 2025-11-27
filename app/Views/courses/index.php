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
    'role' => $userRole,
    'unreadCount' => $unreadCount ?? 0
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

    <!-- LAB 9: Search Interface -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form id="searchForm" class="d-flex">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search courses..." name="search_term">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($courses)): ?>
        <div id="coursesContainer" class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm course-card">
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
        <div id="coursesContainer" class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No courses available.
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Client-side filtering
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.course-card').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Server-side search with AJAX
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var searchTerm = $('#searchInput').val();

        $.get('<?= base_url('courses/search') ?>', {search_term: searchTerm}, function(data) {
            $('#coursesContainer').empty();

            if (data.length > 0) {
                $.each(data, function(index, course) {
                    var courseId = course.id || course['id'];
                    var courseTitle = course.title || course['title'] || 'Untitled';
                    var courseDescription = course.description || course['description'] || '';
                    var userRole = '<?= $userRole ?>';
                    var uploadBase = (userRole === 'admin') ? 'admin' : 'teacher';
                    
                    var courseHtml = `
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm course-card">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-1">${courseTitle}</h5>
                                    <p class="text-muted flex-grow-1">${courseDescription}</p>
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('materials/view/') ?>${courseId}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-folder-open"></i> View Materials
                                        </a>`;
                    
                    if (userRole === 'admin' || userRole === 'teacher') {
                        courseHtml += `
                                        <a href="<?= base_url() ?>${uploadBase}/course/${courseId}/upload" class="btn btn-success btn-sm">
                                            <i class="fas fa-upload"></i> Upload Material
                                        </a>`;
                    }
                    
                    courseHtml += `
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    $('#coursesContainer').append(courseHtml);
                });
            } else {
                $('#coursesContainer').html('<div class="col-12"><div class="alert alert-info">No courses found matching your search.</div></div>');
            }
        }).fail(function() {
            $('#coursesContainer').html('<div class="col-12"><div class="alert alert-danger">An error occurred while searching. Please try again.</div></div>');
        });
    });
});
</script>
</body>
</html>
