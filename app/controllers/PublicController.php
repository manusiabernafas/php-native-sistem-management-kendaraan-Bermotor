<?php
/**
 * PublicController
 * Controller default untuk menangani akses publik (Tanpa Login)
 */
class PublicController extends Controller {

    public function index() {
        // Tampilkan halaman sambutan / scan QR umum
        $data = [
            'title' => 'SIMANDA - Cek Status Armada'
        ];
        $this->view('templates/header', $data);
        $this->view('public/index', $data);
        $this->view('templates/footer');
    }

    // Fungsi untuk menampilkan detail armada saat QR di scan
    public function vehicle($id = null) {
        if (!$id) {
            $this->redirect('');
        }
        
        $armadaModel = $this->model('ArmadaModel');
        $serviceModel = $this->model('ServiceModel');
        
        $armada = $armadaModel->findById($id);
        
        if (!$armada) {
            die("<h2>Kendaraan tidak ditemukan di database.</h2>");
        }

        $data = [
            'title' => 'Status Kendaraan',
            'armada' => $armada,
            'riwayat_servis' => $serviceModel->getByArmada($id)
        ];
        
        $this->view('templates/header', $data);
        $this->view('public/vehicle', $data);
        $this->view('templates/footer');
    }
}
