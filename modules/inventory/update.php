<?php

declare(strict_types=1);

require __DIR__ . '/../_boot.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to(url_for('index.php', ['page' => 'inventory']));
}

$id_barang = (string) ($_POST['id_barang'] ?? '');
$nama_barang = trim((string) ($_POST['nama_barang'] ?? ''));
$kode_aset = trim((string) ($_POST['kode_aset'] ?? ''));
$kondisi = (string) ($_POST['kondisi'] ?? 'Baik');
$stok = (string) ($_POST['stok'] ?? '0');

$allowedKondisi = ['Baik', 'Rusak', 'Perbaikan'];

if (!ctype_digit($id_barang)) {
    flash_set('error', 'ID barang tidak valid.');
    redirect_to(url_for('index.php', ['page' => 'inventory']));
}

if ($nama_barang === '' || $kode_aset === '') {
    flash_set('error', 'Nama Barang dan Kode Aset wajib diisi.');
    redirect_to(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'edit', 'id' => $id_barang]));
}

if (!in_array($kondisi, $allowedKondisi, true)) {
    flash_set('error', 'Kondisi tidak valid.');
    redirect_to(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'edit', 'id' => $id_barang]));
}

if (!ctype_digit($stok)) {
    flash_set('error', 'Stok harus berupa angka (>= 0).');
    redirect_to(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'edit', 'id' => $id_barang]));
}

try {
    $stmt = $pdo->prepare('UPDATE tbl_inventory SET nama_barang = :nama, kode_aset = :kode, kondisi = :kondisi, stok = :stok WHERE id_barang = :id');
    $stmt->execute([
        'nama' => $nama_barang,
        'kode' => $kode_aset,
        'kondisi' => $kondisi,
        'stok' => (int) $stok,
        'id' => (int) $id_barang,
    ]);

    flash_set('success', 'Barang berhasil diperbarui.');
    redirect_to(url_for('index.php', ['page' => 'inventory']));
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        flash_set('error', 'Kode Aset sudah digunakan.');
        redirect_to(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'edit', 'id' => $id_barang]));
    }

    flash_set('error', 'Gagal memperbarui barang.');
    redirect_to(url_for('index.php', ['page' => 'inventory_form', 'mode' => 'edit', 'id' => $id_barang]));
}
