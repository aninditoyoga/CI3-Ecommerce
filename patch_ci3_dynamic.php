<?php
/**
 * Patch CodeIgniter 3 for PHP 8.2–8.3 compatibility
 * Automatically adds #[AllowDynamicProperties] to system/core classes
 */

$systemCorePath = __DIR__ . '/system/core';

if (!is_dir($systemCorePath)) {
    exit("❌ Folder system/core tidak ditemukan!\nPastikan script ini diletakkan di root project CodeIgniter 3.\n");
}

$patched = 0;
$files = glob($systemCorePath . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);

    // Skip jika sudah di-patch
    if (strpos($content, '#[AllowDynamicProperties]') !== false) {
        continue;
    }

    // Tambahkan atribut sebelum deklarasi class utama
    $content = preg_replace(
        '/^(class\s+CI_[a-zA-Z0-9_]+\s+)/m',
        "#[AllowDynamicProperties]\n\$1",
        $content,
        1,
        $count
    );

    if ($count > 0) {
        file_put_contents($file, $content);
        echo "✅ Patched: " . basename($file) . "\n";
        $patched++;
    }
}

if ($patched === 0) {
    echo "ℹ️ Tidak ada file yang perlu di-patch atau semua sudah dipatch sebelumnya.\n";
} else {
    echo "\n🎉 Selesai! Total $patched file sudah di-patch agar kompatibel dengan PHP 8.3.\n";
}
