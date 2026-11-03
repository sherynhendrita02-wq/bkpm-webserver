<?php
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!is_array($data)) {
            Response::json(400, false, 'Body JSON tidak valid');
        }

        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "Field {$field} wajib diisi";
            }
        }
        
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format email tidak valid";
        }

        if ($errors) {
            Response::json(400, false, 'Validasi gagal', null, $errors);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $id = $this->model->create($data);
            Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json(409, false, 'Username atau Email sudah terdaftar');
            }
            Response::json(500, false, 'Registrasi gagal, terjadi kesalahan server');
        }
    }
}