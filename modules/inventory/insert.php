<?php
declare(strict_types=1);

require __DIR__ . '/../_boot.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=inventory');
}

$nama_barang = trim((string) ($_POST['nama_barang'] ?? ''));
$kode_aset = trim((string) ($_POST['kode_aset'] ?? ''));
$kondisi = (string) ($_POST['kondisi'] ?? 'Baik');
$stok = (string) ($_POST['stok'] ?? '0');

$allowedKondisi = ['Baik', 'Rusak', 'Perbaikan'];
if ($nama_barang === '' || $kode_aset === '') {
    flash_set('error', 'Nama Barang dan Kode Aset wajib diisi.');
    redirect_to('../index.php?page=inventory_form&mode=create');
}

if (!in_array($kondisi, $allowedKondisi, true)) {
    flash_set('error', 'Kondisi tidak valid.');
    redirect_to('../index.php?page=inventory_form&mode=create');
}

if (!ctype_digit($stok)) {
    flash_set('error', 'Stok harus berupa angka (>= 0).');
    redirect_to('../index.php?page=inventory_form&mode=create');
}

try {
    $stmt = $pdo->prepare('INSERT INTO tbl_inventory (nama_barang, kode_aset, kondisi, stok) VALUES (:nama, :kode, :kondisi, :stok)');
    $stmt->execute([
        'nama' => $nama_barang,
        'kode' => $kode_aset,
        'kondisi' => $kondisi,
        'stok' => (int) $stok,
    ]);

    flash_set('success', 'Barang berhasil ditambahkan.');
    redirect_to('../index.php?page=inventory');
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        flash_set('error', 'Kode Aset sudah digunakan.');
        redirect_to('../index.php?page=inventory_form&mode=create');
    }

    flash_set('error', 'Gagal menambah barang.');
    redirect_to('../index.php?page=inventory_form&mode=create');
}
