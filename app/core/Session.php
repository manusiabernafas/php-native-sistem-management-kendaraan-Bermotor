<?php
/**
 * Session Helper
 * Mengelola Autentikasi, Hak Akses, dan Pesan Flash
 */

class Session {

    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Idle Session Timeout (1 jam = 3600 detik)
        if (isset($_SESSION['user_id'])) {
            $timeout_duration = 3600; // 1 Jam dalam detik
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
                // Sesi kedaluwarsa, hapus sesi
                self::destroy();
                
                // Mulai sesi baru khusus untuk melempar Flash Message ke halaman login
                session_start();
                self::setFlash('Sesi Anda telah berakhir karena tidak aktif selama 1 jam.', 'warning');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            }
            // Perbarui waktu aktivitas terakhir
            $_SESSION['last_activity'] = time();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }

    /**
     * Memastikan user sudah login
     */
    public static function checkLogin() {
        self::init();
        if (!self::get('user_id')) {
            // Simpan URL tujuan asli sebelum login
            $currentUrl = $_GET['url'] ?? '';
            if ($currentUrl && strpos($currentUrl, 'auth') === false) {
                self::set('redirect_target', '/' . $currentUrl);
            }
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

    public static function checkRole($role) {
        self::checkLogin();
        if (self::get('role') !== $role) {
            if (self::get('role') === 'admin' && $role === 'driver') {
                // Hancurkan sesi admin saat ini agar dipaksa login sebagai supir
                self::destroy();
                session_start();
                self::setFlash('⚠️ QR Code ini khusus untuk Supir. Silakan login sebagai Supir.', 'warning');
                header('Location: ' . BASE_URL . '/auth');
                exit;
            } elseif (self::get('role') === 'driver' && $role === 'admin') {
                self::setFlash('❌ Halaman ini hanya untuk Admin.', 'danger');
                header('Location: ' . BASE_URL . '/driver');
                exit;
            } else {
                die("<h1>403 Forbidden</h1><p>Anda tidak memiliki akses ke halaman ini.</p>");
            }
        }
    }

    /**
     * Set Pesan Flash (Muncul sekali lalu hilang)
     */
    public static function setFlash($pesan, $tipe = 'success') {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'tipe' => $tipe // success, danger, warning
        ];
    }

    /**
     * Tampilkan Pesan Flash jika ada (Dipanggil di View)
     */
    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $pesan = $_SESSION['flash']['pesan'];
            $tipe = $_SESSION['flash']['tipe'];
            
            // Format Brutalism
            $bgColor = $tipe === 'success' ? 'var(--primary)' : ($tipe === 'danger' ? 'var(--danger)' : 'var(--warning)');
            
            echo '<div style="background: '.$bgColor.'; border: var(--border-thick); padding: 12px; margin-bottom: 20px; box-shadow: var(--shadow-hard); font-weight: bold;">
                    ' . htmlspecialchars($pesan) . '
                  </div>';
                  
            unset($_SESSION['flash']);
        }
    }
}
