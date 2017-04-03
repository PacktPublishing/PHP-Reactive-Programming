<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable\RangeObservable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\Subject;

$subject = new Subject();

$source = new RangeObservable(0, 6);
$filteredObservable = $source
    ->map(function($val) {
        return $val ** 2;
    })
    ->filter(function($val) {
        echo "Filter: $val\n";
        return $val % 2;
    });

$disposable1 = $subject->subscribeCallback(function($val) {
    echo "S1: ${val}\n";
});
$disposable2 = $subject->subscribeCallback(function($val) {
    echo "S2: ${val}\n";
});

$filteredObservable->subscribe($subject);