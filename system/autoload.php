<?php

spl_autoload_register(function ($class) {
    if (trim($class, '\\') == 'Sys') {
        include __DIR__ . '/Sys.php';
    } else {
        include __DIR__ . '/../' . $class . '.php';
    }
});