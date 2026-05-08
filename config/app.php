<?php

declare(strict_types=1);

/**
 * Returns the URL path (no trailing slash) to the project root.
 *
 * Examples:
 * - /lab-sentry
 * - /PHP/project/lab-sentry
 * - "" when project is the web root
 */
function app_root_path(): string
{
    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));

    $dir = str_replace('\\', '/', (string) dirname($scriptName));
    $dir = rtrim($dir, '/');

    // If executed from a subfolder like /modules/... or /views/..., jump back to root.
    $dir = (string) preg_replace('~/(modules|views)(/.*)?$~', '', $dir);

    if ($dir === '/' || $dir === '.') {
        return '';
    }

    return $dir;
}

/**
 * Build an absolute (host-rooted) URL for a file inside this project.
 *
 * url_for('index.php', ['page' => 'dashboard']) => /lab-sentry/index.php?page=dashboard
 */
function url_for(string $path, array $query = []): string
{
    if (preg_match('~^https?://~i', $path) === 1) {
        return $query ? ($path . (str_contains($path, '?') ? '&' : '?') . http_build_query($query)) : $path;
    }

    $path = ltrim($path, '/');
    $base = app_root_path();
    $url = ($base === '' ? '' : $base) . '/' . $path;

    if ($query) {
        $url .= '?' . http_build_query($query);
    }

    return $url;
}
