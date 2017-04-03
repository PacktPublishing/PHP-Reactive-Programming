<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;


$source = Observable::defer(function() {
    return Observable::range(0, rand(1, 10));
});

$source->subscribe(new DebugSubject('#1'));
$source->subscribe(new DebugSubject('#2'));