<?php
spl_autoload_register(function ($class) {
    $folders = ['models', 'controllers', 'core', 'config'];
    foreach ($folders as $folder) {
        $file = __DIR__ . '/../' . $folder . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});