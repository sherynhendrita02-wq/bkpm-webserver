<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): string {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role) 
             VALUES (:username, :email, :password, :role)'
        );
        
        $stmt->execute([
            ':username' => $data['username'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':role'     => $data['role'] ?? 'user'
        ]);
        
        return $this->db->lastInsertId();
    }
}