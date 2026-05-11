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
    <link rel="stylesheet" href="<?php echo htmlspecialchars(url_for('assets/css/style.css'), ENT_QUOTES); ?>" />
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars(url_for('assets/img/batman-logo.png'), ENT_QUOTES); ?>">
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            corePlugins: {
                preflight: false
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="app-body">
    <div class="app">
        <aside class="sidebar">
            <div class="sidebar__brand">Lab Sentry</div>

            <nav class="sidebar__nav">
                <a class="sidebar__link <?php echo $active === 'dashboard' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'dashboard']), ENT_QUOTES); ?>">
                    <span class="sidebar__icon" aria-hidden="true">
                        <img class="sidebar__iconImg" src="<?php echo htmlspecialchars(url_for('assets/img/dashboard-icon.png'), ENT_QUOTES); ?>" alt="" />
                    </span>
                    <span class="sidebar__label">Dashboard</span>
                </a>

                <a class="sidebar__link <?php echo $active === 'inventory' || $active === 'inventory_form' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory']), ENT_QUOTES); ?>">
                    <span class="sidebar__icon" aria-hidden="true">
                        <img class="sidebar__iconImg" src="<?php echo htmlspecialchars(url_for('assets/img/inventory-icon.png'), ENT_QUOTES); ?>" alt="" />
                    </span>
                    <span class="sidebar__label">Inventory</span>
                </a>

                <a class="sidebar__link <?php echo $active === 'loan' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'loan']), ENT_QUOTES); ?>">
                    <span class="sidebar__icon" aria-hidden="true">
                        <img class="sidebar__iconImg" src="<?php echo htmlspecialchars(url_for('assets/img/loan-icon.png'), ENT_QUOTES); ?>" alt="" />
                    </span>
                    <span class="sidebar__label">Loan</span>
                </a>

                <a class="sidebar__link <?php echo $active === 'report' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'report']), ENT_QUOTES); ?>">
                    <span class="sidebar__icon" aria-hidden="true">
                        <img class="sidebar__iconImg" src="<?php echo htmlspecialchars(url_for('assets/img/report-icon.png'), ENT_QUOTES); ?>" alt="" />
                    </span>
                    <span class="sidebar__label">Report</span>
                </a>
            </nav>

            <div class="sidebar__footer">
                <a class="btn btn--pink btn--block sidebar__logoutBtn" href="<?php echo htmlspecialchars(url_for('modules/auth/logout.php'), ENT_QUOTES); ?>">
                    <span aria-hidden="true">
                        <img class="btn__iconImg" src="<?php echo htmlspecialchars(url_for('assets/img/logout-icon.png'), ENT_QUOTES); ?>" alt="" />
                    </span>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <header class="topbar">
                <div class="topbar__title"><?php echo htmlspecialchars(ucfirst($active), ENT_QUOTES); ?></div>
                <div class="topbar__actions">
                    <div class="avatar" aria-hidden="true">
                        <img class="avatar__img" src="<?php echo htmlspecialchars(url_for('assets/img/batman-avatar.jpg'), ENT_QUOTES); ?>" alt="" />
                    </div>
                </div>
            </header>

            <main class="content">