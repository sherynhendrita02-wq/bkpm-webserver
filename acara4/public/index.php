<?php

require_once __DIR__ . '/../app/models/Mahasiswa.php';

$mahasiswa = [
    new Mahasiswa(
        '25001',
        'Sheryn Febrylia Hendrita',
        'Teknik Informatika'
    ),

    new Mahasiswa(
        '25002',
        'Ingka Jivanda Gayshela',
        'Teknik Informatika'
    ),

    new Mahasiswa(
        '25003',
        'Dinda Febiola Rachmawati',
        'Teknik Informatika'
    )
];

$title = 'Data Mahasiswa';

ob_start();

require __DIR__ . '/../app/views/mahasiswa/index.php';

$content = ob_get_clean();

require __DIR__ . '/../app/views/layouts/main.php';