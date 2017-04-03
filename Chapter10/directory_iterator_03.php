<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

(new DirectoryIteratorObservable(__DIR__ . '/../symfony_template/src/AppBundle/', '/.+\.php$/', null, false))
//    ->subscribe(new DebugSubject());
    ->subscribeCallback(function(SplFileInfo $file) {
        echo "$file\n";
    });
