<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;

$source = Observable::create(function(ObserverInterface $obs) {
    echo "Observable::create\n";
    $obs->onNext(1);
    $obs->onNext('Hello, World!');
    $obs->onNext(2);
    $obs->onCompleted();
});

$source->subscribe(new DebugSubject());
$source->subscribe(new DebugSubject());