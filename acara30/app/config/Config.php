<?php
// Acara 30: pemisahan kredensial dari kode sumber.
// Nilai rahasia dibaca dari app/config/config.local.php (TIDAK ikut Git, lihat .gitignore).
// Jika berkas itu tidak ada (mis. di komputer pengembangan), dipakai nilai bawaan lokal di bawah ini.
class Config {
    private static ?array $data = null;

    private static function defaults(): array {
        return [
            'env'        => 'development',            // 'production' di hosting
            'db' => [
                'host'    => 'localhost',
                'name'    => 'db_workshop',
                'user'    => 'root',
                'pass'    => '',
                'charset' => 'utf8mb4',
            ],
            'jwt_secret' => 'kunci_rahasia_workshop_2026', // GANTI di config.local.php pada produksi
            'cors_origin' => '*',                      // di produksi sebaiknya https://domain-anda
            'error_log'  => null,                      // contoh: /home/namacpanel/logs/php_errors.log
        ];
    }

    private static function load(): array {
        if (self::$data === null) {
            $data = self::defaults();
            $file = __DIR__ . '/config.local.php';
            if (is_file($file)) {
                $local = require $file;
                if (is_array($local)) {
                    // db digabung per kunci; kunci lain menimpa nilai bawaan
                    $data['db'] = array_merge($data['db'], $local['db'] ?? []);
                    unset($local['db']);
                    $data = array_merge($data, $local);
                }
            }
            self::$data = $data;
        }
        return self::$data;
    }

    public static function get(string $key, mixed $default = null): mixed {
        return self::load()[$key] ?? $default;
    }

    public static function db(string $key): string {
        return (string) (self::load()['db'][$key] ?? '');
    }

    public static function isProduction(): bool {
        return self::get('env') === 'production';
    }
}
