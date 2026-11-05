<?php
/**
 * Base Controller MVC
 */
class Controller {
    
    /**
     * Memanggil View dan meneruskan data
     */
    public function view($view, $data = []) {
        $viewFile = APP_PATH . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            // Ekstrak data array menjadi variabel (misal: ['nama' => 'Budi'] menjadi $nama = 'Budi')
            extract($data);
            require_once $viewFile;
        } else {
            die("View [{$view}] tidak ditemukan di " . $viewFile);
        }
    }

    /**
     * Memanggil Model
     */
    public function model($model) {
        $modelFile = APP_PATH . 'models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Model [{$model}] tidak ditemukan.");
        }
    }

    /**
     * Mengirim response dalam bentuk JSON (Untuk API/AJAX PWA)
     */
    public function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Mengarahkan URL
     */
    public function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
