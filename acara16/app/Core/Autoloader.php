
<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';

    $folders = [
        'Core/',
        'controllers/',
        'models/',
        'repositories/',
        'services/'
    ];

    foreach ($folders as $folder) {
        $file = $baseDir . $folder . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
