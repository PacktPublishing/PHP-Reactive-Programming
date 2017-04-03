<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

Observable::mergeAll(
    Observable::range(1, 3)
        ->map(function($value) {
            return Observable::range(0, $value);
        })
    )
    ->subscribe(new DebugSubject());
