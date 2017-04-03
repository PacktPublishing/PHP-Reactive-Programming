<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once '../Chapter 02/CURLObservable.php';

use Rx\Observable;

Observable::defer(function() {
        echo "Observable::defer()\n";
        return new CurlObservable('https://example.com123');
    })
    ->retry(3)
    ->subscribe(new DebugSubject());