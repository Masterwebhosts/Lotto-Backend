<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** ارجاع اللغة الحالية (en/ar/tr) بعد التطبيع */
function current_lang(): string {
    $lang = strtolower(trim($_SESSION['lang'] ?? 'en'));
    $allowed = ['en','ar','tr'];
    return in_array($lang, $allowed, true) ? $lang : 'en';
}

/** اتجاه الصفحة حسب اللغة */
function lang_dir(): string {
    return current_lang() === 'ar' ? 'rtl' : 'ltr';
}

/**
 * ترجمة بمبدأ fallback:
 * - نحمل en.php دائماً كأساس
 * - ندمج فوقه ملف اللغة الحالي (إن وجد)
 */
function __($key) {
    $baseDir  = __DIR__ . '/../Lang/';
    $fallback = $baseDir . 'en.php';
    $current  = $baseDir . current_lang() . '.php';

    $map = [];
    if (file_exists($fallback)) {
        $base = include $fallback;
        if (is_array($base)) $map = $base;
    }
    if (file_exists($current)) {
        $override = include $current;
        if (is_array($override)) $map = array_merge($map, $override);
    }
    return $map[$key] ?? $key;
}



