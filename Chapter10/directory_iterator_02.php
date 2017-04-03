<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

(new DirectoryIteratorObservable('.', '/.+\.php$/'))
//    ->subscribe(new DebugSubject());
    ->subscribeCallback(function(SplFileInfo $file) {
        echo "$file\n";
    });
