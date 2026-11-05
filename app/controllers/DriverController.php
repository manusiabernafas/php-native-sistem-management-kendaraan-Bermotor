<?php
/**
 * DriverController
 * Mengurus PWA khusus untuk Supir
 */
class DriverController extends Controller {

    public function __construct() {
        // Proteksi tingkat controller, wajib login sebagai driver
        Session::checkRole('driver');
    }

    public function index() {
        $armadaModel = $this->model('ArmadaModel');
        $data = [
            'title' => 'Driver PWA - SIMANDA',
            'dark_mode' => false,
            'armada_list' => $armadaModel->getAll()
        ];
        
        $this->view('templates/header', $data);
        $this->view('driver/home', $data);
        $this->view('templates/footer');
    }

    public function report() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $alertModel = $this->model('AlertModel');
            $armadaModel = $this->model('ArmadaModel');
            
            $armada_id = $_POST['armada_id'] ?? '';
            $armada = $armadaModel->findById($armada_id);
            
            if ($armada) {
                $km_baru = (int) ($_POST['km_saat_ini'] ?? 0);
                if ($km_baru > 0 && $km_baru < (int)$armada['km_saat_ini']) {
                    Session::setFlash('❌ Gagal: Input Odometer (' . number_format($km_baru, 0, ',', '.') . ' KM) tidak boleh kurang dari KM terakhir kendaraan (' . number_format($armada['km_saat_ini'], 0, ',', '.') . ' KM)!', 'danger');
                    $this->redirect('/driver/report_page/' . $armada['id']);
                    return;
                }
            }
            
            // Handle file upload
            $fotoPath = '';
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $newName = 'alert_' . time() . '.' . $ext;
                $target = UPLOAD_PATH . 'alerts/' . $newName;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $fotoPath = $newName;
                }
            }
            
            // Simpan laporan
            $alertId = $alertModel->create([
                'armada_id' => $_POST['armada_id'] ?? 'TIDAK-DIKETAHUI',
                'keterangan' => $_POST['keterangan'] ?? 'Laporan Darurat / Kerusakan',
                'tipe' => $_POST['tipe'] ?? 'normal', // normal atau urgent
                'driver_id' => Session::get('user_id'),
                'driver_name' => Session::get('name'),
                'km_saat_ini' => $_POST['km_saat_ini'] ?? '',
                'foto' => $fotoPath,
                'gps_lat' => $_POST['gps_lat'] ?? '',
                'gps_lng' => $_POST['gps_lng'] ?? ''
            ]);
            
            // Otomatis masukkan ke antrean Servis (1 kesatuan)
            if ($alertId && ($_POST['armada_id'] ?? '') !== 'TIDAK-DIKETAHUI') {
                $serviceModel = $this->model('ServiceModel');
                $serviceModel->addSchedule([
                    'armada_id' => $_POST['armada_id'],
                    'tanggal' => date('Y-m-d'),
                    'jenis_service' => 'Perbaikan Laporan Kerusakan',
                    'keterangan' => 'Laporan dari ' . Session::get('name') . ': ' . ($_POST['keterangan'] ?? 'Tanpa keterangan'),
                    'alert_id' => $alertId,
                    'status' => 'pending'
                ]);

                // UPDATE STATUS ARMADA JADI RUSAK KARENA ADA ALERT LAPORAN KERUSAKAN
                $armadaModel = $this->model('ArmadaModel');
                $armada = $armadaModel->findById($_POST['armada_id']);
                $km_baru = (int) ($_POST['km_saat_ini'] ?? 0);
                if ($armada) {
                    $updateData = [
                        'status' => 'rusak',
                        'dipakai_oleh' => null, // Auto checkout supir karena mobil rusak
                        'checkin_time' => null  // Reset checkin time
                    ];
                    if ($km_baru > 0) {
                        $updateData['km_saat_ini'] = $km_baru;
                    }
                    $armadaModel->update($armada['id'], $updateData);
                }
                
                if ($armada && $km_baru > 0) {
                    $last_service_km = isset($armada['last_service_km']) ? (int)$armada['last_service_km'] : floor($km_baru / 5000) * 5000;
                    
                    if ($km_baru - $last_service_km >= 5000) {
                        $allSvc = $serviceModel->getByArmada($armada['id']);
                        $sudahAda = false;
                        foreach($allSvc as $svc) {
                            if ($svc['status'] === 'pending' && strpos(strtolower($svc['keterangan'] ?? ''), 'ganti oli') !== false) {
                                $sudahAda = true; break;
                            }
                        }
                        if (!$sudahAda) {
                            $serviceModel->addSchedule([
                                'armada_id' => $armada['id'],
                                'tanggal' => date('Y-m-d', strtotime('+3 days')),
                                'jenis_service' => 'Servis Rutin Berkala',
                                'keterangan' => '⚠️ GANTI OLI RUTIN (KM telah melampaui ' . number_format($km_baru, 0, ',', '.') . ')',
                                'status' => 'pending'
                            ]);
                            $armadaModel->update($armada['id'], ['last_service_km' => $km_baru]);
                        }
                    }
                }
            }
            
            Session::setFlash('✅ Laporan terkirim & otomatis masuk ke Jadwal Servis!', 'success');
            $this->redirect('/driver');
        }
    }
    public function scan($id = null) {
        if (!$id) $this->redirect('/driver');
        
        $model = $this->model('ArmadaModel');
        
        // Proteksi: Cek jika supir sudah punya shift aktif
        $allArmadas = $model->getAll();
        foreach ($allArmadas as $a) {
            if (($a['dipakai_oleh'] ?? '') === Session::get('name')) {
                Session::setFlash('❌ Gagal: Anda sedang berada dalam shift aktif membawa mobil ' . $a['no_polisi'] . '! Harap akhiri shift Anda terlebih dahulu.', 'danger');
                $this->redirect('/driver');
                return;
            }
        }
        
        $armada = $model->findById($id);
        
        if (!$armada) {
            Session::setFlash('❌ Mobil tidak terdaftar di sistem.', 'danger');
            $this->redirect('/driver');
            return;
        }

        // Proteksi: Cek jika kendaraan tidak layak jalan / tidak tersedia (rusak / service)
        if ($armada['status'] !== 'tersedia') {
            Session::setFlash('❌ Gagal: Kendaraan ' . $armada['no_polisi'] . ' sedang tidak tersedia (' . strtoupper($armada['status']) . ')!', 'danger');
            $this->redirect('/driver');
            return;
        }

        // Proteksi: Cek jika kendaraan sedang digunakan oleh supir lain
        if (!empty($armada['dipakai_oleh'])) {
            Session::setFlash('❌ Gagal: Kendaraan ' . $armada['no_polisi'] . ' sedang digunakan oleh supir lain (' . htmlspecialchars($armada['dipakai_oleh']) . ')!', 'danger');
            $this->redirect('/driver');
            return;
        }

        $data = [
            'title' => 'Check-in Kendaraan',
            'armada' => $armada,
            'dark_mode' => false
        ];
        
        $this->view('templates/header', $data);
        $this->view('driver/checkin_form', $data);
        $this->view('templates/footer');
    }

    public function checkin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $armadaModel = $this->model('ArmadaModel');
            
            // Proteksi: Cek jika supir sudah punya shift aktif
            $allArmadas = $armadaModel->getAll();
            foreach ($allArmadas as $a) {
                if (($a['dipakai_oleh'] ?? '') === Session::get('name')) {
                    Session::setFlash('❌ Gagal: Anda sedang berada dalam shift aktif membawa mobil ' . $a['no_polisi'] . '! Harap akhiri shift Anda terlebih dahulu.', 'danger');
                    $this->redirect('/driver');
                    return;
                }
            }
            // Validasi pengecekan harian
            if ($_POST['cek_rem'] == 'Tidak' || $_POST['cek_lampu'] == 'Tidak') {
                Session::setFlash('⚠️ KENDARAAN TIDAK LAYAK JALAN! Silakan lapor kerusakan.', 'danger');
                $this->redirect('/driver/report_page/' . $_POST['armada_id']); 
                return;
            }

            // Simpan data checkin ke armada (update KM)
            $armadaModel = $this->model('ArmadaModel');
            $armada = $armadaModel->findById($_POST['armada_id']);
            
            if (!$armada) {
                Session::setFlash('❌ Mobil tidak terdaftar di sistem.', 'danger');
                $this->redirect('/driver');
                return;
            }

            // Proteksi: Cek jika kendaraan sedang digunakan oleh supir lain
            if (!empty($armada['dipakai_oleh'])) {
                Session::setFlash('❌ Gagal: Kendaraan ' . $armada['no_polisi'] . ' sedang digunakan oleh supir lain (' . htmlspecialchars($armada['dipakai_oleh']) . ')!', 'danger');
                $this->redirect('/driver');
                return;
            }

            // Proteksi: Cek jika kendaraan tidak layak jalan / tidak tersedia (rusak / service)
            if ($armada['status'] !== 'tersedia') {
                Session::setFlash('❌ Gagal: Kendaraan ' . $armada['no_polisi'] . ' sedang tidak tersedia (' . strtoupper($armada['status']) . ')!', 'danger');
                $this->redirect('/driver');
                return;
            }

            if ($armada) {
                $km_baru = (int) $_POST['km_saat_ini'];
                
                // Proteksi: Odometer tidak boleh kurang dari KM terakhir
                if ($km_baru < (int)$armada['km_saat_ini']) {
                    Session::setFlash('❌ Gagal: Input Odometer (' . number_format($km_baru, 0, ',', '.') . ' KM) tidak boleh kurang dari KM terakhir kendaraan (' . number_format($armada['km_saat_ini'], 0, ',', '.') . ' KM)!', 'danger');
                    $this->redirect('/driver/scan/' . $armada['id']);
                    return;
                }

                // Update armada status jadi dipakai oleh supir ini
                $armadaModel->update($armada['id'], [
                    'dipakai_oleh' => Session::get('name'),
                    'checkin_time' => time(),
                    'km_saat_ini' => $km_baru
                ]);

                // LOGIKA AUTO-SCHEDULE GANTI OLI (Kelipatan 5.000 KM)
                $last_service_km = isset($armada['last_service_km']) ? (int)$armada['last_service_km'] : 0;
                
                // Jika belum pernah diset, jadikan kelipatan 5000 terdekat di bawahnya
                if ($last_service_km == 0) {
                    $last_service_km = floor($km_baru / 5000) * 5000;
                    $armadaModel->update($armada['id'], ['last_service_km' => $last_service_km]);
                }

                if ($km_baru - $last_service_km >= 5000) {
                    $serviceModel = $this->model('ServiceModel');
                    
                    // Pastikan belum ada jadwal ganti oli pending
                    $allSvc = $serviceModel->getByArmada($armada['id']);
                    $sudahAda = false;
                    foreach($allSvc as $svc) {
                        if ($svc['status'] === 'pending' && strpos(strtolower($svc['keterangan'] ?? ''), 'ganti oli') !== false) {
                            $sudahAda = true; break;
                        }
                    }

                    if (!$sudahAda) {
                        $serviceModel->addSchedule([
                            'armada_id' => $armada['id'],
                            'tanggal' => date('Y-m-d', strtotime('+3 days')),
                            'jenis_service' => 'Servis Rutin Berkala',
                            'keterangan' => '⚠️ GANTI OLI RUTIN (KM telah melampaui ' . number_format($km_baru, 0, ',', '.') . ')',
                            'status' => 'pending'
                        ]);
                        // Set last_service_km agar tidak trigger terus sampai nambah 5000 lagi
                        $armadaModel->update($armada['id'], ['last_service_km' => $km_baru]);
                    }
                }
            }

            Session::setFlash('✅ Check-in berhasil. Selamat Bertugas!', 'success');
            $this->redirect('/driver');
        }
    }

    // Fitur 10: Manajemen Kelelahan Sopir (Check-out)
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['armada_id'])) {
            $armadaModel = $this->model('ArmadaModel');
            $armada = $armadaModel->findById($_POST['armada_id']);
            
            if ($armada && $armada['dipakai_oleh'] === Session::get('name')) {
                // Clear dipakai_oleh and checkin_time
                $armadaModel->update($armada['id'], [
                    'dipakai_oleh' => null,
                    'checkin_time' => null
                ]);
                Session::setFlash('✅ Selesai bertugas! Anda telah Check-out. Selamat beristirahat.', 'success');
            }
        }
        $this->redirect('/driver');
    }

    public function scanner() {
        $data = [
            'title' => 'Scan QR Kendaraan',
            'dark_mode' => false
        ];
        $this->view('templates/header', $data);
        $this->view('driver/scanner', $data);
        $this->view('templates/footer');
    }

    public function report_page($id = null) {
        if (!$id) $this->redirect('/driver');
        
        $model = $this->model('ArmadaModel');
        $armada = $model->findById($id);
        
        if (!$armada) $this->redirect('/driver');

        $data = [
            'title' => 'Form Laporan',
            'armada' => $armada,
            'dark_mode' => false
        ];
        $this->view('templates/header', $data);
        $this->view('driver/report_form', $data);
        $this->view('templates/footer');
    }

    public function report_page_redirect() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['armada_id'])) {
            $this->redirect('/driver/report_page/' . $_POST['armada_id']);
        }
        $this->redirect('/driver');
    }
}
