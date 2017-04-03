<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable\RangeObservable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\Subject;

$connObs = new ConnectableObservable(new RangeObservable(0, 6));

$filteredObservable = $connObs
    ->map(function($val) {
        return $val ** 2;
    })
    ->filter(function($val) {
        echo "Filter: $val\n";
        return $val % 2;
    });

$disposable1 = $filteredObservable->subscribeCallback(function($val) {
    echo "S1: ${val}\n";
});
$disposable2 = $filteredObservable->subscribeCallback(function($val) {
    echo "S2: ${val}\n";
});

$connObs->connect();