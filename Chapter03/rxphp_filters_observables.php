<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable\RangeObservable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\Subject;

$subject = new Subject();

$connObservable = new ConnectableObservable(new RangeObservable(0, 6));
$filteredObservable = $connObservable
    ->map(function($val) {
        return $val ** 2;
    })
    ->filter(function($val) {
        echo "Filter: $val\n";
        return $val % 2;
    })
    ->subscribe($subject);

$disposable1 = $subject->subscribeCallback(function($val) {
    echo "S1: ${val}\n";
});
$disposable2 = $subject->subscribeCallback(function($val) {
    echo "S2: ${val}\n";
});

$connObservable->connect();