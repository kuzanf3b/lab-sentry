<?php
require __DIR__ . '/partials/app_shell_top.php';

$success = flash_get('success');
$error = flash_get('error');

$perPage = 10;
$currentPage = (int) ($_GET['p'] ?? 1);
if ($currentPage < 1) {
    $currentPage = 1;
}

$totalRows = (int) $pdo->query('SELECT COUNT(*) FROM tbl_inventory')->fetchColumn();
$totalPages = $totalRows === 0 ? 1 : (int) ceil($totalRows / $perPage);
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $perPage;
$no = $offset + 1;

$stmt = $pdo->prepare('SELECT id_barang, nama_barang, kode_aset, kondisi, stok, tgl_update FROM tbl_inventory ORDER BY id_barang DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$startItem = $totalRows === 0 ? 0 : $offset + 1;
$endItem = $totalRows === 0 ? 0 : min($offset + $perPage, $totalRows);
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
        <button class="btn btn--cyan" type="button" data-modal-open="addStockModal">+ Tambah</button>
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
                                <button
                                    class="btn btn--yellow btn--sm"
                                    type="button"
                                    data-modal-open="editStockModal"
                                    data-stock-id="<?php echo htmlspecialchars((string) $r['id_barang'], ENT_QUOTES); ?>"
                                    data-stock-nama="<?php echo htmlspecialchars((string) $r['nama_barang'], ENT_QUOTES); ?>"
                                    data-stock-kode="<?php echo htmlspecialchars((string) $r['kode_aset'], ENT_QUOTES); ?>"
                                    data-stock-stok="<?php echo htmlspecialchars((string) $r['stok'], ENT_QUOTES); ?>"
                                    data-stock-kondisi="<?php echo htmlspecialchars((string) $r['kondisi'], ENT_QUOTES); ?>">
                                    Edit
                                </button>

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

    <?php if ($totalRows > 0): ?>
        <div class="pager" aria-label="Pagination">
            <div class="pager__meta muted">
                Menampilkan <?php echo (int) $startItem; ?>–<?php echo (int) $endItem; ?> dari <?php echo (int) $totalRows; ?> data
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pager__controls">
                    <?php if ($currentPage > 1): ?>
                        <a class="btn btn--ghost btn--sm pager__link" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory', 'p' => $currentPage - 1]), ENT_QUOTES); ?>">‹ Prev</a>
                    <?php endif; ?>

                    <?php
                    $window = 2;
                    $pagesToShow = [1, $totalPages];
                    for ($i = max(1, $currentPage - $window); $i <= min($totalPages, $currentPage + $window); $i++) {
                        $pagesToShow[] = $i;
                    }
                    $pagesToShow = array_values(array_unique($pagesToShow));
                    sort($pagesToShow);

                    $lastPage = 0;
                    foreach ($pagesToShow as $p):
                        if ($p - $lastPage > 1): ?>
                            <span class="pager__ellipsis" aria-hidden="true">…</span>
                        <?php
                        endif;

                        $isActive = $p === $currentPage;
                        ?>
                        <a
                            class="btn btn--ghost btn--sm pager__link <?php echo $isActive ? 'is-active' : ''; ?>"
                            href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory', 'p' => $p]), ENT_QUOTES); ?>"
                            <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                            <?php echo (int) $p; ?>
                        </a>
                    <?php
                        $lastPage = $p;
                    endforeach;
                    ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a class="btn btn--ghost btn--sm pager__link" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'inventory', 'p' => $currentPage + 1]), ENT_QUOTES); ?>">Next ›</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<div class="modal" id="addStockModal" aria-hidden="true">
    <div class="modal__backdrop" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="addStockTitle">
        <div class="modal__header">
            <div class="modal__title" id="addStockTitle">Tambah Stok</div>
            <button class="modal__close" type="button" data-modal-close aria-label="Tutup">✕</button>
        </div>

        <form method="post" action="<?php echo htmlspecialchars(url_for('modules/inventory/insert.php'), ENT_QUOTES); ?>">
            <div class="modal__body">
                <label class="field">
                    <span class="field__label">Nama Barang</span>
                    <div class="field__control">
                        <input class="input" type="text" name="nama_barang" placeholder="Contoh: Mikroskop" required />
                    </div>
                </label>

                <label class="field">
                    <span class="field__label">Kode Aset</span>
                    <div class="field__control">
                        <input class="input" type="text" name="kode_aset" placeholder="Contoh: LAB-001" required />
                    </div>
                </label>

                <label class="field">
                    <span class="field__label">Stok</span>
                    <div class="field__control">
                        <input class="input" type="number" min="0" name="stok" value="0" required />
                    </div>
                </label>

                <label class="field">
                    <span class="field__label">Kondisi</span>
                    <div class="field__control">
                        <select class="input" name="kondisi" required>
                            <option value="Baik">Baik</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </label>
            </div>

            <div class="modal__footer">
                <button class="btn btn--ghost" type="button" data-modal-close>BATAL</button>
                <button class="btn btn--cyan" type="submit">TAMBAH</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="editStockModal" aria-hidden="true">
    <div class="modal__backdrop" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="editStockTitle">
        <div class="modal__header">
            <div class="modal__title" id="editStockTitle">Edit Stok</div>
            <button class="modal__close" type="button" data-modal-close aria-label="Tutup">✕</button>
        </div>

        <form method="post" action="<?php echo htmlspecialchars(url_for('modules/inventory/update.php'), ENT_QUOTES); ?>">
            <input type="hidden" name="id_barang" value="" />

            <div class="modal__body">
                <label class="field">
                    <span class="field__label">Nama Barang</span>
                    <div class="field__control">
                        <input class="input" type="text" name="nama_barang" required />
                    </div>
                </label>

                <label class="field">
                    <span class="field__label">Kode Aset</span>
                    <div class="field__control">
                        <input class="input" type="text" name="kode_aset" required />
                    </div>
                </label>

                <label class="field">
                    <span class="field__label">Stok</span>
                    <div class="field__control">
                        <input class="input" type="number" min="0" name="stok" required />
                    </div>
                </label>

                <label class="field">
                    <span class="field__label">Kondisi</span>
                    <div class="field__control">
                        <select class="input" name="kondisi" required>
                            <option value="Baik">Baik</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </label>
            </div>

            <div class="modal__footer">
                <button class="btn btn--ghost" type="button" data-modal-close>BATAL</button>
                <button class="btn btn--cyan" type="submit">SIMPAN</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/partials/app_shell_bottom.php'; ?>