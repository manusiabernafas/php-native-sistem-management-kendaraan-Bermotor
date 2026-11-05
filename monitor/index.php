<?php
/**
 * Single Entry Point for /monitor route
 * Resolves 404 on Apache configurations where AllowOverride is disabled.
 */

// Memulai session PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ubah SCRIPT_NAME agar deteksi BASE_URL di config.php tetap akurat mengarah ke /public
if (isset($_SERVER['SCRIPT_NAME'])) {
    $_SERVER['SCRIPT_NAME'] = str_replace('/monitor/index.php', '/public/index.php', $_SERVER['SCRIPT_NAME']);
}

// Set parameter URL agar Router mengeksekusi MonitorController
$_GET['url'] = 'monitor';

// Panggil file konfigurasi
require_once '../app/config/config.php';

// Panggil Autoloader atau Core Classes
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/JsonDB.php';
require_once '../app/core/Session.php';

// Inisialisasi Session Global
Session::init();

// Inisialisasi Aplikasi (Menjalankan Router)
$app = new App();
