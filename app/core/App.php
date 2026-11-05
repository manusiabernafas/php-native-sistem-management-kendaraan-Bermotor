<?php
/**
 * Router / App Core MVC
 * Mengurai URL untuk memanggil Controller dan Method yang tepat
 */
class App {
    // Default controller dan method jika tidak ada URL (Halaman Publik/Monitor)
    protected $controller = 'PublicController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Cek apakah controller ada
        if (isset($url[0])) {
            // Karena nama controller diawali huruf kapital dan diakhiri kata 'Controller'
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists(APP_PATH . 'controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Require dan instansiasi controller
        require_once APP_PATH . 'controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Cek apakah method/fungsi di controller tersebut ada
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Ambil sisa parameter (jika ada)
        $this->params = $url ? array_values($url) : [];

        // 4. Jalankan controller & method, serta kirim params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Memecah URL menjadi Array
     * Contoh: website.com/admin/armada/ARM-001 -> ['admin', 'armada', 'ARM-001']
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
            
            // Hubungkan parameter kueri jika terdapat "?" di dalam parameter "url"
            $urlParts = explode('?', $url);
            $cleanUrl = $urlParts[0];
            if (isset($urlParts[1])) {
                parse_str($urlParts[1], $queryParams);
                $_GET = array_merge($_GET, $queryParams);
            }
            
            $cleanUrl = rtrim($cleanUrl, '/');
            $cleanUrl = ltrim($cleanUrl, '/');
            $cleanUrl = filter_var($cleanUrl, FILTER_SANITIZE_URL);
            return explode('/', $cleanUrl);
        }
        return [];
    }
}
