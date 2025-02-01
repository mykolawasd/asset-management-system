<?php

spl_autoload_register(function($class) {
   $path = str_replace('\\', '/', $class) . '.php';
   if (file_exists($path)) {
        include $path;
   }
});

require_once __DIR__ . '/Core/helpers.php';

$core = Core\Core::getInstance();
$core->init();
$core->run();
$core->done();

// $db = new Core\Database();
// $result = $db->query("SELECT * FROM users");

// var_dump($result->fetchAll(\PDO::FETCH_ASSOC));


