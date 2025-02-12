<?php

spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    
    $fileName = array_pop($parts) . '.php';
        $path = '';
    if (!empty($parts)) {
        $path = strtolower(implode('/', $parts)) . '/';
    }
    
    $fullPath = $path . $fileName;
    if (file_exists($fullPath)) {
        include $fullPath;
    }
});

require_once __DIR__ . '/core/helpers.php';

$core = Core\Core::getInstance();
$core->init();
$core->run();
$core->done();

