<!DOCTYPE html>
<html>
<head>
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Announcements</h2>
        <?php if (empty($announcements)): ?>
            <p>No announcements available.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($announcements as $announcement): ?>
                    <li class="list-group-item">
                        <h5><?= esc($announcement['title']) ?></h5>
                        <p><?= esc($announcement['content']) ?></p>
                        <small class="text-muted">Posted on: <?= $announcement['created_at'] ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>