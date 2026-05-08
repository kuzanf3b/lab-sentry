<?php

declare(strict_types=1);

require __DIR__ . '/../_boot.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to(url_for('index.php', ['page' => 'register']));
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    flash_set('error', 'Username dan password wajib diisi.');
    redirect_to(url_for('index.php', ['page' => 'register']));
}

if (mb_strlen($username) < 3) {
    flash_set('error', 'Username minimal 3 karakter.');
    redirect_to(url_for('index.php', ['page' => 'register']));
}

if (mb_strlen($password) < 6) {
    flash_set('error', 'Password minimal 6 karakter.');
    redirect_to(url_for('index.php', ['page' => 'register']));
}

$stmt = $pdo->prepare('SELECT id FROM tbl_user WHERE username = :username LIMIT 1');
$stmt->execute(['username' => $username]);
$exists = (bool) $stmt->fetch();

if ($exists) {
    flash_set('error', 'Username sudah terdaftar.');
    redirect_to(url_for('index.php', ['page' => 'register']));
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare('INSERT INTO tbl_user (username, password) VALUES (:username, :password)');
$insert->execute([
    'username' => $username,
    'password' => $hash,
]);

flash_set('success', 'Registrasi berhasil. Silakan login.');
redirect_to(url_for('index.php', ['page' => 'login']));
