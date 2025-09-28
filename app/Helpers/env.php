<?php
if (!function_exists('env')) {
    /**
     * قراءة قيمة من ملف .env
     * @param string $key اسم المتغير
     * @param mixed $default القيمة الافتراضية إن لم توجد
     * @return string|null
     */
    function env($key, $default = null) {
        static $env = null;

        if ($env === null) {
            $envPath = __DIR__ . '/../../.env';
            $env = [];

            if (file_exists($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue; // تخطي التعليقات
                    if (!str_contains($line, '=')) continue;
                    list($k, $v) = array_map('trim', explode('=', $line, 2));
                    $env[$k] = $v;
                }
            }
        }

        return $env[$key] ?? $default;
    }
}
