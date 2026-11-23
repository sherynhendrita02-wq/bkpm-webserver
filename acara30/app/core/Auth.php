<?php
class Auth {
    // Kunci rahasia dibaca dari Config (config.local.php), tidak lagi di-hardcode pada kode
    private static function secret(): string {
        return (string) Config::get('jwt_secret');
    }

    // Helper untuk Base64URL
    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // 1. Membuat Token JWT
    public static function generateToken(array $payload): string {
        $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        
        // Tambahkan waktu kedaluwarsa (5 menit dari sekarang)
        $payload['exp'] = time() + 300; 
        $payloadEnc = self::base64url(json_encode($payload));
        
        // Buat Signature
        $signature = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::secret(), true));
        
        return "$header.$payloadEnc.$signature";
    }

    // 2. Validasi Token
    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) != 3) return false;
        
        [$header, $payload, $signature] = $parts;
        
        // Cek apakah signature cocok
        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::secret(), true));
        if (!hash_equals($valid, $signature)) return false;
        
        // Cek apakah token sudah kedaluwarsa
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        if (!is_array($data) || ($data['exp'] ?? 0) < time()) return false;
        
        return $data;
    }

    // 3. Middleware: Paksa Cek Token di Header
    public static function requireAuth(): array {
        // getallheaders() kadang tidak ada di server tertentu, ini alternatif amannya
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $authHeader = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        // Cek format "Bearer <token>"
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $m)) {
            Response::json(401, false, 'Token tidak ditemukan');
        }
        
        $payload = self::validateToken($m[1]);
        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }
        
        return $payload;
    }

    // 4. Middleware Otorisasi (RBAC): hanya role admin yang diizinkan
    public static function requireAdmin(): array {
        $payload = self::requireAuth();
        if (($payload['role'] ?? '') !== 'admin') {
            Response::json(403, false, 'Akses ditolak, hanya admin yang diizinkan');
        }
        return $payload;
    }
}
