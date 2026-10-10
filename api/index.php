<?php
// File: api/index.php
// Master Serverless Router untuk Vercel (1 Single Serverless Function - Bypass Limit 12 Hobby Plan)

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Jika mengakses root atau index.php, arahkan ke modul mahasiswa
if (empty($path) || $path === 'index.php') {
    header("Location: /mahasiswa");
    exit();
}

// Arahkan ke file yang sesuai di dalam folder src/
$target = __DIR__ . '/../src/' . $path;

if (is_dir($target)) {
    $target = rtrim($target, '/') . '/index.php';
} elseif (!file_exists($target) && file_exists($target . '.php')) {
    $target = $target . '.php';
}

if (file_exists($target) && is_file($target)) {
    require $target;
    exit();
}

// Fallback jika halaman tidak ditemukan
http_response_code(404);
echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body style='font-family: Arial, sans-serif; padding: 30px;'><h2>404 - Halaman Tidak Ditemukan</h2><p><a href='/mahasiswa'>&laquo; Kembali ke Data Mahasiswa</a></p></body></html>";
exit();
?>