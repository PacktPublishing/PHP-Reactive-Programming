<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

$dir = __DIR__ . '/../symfony_template';
(new DirectoryIteratorObservable($dir, '/.+\.php$/'))
    ->subscribeCallback(function(SplFileInfo $file) {
        echo "$file\n";
    });
