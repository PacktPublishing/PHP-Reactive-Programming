<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;

$source = Observable::defer(function() {
        printf("Observable::defer\n");
        return Observable::range(1, 3);
    })
    ->replay(null, 1);

$source->subscribe(new DebugSubject('1'));
$source->connect();

//$sub1->dispose();
$sub2 = $source->subscribe(new DebugSubject('2'));

