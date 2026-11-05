<?php
/**
 * MonitorController
 * Controller khusus untuk Layar TV Besar (Auto Refresh)
 */
class MonitorController extends Controller {

    public function index() {
        $armadaModel = $this->model('ArmadaModel');
        $alertModel = $this->model('AlertModel');

        $data = [
            'title' => 'Monitor Pusat SIMANDA',
            'stats' => $armadaModel->getStats(),
            'alerts' => $alertModel->getOpen(), // Mengambil peringatan yang masih terbuka
            'armada_list' => $armadaModel->getAll()
        ];

        // Kita tidak menggunakan template header/footer biasa karena ini desain khusus TV
        $this->view('monitor/index', $data);
    }
}
