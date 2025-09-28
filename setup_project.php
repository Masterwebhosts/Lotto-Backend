<?php
// setup_project.php

$projectPath = __DIR__;
$dbHost = '127.0.0.1';
$dbName = 'lotto_db';
$dbUser = 'yassin';
$dbPass = 'F9daCrPCeB6G2YK1';
$mysqlBin = 'C:/xampp/mysql/bin/mysql.exe';

// ---------------------------
// 1. إنشاء المجلدات اللازمة
// ---------------------------
$folders = [
    "$projectPath/storage/app",
    "$projectPath/storage/framework/cache/data",
    "$projectPath/storage/framework/sessions",
    "$projectPath/storage/framework/views",
    "$projectPath/storage/logs",
    "$projectPath/bootstrap/cache",
];

foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
        echo "✅ Created folder: $folder\n";
    } else {
        echo "ℹ️ Folder exists: $folder\n";
    }
    chmod($folder, 0777);
}

// ---------------------------
// 2. مسح الكاش القديم
// ---------------------------
function clearFolder($folder) {
    if (is_dir($folder)) {
        $files = glob($folder . '/*');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
            elseif (is_dir($file)) clearFolder($file);
        }
    }
}

clearFolder("$projectPath/storage/framework/cache/data");
clearFolder("$projectPath/bootstrap/cache");

echo "✅ Cache cleared!\n";

// ---------------------------
// 3. تشغيل الميجريشن
// ---------------------------
$allMigrations = "$projectPath/database/migrations/all_migrations.sql";
if (file_exists($allMigrations)) {
    $command = "\"$mysqlBin\" -u $dbUser -p$dbPass $dbName < \"$allMigrations\"";
    system($command, $retval);
    if ($retval === 0) echo "✅ Migrations applied successfully!\n";
    else echo "❌ Error running migrations!\n";
} else {
    echo "ℹ️ No migrations file found.\n";
}

// ---------------------------
// 4. تشغيل seed للادمن
// ---------------------------
$seedAdmin = "$projectPath/database/seeds/seed_admin.sql";
if (file_exists($seedAdmin)) {
    $command = "\"$mysqlBin\" -u $dbUser -p$dbPass $dbName < \"$seedAdmin\"";
    system($command, $retval);
    if ($retval === 0) echo "✅ Admin seeded successfully!\n";
    else echo "❌ Error seeding admin!\n";
} else {
    echo "ℹ️ No seed file found.\n";
}

echo "\n🎯 Project setup completed!\n";
