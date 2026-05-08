<?php
require __DIR__ . '/partials/app_shell_top.php';

$success = flash_get('success');
$error = flash_get('error');
$no = 1;

$rows = $pdo->query('SELECT id_barang, nama_barang, kode_aset, kondisi, stok, tgl_update FROM tbl_inventory ORDER BY id_barang DESC')->fetchAll();

function badge_class(string $kondisi): string
{
    return match ($kondisi) {
        'Baik' => 'badge--green',
        'Perbaikan' => 'badge--yellow',
        'Rusak' => 'badge--pink',
        default => 'badge--muted',
    };
}
?>

<?php if ($success): ?>
    <div class="alert alert--success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert--error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
<?php endif; ?>

<section class="panel">
    <div class="panel__header panel__header--row">
        <div>
            <h2 class="panel__title">Tabel Inventaris</h2>
            <div class="panel__subtitle">Data diambil dari database (tbl_inventory)</div>
        </div>
        <a class="btn btn--cyan" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'create']), ENT_QUOTES); ?>">+ Tambah</a>
    </div>

    <div class="tableWrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Kode Aset</th>
                    <th>Kondisi</th>
                    <th>Stok</th>
                    <th>Update</th>
                    <th class="table__actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="7" class="table__empty">Belum ada data inventaris.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars((string) $r['nama_barang'], ENT_QUOTES); ?></td>
                            <td class="mono"><?php echo htmlspecialchars((string) $r['kode_aset'], ENT_QUOTES); ?></td>
                            <td>
                                <span class="badge <?php echo badge_class((string) $r['kondisi']); ?>">
                                    <?php echo htmlspecialchars((string) $r['kondisi'], ENT_QUOTES); ?>
                                </span>
                            </td>
                            <td><?php echo (int) $r['stok']; ?></td>
                            <td class="muted"><?php echo htmlspecialchars((string) $r['tgl_update'], ENT_QUOTES); ?></td>
                            <td class="table__actions">
                                <a class="btn btn--yellow btn--sm" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'edit', 'id' => (string) $r['id_barang']]), ENT_QUOTES); ?>">Edit</a>

                                <form class="inline" method="post" action="<?php echo htmlspecialchars(url_for('modules/inventory/delete.php'), ENT_QUOTES); ?>" data-confirm="Hapus barang ini?">
                                    <input type="hidden" name="id_barang" value="<?php echo (int) $r['id_barang']; ?>" />
                                    <button class="btn btn--pink btn--sm" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/partials/app_shell_bottom.php'; ?>