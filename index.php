<?php

spl_autoload_register(function($class) {
   $path = str_replace('\\', '/', $class) . '.php';
   if (file_exists($path)) {
        include $path;
   }
});


$core = Core\Core::getInstance();
$core->init();
$core->run();
$core->done();

