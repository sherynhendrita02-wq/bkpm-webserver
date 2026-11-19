<?php
class Database {
    // 🔧 Ganti ke 'production' saat deploy
    // Bisa juga ambil dari environment: getenv('APP_ENV') ?: 'production'
    const APP_ENV = 'development';

    private static ?PDO $connection = null;

    /**
     * Flag untuk json_encode.
     * Di development → JSON rapi (memudahkan debug).
     * Di production  → JSON ringkas (hemat bandwidth).
     */
    public static function getJsonFlags(): int {
        return self::APP_ENV === 'development' ? JSON_PRETTY_PRINT : 0;
    }

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $host = 'localhost';
            $db = 'db_workshop';
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, 'root', '', $options);
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(
                    ['success' => false, 'message' => 'Koneksi database gagal'],
                    self::getJsonFlags()   // ✅ flag mengikuti APP_ENV
                );
                exit;
            }
        }
        return self::$connection;
    }
}