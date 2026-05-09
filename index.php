<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/app.php';

function redirect_to(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = (string) $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $message;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function badge_class(string $kondisi): string
{
    return match ($kondisi) {
        'Baik' => 'badge--green',
        'Perbaikan' => 'badge--yellow',
        'Rusak' => 'badge--pink',
        default => 'badge--muted',
    };
}

$page = (string) ($_GET['page'] ?? '');
if ($page === '') {
    $page = is_logged_in() ? 'dashboard' : 'login';
}

$publicPages = ['login', 'register'];
if (!in_array($page, $publicPages, true) && !is_logged_in()) {
    redirect_to(url_for('index.php', ['page' => 'login']));
}
if (in_array($page, $publicPages, true) && is_logged_in()) {
    redirect_to(url_for('index.php', ['page' => 'dashboard']));
}

$view = match ($page) {
    'login' => __DIR__ . '/views/auth/login.php',
    'register' => __DIR__ . '/views/auth/register.php',
    'dashboard' => __DIR__ . '/views/dashboard.php',
    'inventory' => __DIR__ . '/views/inventory.php',
    'inventory_form' => __DIR__ . '/views/inventory_form.php',
    'loan' => __DIR__ . '/views/loan.php',
    'report' => __DIR__ . '/views/report.php',
    default => __DIR__ . '/views/dashboard.php',
};

require $view;
