<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\ReplaySubject;
use Rx\Subject\Subject;

$subject = new Subject();

$source = Observable::range(1, 3)
    ->multicast($subject, function(ConnectableObservable $connectable) {
        return $connectable->startWith('start');
    })
    ->concat(Observable::just('concat'));

$source->subscribe(new DebugSubject());
$source->subscribe(new DebugSubject());


