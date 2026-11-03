<?php
class Auth {
    // Kunci rahasia (jangan diubah sembarangan)
    const SECRET = 'kunci_rahasia_workshop_2026';

    // Helper untuk Base64URL
    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-'), '=');
    }

    // 1. Membuat Token JWT
    public static function generateToken(array $payload): string {
        $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        
        // Tambahkan waktu kedaluwarsa (1 jam dari sekarang)
        $payload['exp'] = time() + 300; 
        $payloadEnc = self::base64url(json_encode($payload));
        
        // Buat Signature
        $signature = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));
        
        return "$header.$payloadEnc.$signature";
    }

    // 2. Validasi Token
    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) != 3) return false;
        
        [$header, $payload, $signature] = $parts;
        
        // Cek apakah signature cocok
        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) return false;
        
        // Cek apakah token sudah kedaluwarsa
        $data = json_decode(base64_decode($payload), true);
        if ($data['exp'] < time()) return false;
        
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
}