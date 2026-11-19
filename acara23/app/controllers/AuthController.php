<?php
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function register(): void {
        // ... (Biarkan kode register yang lama dari Acara 19, tidak perlu diubah)
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) Response::json(400, false, 'Body JSON tidak valid');
        
        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') $errors[$field] = "Field {$field} wajib diisi";
        }
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = "Format email tidak valid";
        if ($errors) Response::json(400, false, 'Validasi gagal', null, $errors);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        try {
            $id = $this->model->create($data);
            Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') Response::json(409, false, 'Username atau Email sudah terdaftar');
            Response::json(500, false, 'Registrasi gagal');
        }
    }

    // --- TAMBAHKAN FUNGSI LOGIN INI ---
    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // Cari user di database
        $user = $this->model->findByUsername($username);

        // Verifikasi password (password_verify mencocokkan teks asli dengan hash di DB)
        if (!$user || !password_verify($password, $user['password'])) {
            Response::json(401, false, 'Username atau password salah');
        }

        // Buat Token JWT
        $token = Auth::generateToken(['id' => $user['id'], 'role' => $user['role']]);
        
        Response::json(200, true, 'Login berhasil', [
            'token' => $token, 
            'role'  => $user['role']
        ]);
    }
}