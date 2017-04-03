<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

Rx\Observable::just('{"value":42}')
    ->map(function($value) {
        return json_decode($value, true);
    })
    ->subscribe(new DebugSubject());
