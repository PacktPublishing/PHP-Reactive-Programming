<?php

require_once '../vendor/autoload.php';

use React\Promise\Deferred;

$deferred = new Deferred();

$deferred->promise()
    ->then(function($val) {
        echo "then #1: $val\n";
        return $val + 1;
    })
    ->then(function($val) {
        echo "then #2: $val\n";
        return $val + 1;
    })
    ->done(function($val) {
        echo "done: $val\n";
    });

$deferred->resolve(42);
