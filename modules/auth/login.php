<?php
declare(strict_types=1);

require __DIR__ . '/../_boot.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../index.php?page=login');
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    flash_set('error', 'Username dan password wajib diisi.');
    redirect_to('../index.php?page=login');
}

$stmt = $pdo->prepare('SELECT id, username, password FROM tbl_user WHERE username = :username LIMIT 1');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, (string) $user['password'])) {
    flash_set('error', 'Login gagal. Periksa username/password.');
    redirect_to('../index.php?page=login');
}

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = (string) $user['username'];

redirect_to('../index.php?page=dashboard');
