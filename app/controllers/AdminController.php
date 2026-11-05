<?php
/**
 * AdminController
 * Mengurus semua fitur halaman Admin PWA
 */
class AdminController extends Controller {

    public function __construct() {
        // Proteksi tingkat controller, wajib login sebagai admin
        Session::checkRole('admin');
    }

    public function index() {
        $data = ['title' => 'Admin PWA - Menu Utama'];
        
        $this->view('templates/header', $data);
        $this->view('admin/dashboard', $data); // Berisi App Shell Grid Menu
        $this->view('templates/footer');
    }

    public function armada() {
        $model = $this->model('ArmadaModel');
        $data = [
            'title' => 'Kelola Armada',
            'armada' => $model->getAll()
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/armada', $data);
        $this->view('templates/footer');
    }

    public function tambahArmada() {
        $data = ['title' => 'Tambah Armada Baru'];
        $this->view('templates/header', $data);
        $this->view('admin/armada_form', $data);
        $this->view('templates/footer');
    }

    public function editArmada($id) {
        $model = $this->model('ArmadaModel');
        $data = [
            'title' => 'Edit Armada',
            'armada' => $model->findById($id)
        ];
        
        if(!$data['armada']) $this->redirect('/admin/armada');

        $this->view('templates/header', $data);
        $this->view('admin/armada_form', $data);
        $this->view('templates/footer');
    }
    
    // Fitur API untuk Dashboard Stats
    public function getStats() {
        $model = $this->model('ArmadaModel');
        $this->json($model->getStats());
    }

    public function simpanArmada() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ArmadaModel');
            
            $data = [
                'no_polisi' => $_POST['no_polisi'] ?? '',
                'merk' => $_POST['merk'] ?? '',
                'jenis' => $_POST['jenis'] ?? '',
                'tahun' => $_POST['tahun'] ?? '',
                'pajak_date' => $_POST['pajak_date'] ?? '',
                'stnk_date' => $_POST['stnk_date'] ?? '',
                'status' => $_POST['status'] ?? 'tersedia'
            ];

            if (!empty($_POST['id'])) {
                // Update
                $model->update($_POST['id'], $data);
                Session::setFlash('✅ Data Armada berhasil diperbarui!', 'success');
            } else {
                // Insert
                $model->create($data);
                Session::setFlash('✅ Armada baru berhasil ditambahkan!', 'success');
            }
            
            $this->redirect('/admin/armada');
        }
    }

    public function hapusArmada($id) {
        if ($id) {
            $model = $this->model('ArmadaModel');
            $model->delete($id);
            Session::setFlash('🗑️ Armada berhasil dihapus.', 'warning');
        }
        $this->redirect('/admin/armada');
    }

    public function importExcel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $file = $_FILES['file_excel'];
            
            // Validasi ektensi file
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'csv') {
                Session::setFlash('⚠️ Gagal: File harus berformat .csv!', 'danger');
                $this->redirect('/admin/armada');
                return;
            }

            // Pindahkan file sementara
            $targetPath = UPLOAD_PATH . 'temp_' . time() . '.csv';
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                
                $importModel = $this->model('ImportModel');
                $parsedData = $importModel->parseCsv($targetPath);
                $berhasil = $importModel->importToArmada($parsedData);

                // Hapus file sementara
                unlink($targetPath);

                Session::setFlash('✅ Sukses mengimport ' . $berhasil . ' data armada baru!', 'success');
            } else {
                Session::setFlash('⚠️ Gagal mengupload file ke server.', 'danger');
            }
            $this->redirect('/admin/armada');
        }
    }

    // ==========================================
    // MANAJEMEN SERVIS
    // ==========================================
    public function servis() {
        $model = $this->model('ServiceModel');
        $armadaModel = $this->model('ArmadaModel');
        
        $servis = $model->getAll();
        
        // Priority Sorting:
        // 1. Pending/Active (not 'selesai') comes first.
        // 2. For Active/Pending: Overdue or soonest date first (tanggal ascending), then newly created first.
        // 3. For Completed: Newly created/finished first (created_at descending).
        usort($servis, function($a, $b) {
            $statusA = ($a['status'] ?? 'pending') === 'selesai' ? 1 : 0;
            $statusB = ($b['status'] ?? 'pending') === 'selesai' ? 1 : 0;
            
            if ($statusA !== $statusB) {
                return $statusA - $statusB;
            }
            
            if ($statusA === 0) {
                $dateA = strtotime($a['tanggal'] ?? '1970-01-01');
                $dateB = strtotime($b['tanggal'] ?? '1970-01-01');
                if ($dateA !== $dateB) {
                    return $dateA - $dateB;
                }
                
                $createdA = strtotime($a['created_at'] ?? '1970-01-01');
                $createdB = strtotime($b['created_at'] ?? '1970-01-01');
                return $createdB - $createdA;
            } else {
                $createdA = strtotime($a['created_at'] ?? '1970-01-01');
                $createdB = strtotime($b['created_at'] ?? '1970-01-01');
                return $createdB - $createdA;
            }
        });

        $data = [
            'title' => 'Jadwal Servis',
            'servis' => $servis,
            'armada_list' => $armadaModel->getAll()
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/servis', $data);
        $this->view('templates/footer');
    }

    public function tambahServis() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ServiceModel');
            $model->addSchedule([
                'armada_id' => $_POST['armada_id'] ?? '',
                'keterangan' => $_POST['keterangan'] ?? '',
                'tanggal' => $_POST['tanggal'] ?? date('Y-m-d')
            ]);
            Session::setFlash('✅ Jadwal Servis berhasil ditambahkan!', 'success');
            $this->redirect('/admin/servis');
        }
    }

    public function servisSelesai($id) {
        if ($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ServiceModel');
            
            $biaya = $_POST['biaya'] ?? 0;
            $notaPath = '';
            
            if (isset($_FILES['nota']) && $_FILES['nota']['error'] == UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['nota']['name'], PATHINFO_EXTENSION);
                $newName = 'nota_' . time() . '.' . $ext;
                $target = UPLOAD_PATH . 'servis/' . $newName;
                if (move_uploaded_file($_FILES['nota']['tmp_name'], $target)) {
                    $notaPath = $newName;
                }
            }
            
            $model->markDone($id, [
                'biaya' => $biaya,
                'nota' => $notaPath
            ]);

            // Cari jika servis ini berasal dari Laporan (Alert), otomatis selesaikan Laporan tersebut
            $serviceData = $model->getAll();
            $currentSvc = null;
            foreach ($serviceData as $s) {
                if ($s['id'] === $id) { $currentSvc = $s; break; }
            }

            // Kembalikan status armada ke 'tersedia' setelah selesai diservis
            if ($currentSvc && !empty($currentSvc['armada_id'])) {
                $armadaModel = $this->model('ArmadaModel');
                $armadaModel->update($currentSvc['armada_id'], ['status' => 'tersedia']);
            }

            if ($currentSvc && !empty($currentSvc['alert_id'])) {
                $alertModel = $this->model('AlertModel');
                $alertModel->resolve($currentSvc['alert_id'], [
                    'biaya' => $biaya,
                    'nota' => $notaPath
                ]);

                // Cek jika laporannya tipe fatal
                $alertInfo = JsonDB::findById('alerts', $currentSvc['alert_id']);
                if ($alertInfo && $alertInfo['tipe'] === 'fatal') {
                    $armadaModel = $this->model('ArmadaModel');
                    $armadaInfo = $armadaModel->findById($currentSvc['armada_id']);
                    if ($armadaInfo) {
                        $armadaModel->update($currentSvc['armada_id'], [
                            'last_service' => date('Y-m-d'),
                            'next_service' => date('Y-m-d', strtotime('+6 months'))
                        ]);
                    }
                }
            }

            Session::setFlash('✅ Servis ditandai selesai beserta bukti biaya.', 'success');
        }
        $this->redirect('/admin/servis');
    }

    // ==========================================
    // MANAJEMEN USERS
    // ==========================================
    public function users() {
        $model = $this->model('UserModel');
        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $model->getAll()
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/users', $data);
        $this->view('templates/footer');
    }

    public function tambahUser() {
        $data = ['title' => 'Tambah Pengguna Baru'];
        $this->view('templates/header', $data);
        $this->view('admin/user_form', $data);
        $this->view('templates/footer');
    }

    public function editUser($id) {
        $model = $this->model('UserModel');
        $data = [
            'title' => 'Edit Pengguna',
            'user' => $model->findById($id)
        ];
        
        if(!$data['user']) $this->redirect('/admin/users');

        $this->view('templates/header', $data);
        $this->view('admin/user_form', $data);
        $this->view('templates/footer');
    }

    public function simpanUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('UserModel');
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'username' => strtolower(trim($_POST['username'] ?? '')),
                'role' => $_POST['role'] ?? 'driver'
            ];

            // Hanya input password jika tidak kosong
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            if (!empty($_POST['id'])) {
                // Update
                $model->update($_POST['id'], $data);
                Session::setFlash('✅ Data Pengguna berhasil diperbarui!', 'success');
            } else {
                // Insert (Wajib password jika user baru)
                if (empty($_POST['password'])) {
                    Session::setFlash('⚠️ Gagal: Password wajib diisi untuk pengguna baru!', 'danger');
                    $this->redirect('/admin/tambahUser');
                    return;
                }
                
                // Proteksi username unik
                $existing = $model->findByUsername($data['username']);
                if ($existing) {
                    Session::setFlash('⚠️ Gagal: Username @' . $data['username'] . ' sudah digunakan!', 'danger');
                    $this->redirect('/admin/tambahUser');
                    return;
                }

                $model->create($data);
                Session::setFlash('✅ Pengguna baru berhasil ditambahkan!', 'success');
            }
            
            $this->redirect('/admin/users');
        }
    }

    public function hapusUser($id) {
        if ($id) {
            // Mencegah admin menghapus dirinya sendiri jika sedang login
            if ($id === Session::get('user_id')) {
                Session::setFlash('❌ Gagal: Anda tidak bisa menghapus akun Anda sendiri!', 'danger');
            } else {
                $model = $this->model('UserModel');
                $model->delete($id);
                Session::setFlash('🗑️ Pengguna berhasil dihapus.', 'warning');
            }
        }
        $this->redirect('/admin/users');
    }

    // ==========================================
    // MANAJEMEN LAPORAN (ALERTS)
    // ==========================================
    public function laporan() {
        $model = $this->model('AlertModel');
        $armadaModel = $this->model('ArmadaModel');
        $data = [
            'title' => 'Laporan Kerusakan',
            'alerts' => $model->getAll(),
            'armada_list' => $armadaModel->getAll()
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/laporan', $data);
        $this->view('templates/footer');
    }

    public function selesaiLaporan($id) {
        if ($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $alertModel = $this->model('AlertModel');
            
            $biaya = $_POST['biaya'] ?? 0;
            $notaPath = '';
            
            if (isset($_FILES['nota']) && $_FILES['nota']['error'] == UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['nota']['name'], PATHINFO_EXTENSION);
                $newName = 'nota_' . time() . '.' . $ext;
                $target = UPLOAD_PATH . 'servis/' . $newName;
                if (move_uploaded_file($_FILES['nota']['tmp_name'], $target)) {
                    $notaPath = $newName;
                }
            }
            
            $alertModel->resolve($id, [
                'biaya' => $biaya,
                'nota' => $notaPath
            ]);

            // Dapatkan info Alert untuk dicek tipenya
            $alertInfo = JsonDB::findById('alerts', $id);

            // Selalu kembalikan status armada ke 'tersedia' setelah laporan diselesaikan
            if ($alertInfo && !empty($alertInfo['armada_id'])) {
                $armadaModel = $this->model('ArmadaModel');
                $armadaModel->update($alertInfo['armada_id'], ['status' => 'tersedia']);
            }

            // Jika tipenya FATAL (Turun Mesin), update jadwal servis rutin Armada
            if ($alertInfo && $alertInfo['tipe'] === 'fatal') {
                $armadaModel = $this->model('ArmadaModel');
                $armadaInfo = $armadaModel->findById($alertInfo['armada_id']);
                if ($armadaInfo) {
                    $armadaModel->update($alertInfo['armada_id'], [
                        'last_service' => date('Y-m-d'),
                        'next_service' => date('Y-m-d', strtotime('+6 months'))
                    ]);
                }
            }

            // Cari Servis yang terkait dan selesaikan juga
            $serviceModel = $this->model('ServiceModel');
            $allServices = $serviceModel->getAll();
            foreach ($allServices as $svc) {
                if (($svc['alert_id'] ?? '') === $id && $svc['status'] !== 'selesai') {
                    $serviceModel->markDone($svc['id'], [
                        'biaya' => $biaya,
                        'nota' => $notaPath
                    ]);
                    break;
                }
            }
            
            Session::setFlash('✅ Laporan selesai dan Riwayat Servis diperbarui!', 'success');
        }
        $this->redirect('/admin/laporan');
    }

    // Fitur 8: API Endpoint untuk Polling Push Notification
    public function apiCheckUrgent() {
        header('Content-Type: application/json');
        $model = $this->model('AlertModel');
        $alerts = $model->getAll();
        
        $urgentAlert = null;
        foreach($alerts as $a) {
            // Cari laporan urgent/fatal yang statusnya open dan belum dinotifikasi (atau baru masuk 1 jam terakhir)
            if ($a['status'] === 'open' && in_array($a['tipe'], ['urgent', 'fatal'])) {
                $timeDiff = time() - strtotime($a['created_at']);
                // Cuma kirim notif jika kurang dari 1 jam untuk mencegah spam old alerts
                if ($timeDiff < 3600) {
                    $urgentAlert = $a;
                    break;
                }
            }
        }

        if ($urgentAlert) {
            $armadaModel = $this->model('ArmadaModel');
            $armada = $armadaModel->findById($urgentAlert['armada_id']);
            if ($armada) {
                $urgentAlert['no_polisi'] = $armada['no_polisi'];
            } else {
                $urgentAlert['no_polisi'] = 'Tidak Diketahui';
            }
            echo json_encode(['alert' => $urgentAlert]);
        } else {
            echo json_encode(['alert' => false]);
        }
        exit;
    }

    public function exportLaporan() {
        $model = $this->model('AlertModel');
        $alerts = $model->getAll(); // asumsi getAll() mengambil semua histori

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Laporan_SIMANDA_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Laporan', 'Tanggal', 'Armada ID', 'Supir', 'Keterangan', 'Tipe', 'Status', 'Odometer (KM)', 'Lokasi GPS']);
        
        foreach($alerts as $a) {
            $gps = (!empty($a['gps_lat']) && !empty($a['gps_lng'])) ? $a['gps_lat'] . ',' . $a['gps_lng'] : '-';
            fputcsv($output, [
                $a['id'],
                $a['created_at'],
                $a['armada_id'],
                $a['driver_name'],
                $a['keterangan'],
                strtoupper($a['tipe']),
                strtoupper($a['status']),
                $a['km_saat_ini'] ?? '-',
                $gps
            ]);
        }
        fclose($output);
        exit;
    }

    public function downloadTemplateArmada() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Template_Import_Armada.csv"');
        
        $output = fopen('php://output', 'w');
        // Header
        fputcsv($output, ['no_polisi', 'jenis', 'merk', 'tahun', 'status', 'pajak_date', 'stnk_date']);
        // Contoh Baris Beragam (Roda 2, 3, 4, 6 Bermesin & Tidak Bermesin, Format: YYYY-MM-DD)
        fputcsv($output, ['AB1234XY', 'Ambulance', 'Toyota HiAce', '2022', 'tersedia', '2027-05-10', '2031-05-10']);
        fputcsv($output, ['AB5678YZ', 'Roda 2 Bermesin', 'Honda Supra X', '2019', 'tersedia', '2026-11-20', '2029-11-20']);
        fputcsv($output, ['AB9012AA', 'Roda 4 Bermesin', 'Toyota Avanza', '2021', 'service', '2027-02-15', '2031-02-15']);
        fputcsv($output, ['AB3456BB', 'Roda 2 Tidak Bermesin', 'Polygon Monarch', '2023', 'tersedia', '', '']);
        fputcsv($output, ['AB7890CC', 'Roda 6 Bermesin', 'Hino Dutro Truk', '2020', 'tersedia', '2026-08-05', '2030-08-05']);
        fclose($output);
        exit;
    }

    public function exportServis() {
        $model = $this->model('ServiceModel');
        $armadaModel = $this->model('ArmadaModel');
        $servis = $model->getAll();
        $armadas = $armadaModel->getAll();
        
        $filterType = $_GET['filter'] ?? 'all';
        $filterValue = $_GET['value'] ?? ''; // Format: YYYY-MM (e.g. 2026-05)
        
        // Filter data berdasarkan bulan/tahun jika diminta
        if ($filterType === 'month' && !empty($filterValue)) {
            $servis = array_filter($servis, function($s) use ($filterValue) {
                return strpos($s['tanggal'] ?? '', $filterValue) === 0;
            });
            
            // Proteksi: Jika data kosong, gagalkan download dan kembalikan dengan alert
            if (empty($servis)) {
                Session::setFlash('⚠️ Gagal: Tidak ada data perawatan pada bulan ' . $filterValue . '!', 'danger');
                $this->redirect('/admin/servis');
                exit;
            }
        }
        
        $platMap = [];
        foreach($armadas as $a) {
            $platMap[$a['id']] = $a['no_polisi'] . ' (' . $a['merk'] . ')';
        }

        header('Content-Type: text/csv; charset=utf-8');
        
        // Buat nama file dinamis agar rapi
        $filename = "Laporan_Servis_SIMANDA_Semua.csv";
        if ($filterType === 'month' && !empty($filterValue)) {
            $filename = "Laporan_Servis_SIMANDA_" . $filterValue . ".csv";
        }
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        // UTF-8 BOM to ensure Excel opens Indonesian special characters correctly
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['ID Servis', 'Tanggal Jadwal', 'Armada (No Polisi)', 'Jenis Servis', 'Keterangan', 'Status', 'Biaya (Rp)', 'Tanggal Selesai', 'Tanggal Dibuat']);
        
        foreach($servis as $s) {
            fputcsv($output, [
                $s['id'] ?? '-',
                $s['tanggal'] ?? '-',
                $platMap[$s['armada_id']] ?? ($s['armada_id'] ?? '-'),
                $s['jenis_service'] ?? '-',
                $s['keterangan'] ?? '-',
                strtoupper($s['status'] ?? 'PENDING'),
                $s['biaya'] ?? '0',
                $s['selesai_pada'] ?? '-',
                $s['created_at'] ?? '-'
            ]);
        }
        fclose($output);
        exit;
    }

    // ==========================================
    // MANAJEMEN QR CODE & SCANNER
    // ==========================================
    public function scanner() {
        $data = ['title' => 'Admin QR Scanner'];
        $this->view('templates/header', $data);
        $this->view('driver/scanner', $data); // kita pakai view scanner yang sama
        $this->view('templates/footer');
    }

    public function qr($id = null) {
        if (!$id) $this->redirect('/admin/scanner'); // jika tombol FAB ditekan, arahkan ke scanner
        
        $model = $this->model('ArmadaModel');
        $armada = $model->findById($id);
        
        if (!$armada) $this->redirect('/admin/armada');

        $data = [
            'title' => 'Cetak QR Code',
            'armada' => $armada,
            // Kita pakai full URL agar QR bisa di-scan dari HP lain
            'url_umum' => BASE_URL . '/public/vehicle/' . $id,
            'url_driver' => BASE_URL . '/driver/scan/' . $id
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/qr', $data);
        $this->view('templates/footer');
    }

    public function cetakSemuaQr() {
        $model = $this->model('ArmadaModel');
        $allArmada = $model->getAll();

        $data = [
            'title' => 'Cetak Massal QR Code',
            'all_armada' => $allArmada,
            'dark_mode' => false
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/qr_all', $data);
        $this->view('templates/footer');
    }

    // Fitur 11: Dashboard Analitik Eksekutif
    public function executive() {
        $armadaModel = $this->model('ArmadaModel');
        $serviceModel = $this->model('ServiceModel');
        $alertModel = $this->model('AlertModel');

        $armadas = $armadaModel->getAll();
        $services = $serviceModel->getAll();
        $alerts = $alertModel->getAll();

        // Top 3 Kendaraan Paling Sering Rusak
        $rusakCount = [];
        foreach($alerts as $a) {
            $id = $a['armada_id'];
            if ($id === 'TIDAK-DIKETAHUI') continue;
            if (!isset($rusakCount[$id])) $rusakCount[$id] = 0;
            $rusakCount[$id]++;
        }
        arsort($rusakCount);
        $top3Rusak = array_slice($rusakCount, 0, 3, true);

        // Biaya Operasional & KM
        $totalBiaya = 0;
        foreach($services as $s) {
            if ($s['status'] === 'selesai' && !empty($s['biaya_rp'])) {
                $totalBiaya += (int)$s['biaya_rp'];
            }
        }
        $totalKM = 0;
        $mobilSehat = 0;
        $mobilSakit = 0;
        foreach($armadas as $a) {
            $totalKM += (int)($a['km_saat_ini'] ?? 0);
            if ($a['status'] === 'tersedia') $mobilSehat++;
            else $mobilSakit++;
        }
        $costPerKM = $totalKM > 0 ? $totalBiaya / $totalKM : 0;

        $data = [
            'title' => 'Executive Dashboard',
            'top3Rusak' => $top3Rusak,
            'totalBiaya' => $totalBiaya,
            'totalKM' => $totalKM,
            'costPerKM' => $costPerKM,
            'mobilSehat' => $mobilSehat,
            'mobilSakit' => $mobilSakit,
            'armadas' => $armadas
        ];
        
        $this->view('templates/header', $data);
        $this->view('admin/executive', $data);
        $this->view('templates/footer');
    }
}
