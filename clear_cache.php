<?php
// clear_cache.php

// مسح كاش Laravel (أو أي كاش ملفات)
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                $path = $dir . DIRECTORY_SEPARATOR . $object;
                if (is_dir($path)) {
                    rrmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
        rmdir($dir);
    }
}

// مسار مجلد الكاش، حسب مشروعك
$cacheDirs = [
    __DIR__ . '/storage/framework/cache/data',
    __DIR__ . '/bootstrap/cache',
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        rrmdir($dir);
        echo "✅ تم مسح الكاش في: $dir\n";
    } else {
        echo "ℹ️ المجلد غير موجود: $dir\n";
    }
}
