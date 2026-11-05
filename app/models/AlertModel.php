<?php
/**
 * AlertModel
 * Mengelola data laporan / SOS dari Driver (data/alerts.json)
 */
class AlertModel {
    private $table = 'alerts';

    public function getAll() {
        // Sort descending (terbaru di atas)
        $data = JsonDB::read($this->table);
        usort($data, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return $data;
    }

    public function getOpen() {
        return JsonDB::where($this->table, 'status', 'open');
    }

    public function create($data) {
        $data['status'] = 'open'; // default status
        return JsonDB::insert($this->table, $data);
    }

    public function resolve($id, $additionalData = []) {
        $updateData = [
            'status' => 'resolved',
            'resolved_at' => date('Y-m-d H:i:s')
        ];
        $updateData = array_merge($updateData, $additionalData);
        return JsonDB::update($this->table, $id, $updateData);
    }
}
