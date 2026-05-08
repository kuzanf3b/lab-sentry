<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../config/koneksi.php';

function redirect_to(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function require_login(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirect_to('../index.php?page=login');
    }
}
