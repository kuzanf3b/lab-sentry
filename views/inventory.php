<?php
require __DIR__ . '/partials/app_shell_top.php';

$success = flash_get('success');
$error = flash_get('error');
$no = 1;

$rows = $pdo->query('SELECT id_barang, nama_barang, kode_aset, kondisi, stok, tgl_update FROM tbl_inventory ORDER BY id_barang DESC')->fetchAll();
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
                <button class="btn btn--ghost" type="button" data-modal-close>Batal</button>
                <button class="btn btn--cyan" type="submit">Tambah</button>
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
                <button class="btn btn--ghost" type="button" data-modal-close>Batal</button>
                <button class="btn btn--cyan" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/partials/app_shell_bottom.php'; ?>