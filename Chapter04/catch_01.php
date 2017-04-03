<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

Rx\Observable::range(1,6)
    ->map(function($val) {
        if ($val == 3) {
            throw new \Exception();
        }
        return $val;
    })
    ->catchError(function(Exception $e, \Rx\Observable $sourceOb) {
        return \Rx\Observable::just(42);
    })
    ->subscribe(new DebugSubject());