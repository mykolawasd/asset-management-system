<?php

function e($var) {
    echo htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

function h($var): string {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

function dd($var) {
    var_dump($var);
    die();
}

function truncateHtml($text, $maxLength) {
    if (strlen($text) <= $maxLength) {
        return $text;
    }

    $truncated = substr($text, 0, $maxLength);
    $lastSpace = strrpos($truncated, ' ');

    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }

    return $truncated . '...';
}
