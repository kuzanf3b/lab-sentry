<?php
require __DIR__ . '/partials/app_shell_top.php';

$error = flash_get('error');
$success = flash_get('success');

$mode = (string) ($_GET['mode'] ?? 'create');
$id = (string) ($_GET['id'] ?? '');

$isEdit = $mode === 'edit';
$data = [
    'id_barang' => '',
    'nama_barang' => '',
    'kode_aset' => '',
    'kondisi' => 'Baik',
    'stok' => 0,
];

if ($isEdit) {
    if (!ctype_digit($id)) {
        flash_set('error', 'ID barang tidak valid.');
        redirect_to('index.php?page=inventory');
    }

    $stmt = $pdo->prepare('SELECT id_barang, nama_barang, kode_aset, kondisi, stok FROM tbl_inventory WHERE id_barang = :id LIMIT 1');
    $stmt->execute(['id' => (int) $id]);
    $row = $stmt->fetch();

    if (!$row) {
        flash_set('error', 'Data tidak ditemukan.');
        redirect_to('index.php?page=inventory');
    }

    $data = $row;
}

$action = $isEdit ? 'modules/inventory/update.php' : 'modules/inventory/insert.php';
$title = $isEdit ? 'Edit Barang' : 'Tambah Barang';
$subtitle = $isEdit ? 'Perbarui detail inventaris' : 'Masukkan data inventaris baru';
?>

<section class="panel">
    <div class="panel__header">
        <h2 class="panel__title"><?php echo htmlspecialchars($title, ENT_QUOTES); ?></h2>
        <div class="panel__subtitle"><?php echo htmlspecialchars($subtitle, ENT_QUOTES); ?></div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert--success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert--error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
    <?php endif; ?>

    <form class="form form--grid" method="post" action="<?php echo htmlspecialchars($action, ENT_QUOTES); ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id_barang" value="<?php echo (int) $data['id_barang']; ?>" />
        <?php endif; ?>

        <label class="field">
            <span class="field__label">Nama Barang</span>
            <div class="field__control">
                <input class="input" type="text" name="nama_barang" placeholder="Contoh: Mikroskop" value="<?php echo htmlspecialchars((string) $data['nama_barang'], ENT_QUOTES); ?>" required />
            </div>
        </label>

        <label class="field">
            <span class="field__label">Kode Aset</span>
            <div class="field__control">
                <input class="input" type="text" name="kode_aset" placeholder="Contoh: LAB-001" value="<?php echo htmlspecialchars((string) $data['kode_aset'], ENT_QUOTES); ?>" required />
            </div>
        </label>

        <label class="field">
            <span class="field__label">Stok</span>
            <div class="field__control">
                <input class="input" type="number" min="0" name="stok" value="<?php echo (int) $data['stok']; ?>" required />
            </div>
        </label>

        <label class="field">
            <span class="field__label">Kondisi</span>
            <div class="field__control">
                <select class="input" name="kondisi" required>
                    <?php foreach (['Baik', 'Perbaikan', 'Rusak'] as $opt): ?>
                        <option value="<?php echo htmlspecialchars($opt, ENT_QUOTES); ?>" <?php echo ((string) $data['kondisi'] === $opt) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($opt, ENT_QUOTES); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </label>

        <div class="form__actions">
            <a class="btn btn--ghost" href="index.php?page=inventory">Kembali</a>
            <button class="btn <?php echo $isEdit ? 'btn--yellow' : 'btn--cyan'; ?>" type="submit">
                <?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah Barang'; ?>
            </button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/partials/app_shell_bottom.php'; ?>
