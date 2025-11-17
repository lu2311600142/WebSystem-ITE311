<?php
$role       = $userRole ?? session('role')      ?? 'guest';
$username   = $username ?? session('username')  ?? 'Guest';
$isLoggedIn = session('isLoggedIn') ?? false;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark
    <?php if ($role === 'admin'): ?>bg-danger
    <?php elseif ($role === 'teacher'): ?>bg-warning
    <?php else: ?>bg-primary
    <?php endif; ?>">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-clipboard-list"></i> LMS
     

        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notifDropdown" style="min-width: 320px; max-width: 360px;">
                            <li class="px-2 py-1 text-muted small">Notifications</li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <div id="notif-list" class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;"></div>
                            </li>
                            <li class="mt-2 text-center"><a href="#" class="text-decoration-none small" id="notif-refresh">Refresh</a></li>
                        </ul>
                    </li>
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-users"></i> Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> Courses</a></li>
                    <?php elseif ($role === 'teacher'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> My Classes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-tasks"></i> Assignments</a></li>
                    <?php else: ?> 
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('courses') ?>"><i class="fas fa-book"></i> My Courses</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Grades</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- jQuery and Bootstrap JS for dropdowns -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function($){
        function renderNotifications(items){
            const $list = $('#notif-list');
            $list.empty();
            if (!items || items.length === 0) {
                $list.append('<div class="alert alert-secondary mb-0" role="alert">No notifications</div>');
                return;
            }
            items.forEach(function(n){
                const isRead = Number(n.is_read) === 1;
                const alertClass = isRead ? 'alert-secondary' : 'alert-info';
                const btn = isRead ? '' : '<button class="btn btn-sm btn-outline-primary ms-auto notif-mark" data-id="'+ n.id +'">Mark as Read</button>';
                const row = [
                    '<div class="alert '+alertClass+' d-flex align-items-start mb-0" role="alert">',
                        '<div class="me-2"><i class="fas fa-info-circle"></i></div>',
                        '<div class="flex-fill small">'+ $('<div>').text(n.message).html() +'<div class="text-muted">'+ (n.created_at || '') +'</div></div>',
                        btn,
                    '</div>'
                ].join('');
                $list.append(row);
            });
        }

        function updateBadge(count){
            const $badge = $('#notif-badge');
            if (count > 0) { $badge.text(count).show(); } else { $badge.hide(); }
        }

        function fetchNotifications(){
            $.get('<?= base_url('notifications') ?>')
                .done(function(res){
                    if (res && res.status === 'ok'){
                        updateBadge(res.unread || 0);
                        renderNotifications(res.notifications || []);
                    }
                });
        }

        $(document).on('click', '#notif-refresh', function(e){ e.preventDefault(); fetchNotifications(); });
        $(document).on('click', '.notif-mark', function(){
            const id = $(this).data('id');
            $.post('<?= base_url('notifications/mark_read') ?>/' + id)
                .done(function(res){
                    fetchNotifications();
                });
        });

        $(function(){
            <?php if ($isLoggedIn): ?>
            fetchNotifications();
            setInterval(fetchNotifications, 60000); // optional polling every 60s
            <?php endif; ?>
        });
    })(jQuery);
</script>