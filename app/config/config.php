<?php
/**
 * Konfigurasi Utama Aplikasi SIMANDA
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Path definitions
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__, 2) . DS);
define('APP_PATH', ROOT_PATH . 'app' . DS);
define('DATA_PATH', ROOT_PATH . 'data' . DS);
define('UPLOAD_PATH', ROOT_PATH . 'uploads' . DS);

// Base URL detection
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    // Remove '/public' from the base URL if it's there
    str_replace('/public', '', $scriptDir)
);
$baseUrl = rtrim($baseUrl, '/');

// BYPASS APACHE 404: Kita paksa pakai index.php?url= agar tidak perlu setting .htaccess
define('BASE_URL', $baseUrl . '/public/index.php?url=');

define('SECRET_KEY', 'SIMANDA_BANTUL_SECURE_KEY_2026_!@#');
define('APP_NAME', 'SIMANDA Dinkes Bantul');
date_default_timezone_set('Asia/Jakarta');
