<?php
$active = (string) ($page ?? 'dashboard');
$username = (string) ($_SESSION['username'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lab Sentry</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="app-body">
    <div class="app">
        <aside class="sidebar">
            <div class="sidebar__brand">Lab Sentry</div>

            <nav class="sidebar__nav">
                <a class="sidebar__link <?php echo $active === 'dashboard' ? 'is-active' : ''; ?>" href="index.php?page=dashboard">Dashboard</a>
                <a class="sidebar__link <?php echo $active === 'inventory' || $active === 'inventory_form' ? 'is-active' : ''; ?>" href="index.php?page=inventory">Inventory</a>
                <a class="sidebar__link <?php echo $active === 'loan' ? 'is-active' : ''; ?>" href="index.php?page=loan">Loan</a>
                <a class="sidebar__link <?php echo $active === 'report' ? 'is-active' : ''; ?>" href="index.php?page=report">Report</a>
            </nav>

            <div class="sidebar__footer">
                <div class="sidebar__user">
                    <div class="sidebar__userLabel">Logged in as</div>
                    <div class="sidebar__userName"><?php echo htmlspecialchars($username, ENT_QUOTES); ?></div>
                </div>
                <a class="btn btn--pink btn--block" href="modules/auth/logout.php">Logout</a>
            </div>
        </aside>

        <div class="main">
            <header class="topbar">
                <div class="topbar__title"><?php echo htmlspecialchars(ucfirst($active), ENT_QUOTES); ?></div>
            </header>

            <main class="content">
