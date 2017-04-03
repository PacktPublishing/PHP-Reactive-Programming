<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';

Rx\Observable::range(1, 7)
    ->bufferWithCount(3)
    ->subscribe(new DebugSubject());