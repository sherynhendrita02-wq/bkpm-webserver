<?php
// SALIN berkas ini menjadi config.local.php di server hosting, lalu isi nilai sebenarnya.
// config.local.php sudah masuk .gitignore sehingga tidak ikut ke GitHub.
return [
    'env' => 'production',
    'db' => [
        'host' => 'localhost',
        'name' => 'namacpanel_dbworkshop',
        'user' => 'namacpanel_dbuser',
        'pass' => 'password_sesuai_cpanel',
    ],
    // Buat nilai acak panjang, misalnya dari: php -r "echo bin2hex(random_bytes(32));"
    'jwt_secret'  => 'GANTI_DENGAN_NILAI_ACAK_PANJANG',
    'cors_origin' => 'https://DOMAIN-HOSTING-ANDA',
    'error_log'   => '/home/namacpanel/logs/php_errors.log',
];
