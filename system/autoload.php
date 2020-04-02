<?php

spl_autoload_register(function ($class) {
    if (trim($class, '\\') == 'Sys') {
        include __DIR__ . '/Sys.php';
    } else {
		$pathClass = str_replace('\\', '/', $class);
        include __DIR__ . '/../' . $pathClass . '.php';
    }
});