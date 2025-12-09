<?php
$role       = $userRole ?? session('role')      ?? 'guest';
$username   = $username ?? session('username')  ?? 'Guest';
$isLoggedIn = session('isLoggedIn') ?? false;
$unreadCount = $unreadCount ?? 0;
$notifications = $notifications ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Notification Badge Animation */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .notification-badge-pulse {
            animation: pulse 2s infinite;
        }
        .notification-item {
            transition: all 0.3s ease;
            border-left: 3px solid #0d6efd;
        }
        .notification-item:hover {
            background-color: #f8f9fa !important;
        }
        /* Ensure notification bell is visible and clickable */
        #notifDropdown {
            cursor: pointer !important;
            color: rgba(255, 255, 255, 0.9) !important;
            pointer-events: auto !important;
            z-index: 10;
        }
        #notifDropdown:hover {
            color: rgba(255, 255, 255, 1) !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        /* Ensure dropdown menu is visible */
        #notificationDropdown {
            z-index: 1050 !important;
            display: none;
        }
        #notificationDropdown.show {
            display: block !important;
        }
        /* Loading state for notifications */
        #notif-list.loading {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
    </style>
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
                    <!-- 🔔 LAB 8: NOTIFICATION BELL ICON -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative text-white" href="javascript:void(0);" id="notifDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; pointer-events: auto;">
                            <i class="fas fa-bell fs-5"></i>
                            <span id="notif-badge" 
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= $unreadCount > 0 ? 'notification-badge-pulse' : '' ?>" 
                                  style="<?= $unreadCount > 0 ? '' : 'display:none;' ?>">
                                <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2 shadow" id="notificationDropdown" aria-labelledby="notifDropdown" style="min-width: 320px; max-width: 360px; z-index: 1050;">
                            <li class="px-2 py-1 d-flex justify-content-between align-items-center border-bottom">
                                <span class="text-muted small fw-bold">Notifications</span>
                                <button class="btn btn-sm btn-link text-decoration-none p-0" id="notif-refresh" title="Refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </li>
                            <li>
                                <div id="notif-list" class="d-flex flex-column gap-2 mt-2" style="max-height: 300px; overflow-y: auto; min-height: 50px;">
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-spinner fa-spin"></i> Loading...
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('users') ?>"><i class="fas fa-users"></i> Users</a></li>
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

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($isLoggedIn): ?>
<!--  LAB 8: NOTIFICATION JAVASCRIPT -->
<script>
(function($) {
    'use strict';

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function timeSince(dateString) {
        const date = new Date(dateString);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        if (seconds < 60) return 'just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' min ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' hr ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' day' + (Math.floor(seconds / 86400) > 1 ? 's' : '') + ' ago';
        
        return new Date(dateString).toLocaleDateString();
    }

    function renderNotifications(items) {
        const $list = $('#notif-list');
        $list.empty();
        
        if (!items || items.length === 0) {
            $list.append(`
                <div class="alert alert-secondary mb-0 text-center py-3" role="alert">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i><br>
                    <small>No notifications</small>
                </div>
            `);
            return;
        }

        items.forEach(function(n) {
            const isRead = Number(n.is_read) === 1;
            const alertClass = isRead ? 'alert-light' : 'alert-info';
            const btn = isRead ? '' : `<button class="btn btn-sm btn-outline-primary notif-mark" data-id="${n.id}">Mark as Read</button>`;
            
            const row = `
                <div class="notification-item alert ${alertClass} mb-2 p-2 rounded" role="alert" data-id="${n.id}">
                    <div class="d-flex align-items-start">
                        <div class="me-2 mt-1">
                            <i class="fas ${isRead ? 'fa-envelope-open text-muted' : 'fa-envelope text-primary'}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 small fw-normal">${escapeHtml(n.message)}</p>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">${timeSince(n.created_at)}</small>
                                ${btn}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $list.append(row);
        });
    }

    function updateBadge(count) {
        const $badge = $('#notif-badge');
        if (count > 0) {
            $badge.text(count > 9 ? '9+' : count).addClass('notification-badge-pulse').show();
        } else {
            $badge.removeClass('notification-badge-pulse').hide();
        }
        
        // Update page title
        const baseTitle = document.title.replace(/^\(\d+\)\s*/, '');
        document.title = count > 0 ? `(${count}) ${baseTitle}` : baseTitle;
    }

    function fetchNotifications() {
        $.get('<?= base_url('notifications') ?>')
            .done(function(res) {
                if (res && res.status === 'ok') {
                    updateBadge(res.unread || 0);
                    renderNotifications(res.notifications || []);
                }
            })
            .fail(function(xhr) {
                console.error('Failed to fetch notifications', xhr);
            });
    }

    // Initialize notifications and dropdown
    $(document).ready(function() {
        var dropdownOpen = false;
        
        // Manual dropdown toggle handler
        $('#notifDropdown').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $dropdown = $('#notificationDropdown');
            
            if (dropdownOpen) {
                // Close dropdown
                $dropdown.removeClass('show').hide();
                dropdownOpen = false;
                $(this).attr('aria-expanded', 'false');
            } else {
                // Open dropdown
                $dropdown.addClass('show').show();
                dropdownOpen = true;
                $(this).attr('aria-expanded', 'true');
                
                // Fetch notifications when opened
                fetchNotifications();
            }
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#notifDropdown, #notificationDropdown').length) {
                $('#notificationDropdown').removeClass('show').hide();
                dropdownOpen = false;
                $('#notifDropdown').attr('aria-expanded', 'false');
            }
        });
        
        // Also listen for Bootstrap dropdown events (fallback)
        $('#notifDropdown').on('shown.bs.dropdown', function() {
            fetchNotifications();
            dropdownOpen = true;
        });
        
        $('#notifDropdown').on('hidden.bs.dropdown', function() {
            dropdownOpen = false;
        });
        
        // Initial load
        fetchNotifications();
        
        // Auto-refresh every 60 seconds
        setInterval(fetchNotifications, 60000);
    });

    // Refresh button
    $(document).on('click', '#notif-refresh', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).find('i').addClass('fa-spin');
        fetchNotifications();
        setTimeout(() => $(this).find('i').removeClass('fa-spin'), 1000);
    });

    // Mark as read
    $(document).on('click', '.notif-mark', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const id = $(this).data('id');
        if (!id) return;

        const $btn = $(this);
        $btn.prop('disabled', true).text('Marking...');

        $.post('<?= base_url('notifications/mark_read/') ?>' + id, {
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }).done(function() {
            fetchNotifications();
        }).fail(function() {
            $btn.prop('disabled', false).text('Mark as Read');
            alert('Failed to mark notification as read. Please try again.');
        });
    });

    console.log(' LAB 8: Notification system loaded');
})(jQuery);
</script>
<?php endif; ?>