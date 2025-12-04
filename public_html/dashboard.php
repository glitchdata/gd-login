<?php
session_start();
require __DIR__ . '/includes/users.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['flash'] = 'Please log in to continue.';
    header('Location: index.php');
    exit;
}

$currentUser = $_SESSION['user'];
$latest = findUserByEmail($currentUser['email']) ?? $currentUser;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · GD Login</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="dashboard-body">
    <div class="dashboard">
        <header>
            <div>
                <p class="eyebrow">Session active</p>
                <h1>Welcome, <?= htmlspecialchars($latest['name'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
                <p class="lead">You are signed in with PHP sessions. Manage your account details below.</p>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" class="ghost">Log out</button>
            </form>
        </header>

        <section class="card">
            <h2>Account details</h2>
            <dl class="details">
                <div>
                    <dt>Name</dt>
                    <dd><?= htmlspecialchars($latest['name'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd><?= htmlspecialchars($latest['email'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div>
                    <dt>Member since</dt>
                    <dd><?= htmlspecialchars(date('F j, Y', strtotime($latest['createdAt'] ?? 'now')), ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
            </dl>
        </section>
    </div>
</body>
</html>
