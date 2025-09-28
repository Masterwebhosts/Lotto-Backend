<?php
require_once __DIR__ . '/env.php';

/** الأساس من .env (APP_URL) */
function base_url(string $path = ''): string {
    $base = rtrim(env('APP_URL', ''), '/');
    return $base . '/' . ltrim($path, '/');
}

/** اسم مختصر موحّد للمشروع */
function url(string $path = ''): string {
    return base_url($path);
}

