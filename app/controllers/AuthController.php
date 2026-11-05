<?php
/**
 * AuthController
 * Menangani Login dan Logout
 */
class AuthController extends Controller {

    public function index() {
        // Jika sudah login, redirect sesuai rolenya
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['role']);
        }
        
        // Buat admin default jika JSON users kosong
        $userModel = $this->model('UserModel');
        $userModel->seedDefaultAdmin();

        $data = ['title' => 'Login - SIMANDA'];
        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
        $this->view('templates/footer');
    }

    public function proses() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('UserModel');
            $user = $userModel->findByUsername($username);

            if ($user) {
                // Verifikasi password (Bcrypt)
                if (password_verify($password, $user['password'])) {
                    // Set session
                    Session::set('user_id', $user['id']);
                    Session::set('username', $user['username']);
                    Session::set('name', $user['name']);
                    Session::set('role', $user['role']);

                    $this->redirectBasedOnRole($user['role']);
                    return;
                }
            }

            Session::setFlash('Username atau password salah!', 'danger');
            $this->redirect('/auth');
        } else {
            $this->redirect('/auth');
        }
    }

    public function logout() {
        Session::destroy();
        header('Location: ' . BASE_URL . '/auth');
        exit;
    }

    private function redirectBasedOnRole($role) {
        // Cek apakah ada target redirect tersimpan (misal dari scan QR sebelum login)
        $redirectTarget = Session::get('redirect_target');
        if (!empty($redirectTarget)) {
            Session::set('redirect_target', null); // Bersihkan target agar tidak terulang
            $this->redirect($redirectTarget);
            return;
        }

        if ($role === 'admin') {
            $this->redirect('/admin');
        } else if ($role === 'driver') {
            $this->redirect('/driver');
        } else {
            $this->redirect('');
        }
    }
}
