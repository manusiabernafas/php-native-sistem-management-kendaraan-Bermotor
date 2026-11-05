<?php
/**
 * UserModel
 * Mengelola data User (Admin & Driver)
 */
class UserModel {
    private $table = 'users';

    public function getAll() {
        return JsonDB::read($this->table);
    }

    public function findByUsername($username) {
        $users = JsonDB::where($this->table, 'username', $username);
        return !empty($users) ? $users[0] : null;
    }

    public function findById($id) {
        return JsonDB::findById($this->table, $id);
    }

    public function create($data) {
        // Hash password menggunakan BCRYPT
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        return JsonDB::insert($this->table, $data);
    }

    public function update($id, $data) {
        // Hash password menggunakan BCRYPT jika password baru diisi
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            // Hapus agar password lama tidak tertimpa/hilang
            unset($data['password']);
        }
        return JsonDB::update($this->table, $id, $data);
    }

    public function delete($id) {
        return JsonDB::delete($this->table, $id);
    }
    
    // Fungsi bantuan untuk men-generate default admin pertama kali
    public function seedDefaultAdmin() {
        $users = $this->getAll();
        if (empty($users)) {
            $this->create([
                'id' => 'USR-ADMIN-01',
                'name' => 'Admin Utama',
                'username' => 'admin',
                'password' => 'admin123', // Akan otomatis di-hash
                'role' => 'admin'
            ]);
            
            $this->create([
                'id' => 'USR-DRIVER-01',
                'name' => 'Supir Budi',
                'username' => 'driver',
                'password' => 'driver123',
                'role' => 'driver'
            ]);
        }
    }
}
