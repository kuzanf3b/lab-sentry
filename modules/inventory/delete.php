<?php

declare(strict_types=1);

require __DIR__ . '/../_boot.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to(url_for('index.php', ['page' => 'inventory']));
}

$id_barang = (string) ($_POST['id_barang'] ?? '');
if (!ctype_digit($id_barang)) {
    flash_set('error', 'ID barang tidak valid.');
    redirect_to(url_for('index.php', ['page' => 'inventory']));
}

$stmt = $pdo->prepare('DELETE FROM tbl_inventory WHERE id_barang = :id');
$stmt->execute(['id' => (int) $id_barang]);

flash_set('success', 'Barang berhasil dihapus.');
redirect_to(url_for('index.php', ['page' => 'inventory']));
