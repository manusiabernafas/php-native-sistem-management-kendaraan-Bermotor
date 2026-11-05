<?php
/**
 * ImportModel
 * Menangani parsing file CSV/Excel dan menyimpannya ke database JSON
 */
class ImportModel {
    
    /**
     * Parse file CSV sederhana
     * Format Kolom yang diharapkan:
     * no_polisi | jenis | merk | tahun | status
     */
    public function parseCsv($filePath) {
        $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Ambil baris pertama sebagai header (akan diabaikan)
            $header = fgetcsv($handle, 1000, ",");
            
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Pastikan baris memiliki setidaknya 5 kolom
                if (count($row) >= 5) {
                    $data[] = [
                        'no_polisi' => trim($row[0]),
                        'jenis' => trim($row[1]),
                        'merk' => trim($row[2]),
                        'tahun' => (int)trim($row[3]),
                        'status' => trim(strtolower($row[4])),
                        'pajak_date' => isset($row[5]) ? trim($row[5]) : '',
                        'stnk_date' => isset($row[6]) ? trim($row[6]) : ''
                    ];
                }
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Memasukkan data hasil parsing ke armada.json
     */
    public function importToArmada($parsedData) {
        require_once APP_PATH . 'models/ArmadaModel.php';
        $armadaModel = new ArmadaModel();
        $berhasil = 0;

        foreach ($parsedData as $row) {
            // Validasi sederhana
            if (!empty($row['no_polisi'])) {
                // Cek apakah No Polisi sudah ada (Opsional, agar tidak duplikat)
                // Jika ingin cepat, kita anggap semua row baru
                $armadaModel->create($row);
                $berhasil++;
            }
        }
        
        return $berhasil;
    }
}
