<?php
class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            // Kredensial dibaca dari Config (app/config/config.local.php), bukan di-hardcode di sini
            $host = Config::db('host');
            $db = Config::db('name');
            $charset = Config::db('charset');
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, Config::db('user'), Config::db('pass'), $options);
            } catch (PDOException $e) {
                error_log('Koneksi database gagal: ' . $e->getMessage()); // detail hanya ke log server
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
                exit;
            }
        }
        return self::$connection;
    }
}