<?php
/**
 * ServiceModel
 * Mengelola data jadwal dan riwayat Servis/Perawatan Armada (data/service.json)
 */
class ServiceModel {
    private $table = 'service';

    public function getAll() {
        $data = JsonDB::read($this->table);
        // Urutkan jadwal terbaru/terdekat di atas
        usort($data, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });
        return $data;
    }

    public function getByArmada($armada_id) {
        $data = JsonDB::where($this->table, 'armada_id', $armada_id);
        usort($data, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });
        return $data;
    }

    public function addSchedule($data) {
        $data['status'] = 'pending'; // Default status
        return JsonDB::insert($this->table, $data);
    }

    public function markDone($id, $additionalData = []) {
        $updateData = [
            'status' => 'selesai',
            'selesai_pada' => date('Y-m-d H:i:s')
        ];
        $updateData = array_merge($updateData, $additionalData);
        return JsonDB::update($this->table, $id, $updateData);
    }
    
    public function delete($id) {
        return JsonDB::delete($this->table, $id);
    }
}
