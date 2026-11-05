<?php
/**
 * ArmadaModel
 * Mengelola data master kendaraan (data/armada.json)
 */
class ArmadaModel {
    private $table = 'armada';

    public function getAll() {
        return JsonDB::read($this->table);
    }

    public function findById($id) {
        return JsonDB::findById($this->table, $id);
    }

    public function create($data) {
        return JsonDB::insert($this->table, $data);
    }

    public function update($id, $data) {
        return JsonDB::update($this->table, $id, $data);
    }

    public function delete($id) {
        return JsonDB::delete($this->table, $id);
    }

    /**
     * Hitung statistik untuk Dashboard
     */
    public function getStats() {
        $armada = $this->getAll();
        $stats = [
            'total' => count($armada),
            'tersedia' => 0,
            'service' => 0,
            'rusak' => 0
        ];

        foreach ($armada as $a) {
            $status = strtolower($a['status'] ?? '');
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }
        return $stats;
    }
}
