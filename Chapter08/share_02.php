<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;

$source = Observable::create(function(ObserverInterface $observer) {
        $observer->onNext(1);
        $observer->onNext(2);
        $observer->onNext(3);
//        $observer->onCompleted();
    })
    ->share();

$sub1 = $source->subscribe(new DebugSubject('1'));
$sub1->dispose();
$sub2 = $source->subscribe(new DebugSubject('2'));
