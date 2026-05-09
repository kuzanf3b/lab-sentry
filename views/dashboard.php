<?php
require __DIR__ . '/partials/app_shell_top.php';

$success = flash_get('success');
$error = flash_get('error');

$username = (string) ($_SESSION['username'] ?? 'User');

$statsStmt = $pdo->query(
    "SELECT\n        COUNT(*) AS total,\n        SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) AS baik,\n        SUM(CASE WHEN kondisi = 'Perbaikan' THEN 1 ELSE 0 END) AS perbaikan,\n        SUM(CASE WHEN kondisi = 'Rusak' THEN 1 ELSE 0 END) AS rusak\n     FROM tbl_inventory"
);
$stats = $statsStmt->fetch() ?: ['total' => 0, 'baik' => 0, 'perbaikan' => 0, 'rusak' => 0];

$totalStockStmt = $pdo->query("SELECT COALESCE(SUM(stok), 0) AS total_stok FROM tbl_inventory");
$totalStockRow = $totalStockStmt->fetch() ?: ['total_stok' => 0];
$totalStock = (int) ($totalStockRow['total_stok'] ?? 0);

$recentStmt = $pdo->query(
    "SELECT id_barang, nama_barang, kondisi, stok\n     FROM tbl_inventory\n     ORDER BY tgl_update DESC\n     LIMIT 3"
);
$recentRows = $recentStmt->fetchAll();

$trendStmt = $pdo->query(
    "SELECT DATE(tgl_update) AS d, SUM(stok) AS total_stok\n     FROM tbl_inventory\n     GROUP BY DATE(tgl_update)\n     ORDER BY d DESC\n     LIMIT 7"
);
$trendRows = $trendStmt->fetchAll();
$trendRows = array_reverse($trendRows);

$labels = array_map(static fn($r) => (string) $r['d'], $trendRows);
$values = array_map(static fn($r) => (int) $r['total_stok'], $trendRows);

$pieLabels = ['Baik', 'Perbaikan', 'Rusak'];
$pieValues = [(int) $stats['baik'], (int) $stats['perbaikan'], (int) $stats['rusak']];
?>

<?php if ($success): ?>
    <div class="alert alert--success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert--error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
<?php endif; ?>


<section class="dashTop">
    <div class="dashHello">
        <div class="dashHello__text">
            <div class="dashHello__title">Hi, <?php echo htmlspecialchars($username, ENT_QUOTES); ?></div>
            <div class="dashHello__subtitle">Selamat datang kembali. Pantau stok dan kondisi inventaris lab di sini.</div>
        </div>
        <div class="dashHello__art" aria-hidden="true">
            <img class="dashHello__artImg" src="<?php echo htmlspecialchars(url_for('assets/img/batman-dashboard.png'), ENT_QUOTES); ?>" alt="" />
        </div>
    </div>

    <div class="dashTotal">
        <div class="dashTotal__head">
            <div class="dashTotal__label">Total Stock</div>
            <div class="dashTotal__icon" aria-hidden="true">
                <img class="dashTotal__iconImg" src="<?php echo htmlspecialchars(url_for('assets/img/total-stok-icon.png'), ENT_QUOTES); ?>" alt="" />
            </div>
        </div>
        <div class="dashTotal__value"><?php echo $totalStock; ?></div>
        <div class="dashTotal__meta">Items: <?php echo (int) $stats['total']; ?></div>
    </div>
</section>

<section class="panel dashPanel">
    <div class="panel__header">
        <h2 class="panel__title">Inventory Trend</h2>
        <div class="panel__subtitle">Total stok per tanggal update (7 hari terakhir)</div>
    </div>

    <div class="chartWrap dashChartWrap">
        <canvas
            id="inventoryChart"
            data-labels='<?php echo htmlspecialchars(json_encode($labels, JSON_UNESCAPED_SLASHES), ENT_QUOTES); ?>'
            data-values='<?php echo htmlspecialchars(json_encode($values, JSON_UNESCAPED_SLASHES), ENT_QUOTES); ?>'></canvas>
    </div>
</section>

<section class="dashBottom">
    <section class="panel dashPanel">
        <div class="panel__header">
            <h2 class="panel__title">Kondisi</h2>
            <div class="panel__subtitle">Ringkasan kondisi barang</div>
        </div>
        <div class="chartWrap dashChartWrap dashChartWrap--sm">
            <canvas
                id="conditionPie"
                data-labels='<?php echo htmlspecialchars(json_encode($pieLabels, JSON_UNESCAPED_SLASHES), ENT_QUOTES); ?>'
                data-values='<?php echo htmlspecialchars(json_encode($pieValues, JSON_UNESCAPED_SLASHES), ENT_QUOTES); ?>'></canvas>
        </div>
    </section>

    <section class="panel dashPanel">
        <div class="panel__header panel__header--row">
            <div>
                <h2 class="panel__title">New Stock</h2>
                <div class="panel__subtitle">3 update terbaru</div>
            </div>
            <a class="btn btn--cyan btn--sm" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory']), ENT_QUOTES); ?>">Lihat Semua</a>
        </div>

        <div class="tableWrap dashTable">
            <table class="table" style="min-width:560px">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kondisi</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$recentRows): ?>
                        <tr>
                            <td colspan="4" class="table__empty">Belum ada data.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentRows as $r): ?>
                            <tr>
                                <td><?php echo (int) $r['id_barang']; ?></td>
                                <td><?php echo htmlspecialchars((string) $r['nama_barang'], ENT_QUOTES); ?></td>
                                <td>
                                    <span class="badge <?php echo badge_class((string) $r['kondisi']); ?>">
                                        <?php echo htmlspecialchars((string) $r['kondisi'], ENT_QUOTES); ?>
                                    </span>
                                </td>
                                <td><?php echo (int) $r['stok']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>

<script src="<?php echo htmlspecialchars(url_for('assets/vendor/chartjs/chart.umd.min.js'), ENT_QUOTES); ?>"></script>
<script src="<?php echo htmlspecialchars(url_for('assets/js/dashboard.js'), ENT_QUOTES); ?>"></script>

<?php
require __DIR__ . '/partials/app_shell_bottom.php';
?>