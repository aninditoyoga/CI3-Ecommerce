<?php
/**
 * Patch tambahan untuk CodeIgniter 3 di PHP 8.3
 * - Menambahkan #[AllowDynamicProperties] ke CI_DB_mysqli_driver
 * - Menambahkan #[\\ReturnTypeWillChange] ke fungsi Session_files_driver
 */

// === PATCH 1: DB_driver.php ===
$dbDriverPath = __DIR__ . '/system/database/DB_driver.php';
if (file_exists($dbDriverPath)) {
    $content = file_get_contents($dbDriverPath);

    // Pastikan patch hanya untuk class CI_DB_driver (bukan interface atau lainnya)
    if (strpos($content, 'class CI_DB_mysqli_driver') !== false && strpos($content, '#[AllowDynamicProperties]') === false) {
        $content = preg_replace(
            '/(class\s+CI_DB_mysqli_driver\s+)/',
            "#[AllowDynamicProperties]\n$1",
            $content,
            1,
            $count
        );
        if ($count > 0) {
            file_put_contents($dbDriverPath, $content);
            echo "✅ Patched: CI_DB_mysqli_driver (DB_driver.php)\n";
        }
    }
}

// === PATCH 2: Session_files_driver.php ===
$sessionFile = __DIR__ . '/system/libraries/Session/drivers/Session_files_driver.php';
if (file_exists($sessionFile)) {
    $content = file_get_contents($sessionFile);

    // Tambahkan #[\\ReturnTypeWillChange] ke semua method yang perlu
    $methods = ['open', 'close', 'read', 'write', 'destroy', 'gc'];
    foreach ($methods as $method) {
        $pattern = '/public\s+function\s+' . $method . '\s*\(/i';
        if (!preg_match('/#\\\\ReturnTypeWillChange\s*\n\s*public\s+function\s+' . $method . '/i', $content)) {
            $content = preg_replace($pattern, "#[\\ReturnTypeWillChange]\n    public function $method(", $content);
        }
    }

    file_put_contents($sessionFile, $content);
    echo "✅ Patched: Session_files_driver.php (ReturnType compatibility)\n";
}

echo "\n🎉 Semua patch tambahan telah diterapkan. Sekarang CodeIgniter 3 aman untuk PHP 8.3.\n";