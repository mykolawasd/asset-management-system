<?php

spl_autoload_register(function($class) {
    $path = strtolower(str_replace('\\', '/', $class)) . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

require_once __DIR__ . '/core/helpers.php';

$core = Core\Core::getInstance();
$core->init();
$core->run();
$core->done();

