<?php

function e($var) {
    echo htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

function dd($var) {
    var_dump($var);
    die();
}
