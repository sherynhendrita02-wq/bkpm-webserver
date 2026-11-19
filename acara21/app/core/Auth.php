
<?php

class Auth
{
    const SECRET = 'kunci_rahasia_workshop_2026';

    // Mengubah data menjadi Base64URL
    private static function base64url(string $data): string
    {
        return rtrim(
            strtr(base64_encode($data), '+/', '-_'),
            '='
        );
    }

    // Mengubah Base64URL kembali menjadi data
    private static function base64urlDecode(string $data): string|false
    {
        $data = strtr($data, '-_', '+/');
        $data .= str_repeat('=', (4 - strlen($data) % 4) % 4);

        return base64_decode($data, true);
    }

    // Membuat JWT Token
    public static function generateToken(array $payload): string
    {
        $header = self::base64url(
            json_encode([
                'alg' => 'HS256',
                'typ' => 'JWT'
            ])
        );

        // Token berlaku selama 1 jam
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600;

        $payloadEnc = self::base64url(
            json_encode($payload)
        );

        $signature = self::base64url(
            hash_hmac(
                'sha256',
                "$header.$payloadEnc",
                self::SECRET,
                true
            )
        );

        return "$header.$payloadEnc.$signature";
    }

    // Memvalidasi JWT Token
    public static function validateToken(string $token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        // Memeriksa signature
        $validSignature = self::base64url(
            hash_hmac(
                'sha256',
                "$header.$payload",
                self::SECRET,
                true
            )
        );

        if (!hash_equals($validSignature, $signature)) {
            return false;
        }

        // Membaca payload JWT
        $decoded = self::base64urlDecode($payload);

        if ($decoded === false) {
            return false;
        }

        $data = json_decode($decoded, true);

        if (!is_array($data)) {
            return false;
        }

        // Memeriksa waktu kedaluwarsa
        if (!isset($data['exp']) || $data['exp'] <= time()) {
            return false;
        }

        return $data;
    }

    // Middleware autentikasi
    public static function requireAuth(): array
    {
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $authHeader = '';

        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $authHeader = $value;
                break;
            }
        }

        if ($authHeader === '') {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        }

        // Memeriksa format Bearer Token
        if (!preg_match('/^Bearer\s+(\S+)$/i', trim($authHeader), $m)) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($m[1]);

        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }

        return $payload;
    }
}
