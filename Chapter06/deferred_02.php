<?php

require_once '../vendor/autoload.php';

use React\Promise\Deferred;

$deferred = new Deferred();

$deferred->promise()
    ->then(function($val) {
        echo "Then: $val\n";
        throw new \Exception('This is an exception');
    })
    ->otherwise(function($reason) {
        echo 'Error: '. $reason->getMessage() . "\n";
    })
    ->always(function() {
        echo "Do cleanup\n";
    });

//$deferred->resolve(42);
$deferred->reject(new \Exception('This is an exception'));
