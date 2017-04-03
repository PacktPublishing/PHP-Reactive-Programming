<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

Rx\Observable::range(1, 6)
    ->map(function($val) {
        if ($val == 3) {
            throw new \Exception('error');
        }
        return $val;
    })
    ->retry(3)
    ->subscribe(new DebugSubject());
