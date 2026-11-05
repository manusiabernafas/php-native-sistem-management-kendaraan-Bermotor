<?php
/**
 * JsonDB Engine
 * Flat-file JSON Database untuk menggantikan MySQL
 * Lokasi file: /data/*.json
 */

class JsonDB {
    
    /**
     * Memastikan file JSON ada. Jika tidak ada, buat baru dengan Array kosong.
     */
    private static function initFile($table) {
        if (!is_dir(DATA_PATH)) {
            mkdir(DATA_PATH, 0775, true);
        }
        $file = DATA_PATH . $table . '.json';
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
            chmod($file, 0664);
        }
        return $file;
    }

    /**
     * Baca semua data dari tabel JSON
     */
    public static function read($table) {
        $file = self::initFile($table);
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }

    /**
     * Tulis ulang (overwrite) semua data ke tabel JSON dengan aman (LOCK_EX)
     */
    public static function write($table, $data) {
        $file = self::initFile($table);
        // LOCK_EX mencegah tabrakan data jika ada banyak user yang klik simpan bersamaan
        $result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
        return $result !== false;
    }

    /**
     * Cari 1 baris berdasarkan ID
     */
    public static function findById($table, $id) {
        $data = self::read($table);
        foreach ($data as $row) {
            if (isset($row['id']) && $row['id'] === $id) {
                return $row;
            }
        }
        return null;
    }

    /**
     * Filter banyak data berdasarkan kondisi sederhana (WHERE key = value)
     */
    public static function where($table, $field, $value) {
        $data = self::read($table);
        $result = [];
        foreach ($data as $row) {
            if (isset($row[$field]) && $row[$field] === $value) {
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * Tambah data baru ke JSON
     */
    public static function insert($table, $newRow) {
        $data = self::read($table);
        
        // Auto assign ID unik jika belum ada (Format: TBL-Timestamp-Random)
        if (!isset($newRow['id'])) {
            $prefix = strtoupper(substr($table, 0, 3));
            $newRow['id'] = self::generateId($prefix);
        }
        
        // Auto assign timestamp
        if (!isset($newRow['created_at'])) {
            $newRow['created_at'] = date('Y-m-d H:i:s');
        }

        $data[] = $newRow;
        return self::write($table, $data) ? $newRow['id'] : false;
    }

    /**
     * Update data berdasarkan ID
     */
    public static function update($table, $id, $updatedData) {
        $data = self::read($table);
        $found = false;
        
        foreach ($data as $key => $row) {
            if (isset($row['id']) && $row['id'] === $id) {
                // Merge data lama dan baru (menimpa atribut yang berubah)
                $data[$key] = array_merge($row, $updatedData);
                $data[$key]['id'] = $id; // Proteksi agar ID asli tidak tertimpa
                $found = true;
                break;
            }
        }
        
        if ($found) {
            return self::write($table, $data);
        }
        return false;
    }

    /**
     * Hapus data berdasarkan ID
     */
    public static function delete($table, $id) {
        $data = self::read($table);
        $initialCount = count($data);
        
        // Filter array, buang data yang ID nya cocok
        $data = array_filter($data, function($row) use ($id) {
            return isset($row['id']) && $row['id'] !== $id;
        });
        
        // Re-index array agar JSON tetap menjadi [{}, {}], bukan objek {"0": {}, "2": {}}
        $data = array_values($data);
        
        if (count($data) < $initialCount) {
            return self::write($table, $data);
        }
        return false; 
    }

    /**
     * Generator ID Unik
     */
    public static function generateId($prefix = 'ID') {
        return $prefix . '-' . time() . rand(10, 99);
    }
}
