<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

(new DirectoryIteratorObservable(__DIR__ . '/../symfony_template', '/.+\.php$/', function(SplFileInfo $file) {
    return $file->getBasename();
}))
//    ->subscribe(new DebugSubject());
    ->subscribeCallback(function($file) {
        echo "$file\n";
    });
