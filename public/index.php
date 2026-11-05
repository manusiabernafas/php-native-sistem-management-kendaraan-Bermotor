<?php
/**
 * Single Entry Point
 * Semua request HTTP berawal dari sini.
 */

// Memulai session PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
