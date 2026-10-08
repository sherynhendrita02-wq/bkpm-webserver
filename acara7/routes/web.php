<?php

$routes = [
    'GET' => [
        '/' => ['HomeController', 'index'],

        '/login' => ['AuthController', 'loginForm'],

        '/dashboard' => [
            'DashboardController',
            'index',
            'middleware' => ['AuthMiddleware']
        ],

        '/mahasiswa' => [
            'MahasiswaController',
            'index',
            'middleware' => ['AuthMiddleware']
        ],

        '/mahasiswa/create' => [
            'MahasiswaController',
            'create',
            'middleware' => ['AuthMiddleware']
        ],

        '/logout' => ['AuthController', 'logout'],
    ],

    'POST' => [
        '/login' => ['AuthController', 'login'],

        '/mahasiswa' => [
            'MahasiswaController',
            'store',
            'middleware' => ['AuthMiddleware']
        ],
    ],
];