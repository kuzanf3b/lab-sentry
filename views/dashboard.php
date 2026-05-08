<?php
require __DIR__ . '/partials/app_shell_top.php';

$success = flash_get('success');
$error = flash_get('error');

$statsStmt = $pdo->query(
    "SELECT\n        COUNT(*) AS total,\n        SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) AS baik,\n        SUM(CASE WHEN kondisi = 'Perbaikan' THEN 1 ELSE 0 END) AS perbaikan,\n        SUM(CASE WHEN kondisi = 'Rusak' THEN 1 ELSE 0 END) AS rusak\n     FROM tbl_inventory"
);
$stats = $statsStmt->fetch() ?: ['total' => 0, 'baik' => 0, 'perbaikan' => 0, 'rusak' => 0];

$trendStmt = $pdo->query(
    "SELECT DATE(tgl_update) AS d, SUM(stok) AS total_stok\n     FROM tbl_inventory\n     GROUP BY DATE(tgl_update)\n     ORDER BY d DESC\n     LIMIT 7"
);
$trendRows = $trendStmt->fetchAll();
$trendRows = array_reverse($trendRows);

$labels = array_map(static fn($r) => (string) $r['d'], $trendRows);
$values = array_map(static fn($r) => (int) $r['total_stok'], $trendRows);
?>

<?php if ($success): ?>
    <div class="alert alert--success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert--error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
<?php endif; ?>

<section class="statGrid">
    <div class="statCard">
        <div class="statCard__label">Total</div>
        <div class="statCard__value"><?php echo (int) $stats['total']; ?></div>
        <div class="statCard__accent statCard__accent--cyan"></div>
    </div>
    <div class="statCard">
        <div class="statCard__label">Baik</div>
        <div class="statCard__value"><?php echo (int) $stats['baik']; ?></div>
        <div class="statCard__accent statCard__accent--green"></div>
    </div>
    <div class="statCard">
        <div class="statCard__label">Perbaikan</div>
        <div class="statCard__value"><?php echo (int) $stats['perbaikan']; ?></div>
        <div class="statCard__accent statCard__accent--yellow"></div>
    </div>
    <div class="statCard">
        <div class="statCard__label">Rusak</div>
        <div class="statCard__value"><?php echo (int) $stats['rusak']; ?></div>
        <div class="statCard__accent statCard__accent--pink"></div>
    </div>
</section>

<section class="panel">
    <div class="panel__header">
        <h2 class="panel__title">Inventory Trend</h2>
        <div class="panel__subtitle">Total stok per tanggal update (7 hari terakhir)</div>
    </div>

    <div class="chartWrap">
        <canvas
            id="inventoryChart"
            data-labels='<?php echo htmlspecialchars(json_encode($labels, JSON_UNESCAPED_SLASHES), ENT_QUOTES); ?>'
            data-values='<?php echo htmlspecialchars(json_encode($values, JSON_UNESCAPED_SLASHES), ENT_QUOTES); ?>'></canvas>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="<?php echo htmlspecialchars(url_for('assets/js/dashboard.js'), ENT_QUOTES); ?>"></script>

<?php require __DIR__ . '/partials/app_shell_bottom.php'; ?>